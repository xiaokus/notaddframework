<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-24 14:35
 */
namespace Notadd\Foundation\Setting\GraphQL\Queries;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Query;

/**
 * Class SettingQuery.
 */
class SettingsQuery extends Query
{
    /**
     * @return array
     */
    public function args()
    {
        return [
            'key' => [
                'name' => 'key',
                'type' => Type::string(),
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     *
     * @return array|\stdClass
     */
    public function resolve($root, $args)
    {
        if (isset($args['key'])) {
            return [
                [
                    'key'   => $args['key'],
                    'value' => $this->setting->get($args['key']),
                ],
            ];
        }
        $settings = $this->setting->all()->map(function ($value, $key) {
            return ['key' => $key, 'value' => $value];
        })->values();

        return $settings->toArray();
    }

    /**
     * @return \GraphQL\Type\Definition\ListOfType
     */
    public function type(): ListOfType
    {
        return Type::listOf($this->graphql->type('settings'));
    }
}
