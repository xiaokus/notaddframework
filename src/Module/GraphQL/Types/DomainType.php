<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-11-05 17:09
 */
namespace Notadd\Foundation\Module\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Type as AbstractType;

/**
 * Class DomainType.
 */
class DomainType extends AbstractType
{
    /**
     * @return array
     */
    public function fields()
    {
        return [
            'alias'          => [
                'description' => '',
                'type'        => Type::string(),
            ],
            'default'        => [
                'description' => '',
                'type'        => Type::boolean(),
            ],
            'enabled'        => [
                'description' => '',
                'type'        => Type::boolean(),
            ],
            'host'           => [
                'description' => '',
                'type'        => Type::string(),
            ],
            'identification' => [
                'description' => '',
                'type'        => Type::string(),
            ],
            'name'           => [
                'description' => '',
                'type'        => Type::string(),
            ],
        ];
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'moduleDomain';
    }
}
