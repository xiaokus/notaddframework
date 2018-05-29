<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:15
 */
namespace Notadd\Foundation\GraphQL;

use GraphQL\Error\Error;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use Illuminate\Support\Arr;
use Notadd\Foundation\GraphQL\Errors\ValidationError;
use Notadd\Foundation\GraphQL\Exceptions\SchemaNotFoundException;
use Notadd\Foundation\GraphQL\Exceptions\TypeNotFoundException;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class GraphQL.
 */
class GraphQLManager
{
    use Helpers;

    /**
     * @var array
     */
    protected $schemas = [];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var array
     */
    protected $typesInstances = [];

    /**
     * @param null $schema
     *
     * @return null
     * @throws \Notadd\Foundation\GraphQL\Exceptions\SchemaNotFoundException
     */
    public function schema($schema = null)
    {
        if ($schema instanceof Schema) {
            return $schema;
        }
        $schemaName = is_string($schema) ? $schema : $this->config->get('graphql.schema', 'default');
        if (!is_array($schema) && !isset($this->schemas[$schemaName])) {
            throw new SchemaNotFoundException('Type ' . $schemaName . ' not found.');
        }
        $schema = is_array($schema) ? $schema : $this->schemas[$schemaName];
        $schemaQuery = Arr::get($schema, 'query', []);
        $schemaMutation = Arr::get($schema, 'mutation', []);
        $schemaSubscription = Arr::get($schema, 'subscription', []);
        $schemaTypes = Arr::get($schema, 'types', []);
        $types = [];
        if (sizeof($schemaTypes)) {
            foreach ($schemaTypes as $name => $type) {
                $objectType = $this->objectType($type, is_numeric($name) ? [] : [
                    'name' => $name,
                ]);
                $this->typesInstances[$name] = $objectType;
                $types[] = $objectType;
            }
        } else {
            foreach ($this->types as $name => $type) {
                $types[] = $this->type($name);
            }
        }
        $query = $this->objectType($schemaQuery, [
            'name' => 'Query',
        ]);
        $mutation = $this->objectType($schemaMutation, [
            'name' => 'Mutation',
        ]);
        $subscription = $this->objectType($schemaSubscription, [
            'name' => 'Subscription',
        ]);

        return new Schema([
            'query'        => $query,
            'mutation'     => !empty($schemaMutation) ? $mutation : null,
            'subscription' => !empty($schemaSubscription) ? $subscription : null,
            'types'        => $types,
        ]);
    }

    /**
     * Clear Type Instances.
     */
    protected function clearTypeInstances()
    {
        $this->typesInstances = [];
    }

    /**
     * @param      $name
     * @param bool $fresh
     *
     * @return mixed
     * @throws \Notadd\Foundation\GraphQL\Exceptions\TypeNotFoundException
     */
    public function type($name, $fresh = false)
    {
        if (!isset($this->types[$name])) {
            throw new TypeNotFoundException('Type ' . $name . ' not found.');
        }
        if (!$fresh && isset($this->typesInstances[$name])) {
            return $this->typesInstances[$name];
        }
        $class = $this->types[$name];
        $type = $this->objectType($class, [
            'name' => $name,
        ]);
        $this->typesInstances[$name] = $type;

        return $type;
    }

    /**
     * @param       $type
     * @param array $opts
     *
     * @return \GraphQL\Type\Definition\ObjectType|mixed|null
     */
    public function objectType($type, $opts = [])
    {
        $objectType = null;
        if ($type instanceof ObjectType) {
            $objectType = $type;
            foreach ($opts as $key => $value) {
                if (property_exists($objectType, $key)) {
                    $objectType->{$key} = $value;
                }
                if (isset($objectType->config[$key])) {
                    $objectType->config[$key] = $value;
                }
            }
        } else if (is_array($type)) {
            $objectType = $this->buildObjectTypeFromFields($type, $opts);
        } else {
            $objectType = $this->buildObjectTypeFromClass($type, $opts);
        }

        return $objectType;
    }

    /**
     * @param       $type
     * @param array $opts
     *
     * @return mixed
     */
    protected function buildObjectTypeFromClass($type, $opts = [])
    {
        if (!is_object($type)) {
            $type = $this->container->make($type);
        }
        foreach ($opts as $key => $value) {
            $type->{$key} = $value;
        }

        return $type->toType();
    }

    /**
     * @param       $fields
     * @param array $opts
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    protected function buildObjectTypeFromFields($fields, $opts = [])
    {
        $typeFields = [];
        foreach ($fields as $name => $field) {
            if (is_string($field)) {
                $field = $this->container->make($field);
                $name = is_numeric($name) ? $field->name : $name;
                $field->name = $name;
                $field = $field->toArray();
            } else {
                $name = is_numeric($name) ? $field['name'] : $name;
                $field['name'] = $name;
            }
            $typeFields[$name] = $field;
        }

        return new ObjectType(array_merge([
            'fields' => $typeFields,
        ], $opts));
    }

    /**
     * @param       $query
     * @param array $variables
     * @param array $opts
     *
     * @return array
     */
    public function query($query, $variables = [], $opts = [])
    {
        $result = $this->queryAndReturnResult($query, $variables, $opts);
        if (!empty($result->errors)) {
            $errorFormatter = config('graphql.error_formatter', [self::class, 'formatError']);

            return [
                'data'   => $result->data,
                'errors' => array_map($errorFormatter, $result->errors),
            ];
        } else {
            return [
                'data' => $result->data,
            ];
        }
    }

    /**
     * @param       $query
     * @param array $variables
     * @param array $opts
     *
     * @return \GraphQL\Executor\ExecutionResult|\GraphQL\Executor\Promise\Promise
     */
    public function queryAndReturnResult($query, $variables = [], $opts = [])
    {
        $root = array_get($opts, 'root', null);
        $context = array_get($opts, 'context', null);
        $schemaName = array_get($opts, 'schema', null);
        $operationName = array_get($opts, 'operationName', null);
        $schema = $this->schema($schemaName);
        $result = GraphQLBase::executeQuery($schema, $query, $root, $context, $variables, $operationName);

        return $result;
    }

    public function addTypes($types)
    {
        foreach ($types as $name => $type) {
            $this->addType($type, is_numeric($name) ? null : $name);
        }
    }

    public function addType($class, $name = null)
    {
        $name = $this->getTypeName($class, $name);
        $this->types[$name] = $class;
    }

    public function addSchema($name, $schema)
    {
        $this->schemas[$name] = $schema;
    }

    public function clearType($name)
    {
        if (isset($this->types[$name])) {
            unset($this->types[$name]);
        }
    }

    public function clearSchema($name)
    {
        if (isset($this->schemas[$name])) {
            unset($this->schemas[$name]);
        }
    }

    public function clearTypes()
    {
        $this->types = [];
    }

    public function clearSchemas()
    {
        $this->schemas = [];
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * @param      $class
     * @param null $name
     *
     * @return null
     */
    protected function getTypeName($class, $name = null)
    {
        if ($name) {
            return $name;
        }
        $type = is_object($class) ? $class : $this->container->make($class);

        return $type->name;
    }

    /**
     * @param \GraphQL\Error\Error $e
     *
     * @return array
     */
    public static function formatError(Error $e)
    {
        $error = [
            'message' => $e->getMessage(),
        ];
        $locations = $e->getLocations();
        if (!empty($locations)) {
            $error['locations'] = array_map(function ($loc) {
                return $loc->toArray();
            }, $locations);
        }
        $previous = $e->getPrevious();
        if ($previous && $previous instanceof ValidationError) {
            $error['validation'] = $previous->getValidatorMessages();
        }

        return $error;
    }
}
