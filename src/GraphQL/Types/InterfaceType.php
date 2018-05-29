<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:51
 */
namespace Notadd\Foundation\GraphQL\Types;

use GraphQL\Type\Definition\InterfaceType as BaseInterfaceType;
use Notadd\Foundation\GraphQL\Abstracts\Type;

/**
 * Class InterfaceType.
 */
class InterfaceType extends Type
{
    /**
     * @return \Closure|null
     */
    protected function getTypeResolver()
    {
        if (!method_exists($this, 'resolveType')) {
            return null;
        }
        $resolver = [$this, 'resolveType'];

        return function () use ($resolver) {
            $args = func_get_args();

            return call_user_func_array($resolver, $args);
        };
    }

    /**
     * Get the attributes from the container.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $resolver = $this->getTypeResolver();
        if (isset($resolver)) {
            $attributes['resolveType'] = $resolver;
        }

        return $attributes;
    }

    /**
     * @return \GraphQL\Type\Definition\InterfaceType
     */
    public function toType()
    {
        return new BaseInterfaceType($this->toArray());
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'interface';
    }
}
