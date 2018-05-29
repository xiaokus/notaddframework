<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-11-03 17:39
 */
namespace Notadd\Foundation\Extension\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Mutation;

/**
 * Class UninstallMutation.
 */
class UninstallMutation extends Mutation
{
    /**
     * @return array
     */
    public function args(): array
    {
        return [
            'identification' => [
                'name' => 'identification',
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
        // TODO: Implement resolve() method.
    }
}
