<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:47
 */
namespace Notadd\Foundation\GraphQL\Types;

use GraphQL\Type\Definition\EnumType as EnumObjectType;
use Notadd\Foundation\GraphQL\Abstracts\Type;

/**
 * Class EnumType.
 */
class EnumType extends Type
{
    public function values()
    {
        return [];
    }

    public function getValues()
    {
        $values = $this->values();
        $attributesValues = array_get($this->attributes, 'values', []);
        return sizeof($attributesValues) ? $attributesValues : $values;
    }

    /**
     * Get the attributes from the container.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();

        $values = $this->getValues();
        if (isset($values)) {
            $attributes['values'] = $values;
        }

        return $attributes;
    }

    public function toType()
    {
        return new EnumObjectType($this->toArray());
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
        return 'enum';
    }
}
