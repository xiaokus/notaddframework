<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-23 19:59
 */
namespace Notadd\Foundation\Module\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Type as AbstractType;

/**
 * Class ModuleType.
 */
class ModuleType extends AbstractType
{
    /**
     * @return array
     */
    public function fields()
    {
        return [
            'authors'        => [
                'description' => '',
                'type'        => Type::string(),
            ],
            'description'    => [
                'description' => '',
                'type'        => Type::string(),
            ],
            'enabled'        => [
                'description' => '',
                'type'        => Type::boolean(),
            ],
            'identification' => [
                'description' => '',
                'type'        => Type::string(),
            ],
            'name'           => [
                'description' => '',
                'type'        => Type::string(),
            ],
            'version'        => [
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
        return 'module';
    }
}
