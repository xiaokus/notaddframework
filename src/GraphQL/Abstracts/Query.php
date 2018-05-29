<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:52
 */
namespace Notadd\Foundation\GraphQL\Abstracts;

use GraphQL\Type\Definition\ListOfType;
use Illuminate\Container\Container;
use Notadd\Foundation\GraphQL\Errors\AuthorizationError;
use Notadd\Foundation\GraphQL\GraphQLManager;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class Query.
 */
abstract class Query
{
    use Helpers {
        __get as HelperGet;
    }

    /**
     * @var array.
     */
    protected $attributes = [];

    /**
     * @var \Illuminate\Container\Container.
     */
    protected $container;

    /**
     * @var \Notadd\Foundation\GraphQL\GraphQLManager.
     */
    protected $graphql;

    /**
     * Query constructor.
     *
     * @param \Illuminate\Container\Container           $container
     * @param \Notadd\Foundation\GraphQL\GraphQLManager $graphql
     */
    public function __construct(Container $container, GraphQLManager $graphql)
    {
        $this->container = $container;
        $this->graphql = $graphql;
    }

    /**
     * @param $root
     * @param $args
     *
     * @return bool
     */
    public function authorize($root, $args)
    {
        return true;
    }

    /**
     * @return array
     */
    public function args()
    {
        return [];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * @param $root
     * @param $args
     *
     * @return mixed
     */
    abstract public function resolve($root, $args);

    /**
     * The Query to array.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function toArray(): array
    {
        $attributes = array_merge($this->attributes, ['args' => $this->args()], $this->attributes());
        $type = $this->type();
        if (isset($type)) {
            $attributes['type'] = $type;
        }
        $attributes['resolve'] = function (...$arguments) {
            if ($this->authorize(...$arguments) !== true) {
                throw new AuthorizationError('Unauthorized');
            }

            return $this->resolve(...$arguments);
        };
        $attributes['resolve']->bindTo($this);

        return $attributes;
    }

    /**
     * @return \GraphQL\Type\Definition\ListOfType
     */
    abstract public function type(): ListOfType;

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
}
