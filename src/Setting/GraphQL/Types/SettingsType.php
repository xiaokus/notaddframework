<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-24 15:38
 */
namespace Notadd\Foundation\Setting\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Type as AbstractType;

/**
 * Class SettingType.
 */
class SettingsType extends AbstractType
{
    /**
     * @return array
     */
    public function fields()
    {
        return [
            'key'   => [
                'description' => 'The key of the setting',
                'type'        => Type::string(),
            ],
            'value' => [
                'description' => 'The value of the setting',
                'type'        => Type::string(),
            ],
        ];
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'settings';
    }
}
