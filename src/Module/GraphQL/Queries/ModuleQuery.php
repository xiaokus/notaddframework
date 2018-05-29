<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-19 18:04
 */
namespace Notadd\Foundation\Module\GraphQL\Queries;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Query;
use Notadd\Foundation\Module\Module;

/**
 * Class ConfigurationQuery.
 */
class ModuleQuery extends Query
{
    /**
     * @return array
     */
    public function args()
    {
        return [
            'enabled'   => [
                'defaultValue' => null,
                'name'         => 'enabled',
                'type'         => Type::boolean(),
            ],
            'installed' => [
                'defaultValue' => null,
                'name'         => 'installed',
                'type'         => Type::boolean(),
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     *
     * @return array
     */
    public function resolve($root, $args)
    {
        if ($args['enabled'] === true) {
            $collection = $this->module->enabled();
        } else if ($args['installed'] === true) {
            $collection = $this->module->installed();
        } else if ($args['installed'] === false) {
            $collection = $this->module->notInstalled();
        } else {
            $collection = $this->module->repository();
        }

        return $collection->map(function (Module $module) {
            $authors = (array)$module->get('authors');
            foreach ($authors as $key => $author) {
                if (isset($author['name']) && isset($author['email'])) {
                    $authors[$key] = $author['name'] . ' <' . $author['email'] . '>';
                } else {
                    unset($authors[$key]);
                }
            }
            $module->offsetSet('authors', implode(',', $authors));

            return $module;
        })->filter(function (Module $module) {
            return $module->identification() !== 'notadd/administration';
        })->toArray();
    }

    /**
     * @return \GraphQL\Type\Definition\ListOfType
     */
    public function type(): ListOfType
    {
        return Type::listOf($this->graphql->type('module'));
    }
}
