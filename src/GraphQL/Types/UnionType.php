<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:52
 */
namespace Notadd\Foundation\GraphQL\Types;

use GraphQL\Type\Definition\UnionType as UnionObjectType;

/**
 * Class UnionType.
 */
class UnionType extends InterfaceType
{
    /**
     * @return array
     */
    public function types()
    {
        return [];
    }

    /**
     * @return array|mixed
     */
    public function getTypes()
    {
        $attributesTypes = array_get($this->attributes, 'types', []);

        return sizeof($attributesTypes) ? $attributesTypes : $this->types();
    }

    /**
     * Get the attributes from the container.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $types = $this->getTypes();
        if (isset($types)) {
            $attributes['types'] = $types;
        }

        return $attributes;
    }

    /**
     * @return \GraphQL\Type\Definition\UnionType
     */
    public function toType()
    {
        return new UnionObjectType($this->toArray());
    }
}
