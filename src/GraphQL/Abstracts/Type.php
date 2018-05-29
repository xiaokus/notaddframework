<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:47
 */
namespace Notadd\Foundation\GraphQL\Abstracts;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class Type.
 */
abstract class Type
{
    use Helpers {
        __get as HelperGet;
    }

    /**
     * @var array.
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @var bool
     */
    protected $inputObject = false;

    /**
     * @var bool
     */
    protected $enumObject = false;

    /**
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * @return array
     */
    abstract public function fields();

    /**
     * @return string
     */
    abstract public function name();

    /**
     * @return array
     */
    public function interfaces()
    {
        return [];
    }

    /**
     * @param $name
     * @param $field
     *
     * @return \Closure|null
     */
    protected function getFieldResolver($name, $field)
    {
        $resolveMethod = 'resolve' . studly_case($name) . 'Field';
        if (isset($field['resolve'])) {
            return $field['resolve'];
        } else if (method_exists($this, $resolveMethod)) {
            $resolver = [$this, $resolveMethod];

            return function () use ($resolver) {
                $args = func_get_args();

                return call_user_func_array($resolver, $args);
            };
        }

        return null;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        $fields = $this->fields();
        $allFields = [];
        foreach ($fields as $name => $field) {
            if (is_string($field)) {
                $field = app($field);
                $field->name = $name;
                $allFields[$name] = $field->toArray();
            } else {
                $resolver = $this->getFieldResolver($name, $field);
                if ($resolver) {
                    $field['resolve'] = $resolver;
                }
                $allFields[$name] = $field;
            }
        }

        return $allFields;
    }

    /**
     * Get the attributes from the container.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = $this->attributes();
        $interfaces = $this->interfaces();
        $attributes = array_merge($this->attributes, [
            'fields' => function () {
                return $this->getFields();
            },
        ], $attributes);
        if (sizeof($interfaces)) {
            $attributes['interfaces'] = $interfaces;
        }
        isset($attributes['name']) || $attributes['name'] = $this->name();

        return $attributes;
    }

    /**
     * Convert the Fluent instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getAttributes();
    }

    /**
     * @return \GraphQL\Type\Definition\EnumType|\GraphQL\Type\Definition\InputObjectType|\GraphQL\Type\Definition\ObjectType
     */
    public function toType()
    {
        if ($this->inputObject) {
            return new InputObjectType($this->toArray());
        }
        if ($this->enumObject) {
            return new EnumType($this->toArray());
        }

        return new ObjectType($this->toArray());
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        $attributes = $this->toArray();

        return isset($attributes[$key]) ? $attributes[$key] : $this->HelperGet($key);
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        $attributes = $this->getAttributes();

        return isset($attributes[$key]);
    }
}
