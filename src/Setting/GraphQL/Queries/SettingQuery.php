<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-31 17:46
 */
namespace Notadd\Foundation\Setting\GraphQL\Queries;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Query;

/**
 * Class SettingQuery.
 */
class SettingQuery extends Query
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
     * @return mixed
     */
    public function resolve($root, $args)
    {
        if (isset($args['key'])) {
            return is_array($this->setting->get($args['key'])) ? $this->setting->get($args['key']) : [
                $this->setting->get($args['key']),
            ];
        }

        return [null];
    }

    /**
     * @return \GraphQL\Type\Definition\ListOfType
     */
    public function type(): ListOfType
    {
        return Type::listOf(Type::string());
    }
}
