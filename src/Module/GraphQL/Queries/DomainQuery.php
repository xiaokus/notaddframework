<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-11-05 17:13
 */
namespace Notadd\Foundation\Module\GraphQL\Queries;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Notadd\Foundation\GraphQL\Abstracts\Query;
use Notadd\Foundation\Module\Module;

/**
 * Class DomainQuery.
 */
class DomainQuery extends Query
{
    /**
     * @param $root
     * @param $args
     *
     * @return mixed
     */
    public function resolve($root, $args)
    {
        $domains = $this->module->enabled()->map(function (Module $module) {
            $data = [];
            $alias = 'module.' . $module->identification() . '.domain.alias';
            $enabled = 'module.' . $module->identification() . '.domain.enabled';
            $host = 'module.' . $module->identification() . '.domain.host';
            $data['alias'] = $this->setting->get($alias, '');
            $data['default'] = $this->setting->get('module.default', 'notadd/notadd') == $module->identification();
            $data['enabled'] = boolval($this->setting->get($enabled, 0));
            $data['host'] = $this->setting->get($host, '');
            $data['identification'] = $module->identification();
            $data['name'] = $module->offsetGet('name');

            return $data;
        });
        $domains->offsetUnset('notadd/administration');
        $domains->prepend([
            'alias'          => $this->setting->get('module.notadd/administration.domain.alias', ''),
            'default'        => $this->setting->get('module.default', 'notadd/notadd') == 'notadd/administration',
            'enabled'        => boolval($this->setting->get('module.notadd/administration.domain.enabled', 0)),
            'host'           => $this->setting->get('module.notadd/administration.domain.host', ''),
            'identification' => 'notadd/administration',
            'name'           => 'Notadd 后台',
        ], 'notadd/administration');
        $domains->prepend([
            'alias'          => $this->setting->get('module.notadd/api.domain.alias', ''),
            'default'        => $this->setting->get('module.default', 'notadd/notadd') == 'notadd/api',
            'enabled'        => boolval($this->setting->get('module.notadd/api.domain.enabled', 0)),
            'host'           => $this->setting->get('module.notadd/api.domain.host', ''),
            'identification' => 'notadd/api',
            'name'           => 'Notadd API',
        ], 'notadd/api');
        $domains->prepend([
            'alias'          => '/',
            'default'        => $this->setting->get('module.default', 'notadd/notadd') == 'notadd/notadd',
            'enabled'        => boolval($this->setting->get('module.notadd/notadd.domain.enabled', 0)),
            'host'           => $this->setting->get('module.notadd/notadd.domain.host', ''),
            'identification' => 'notadd/notadd',
            'name'           => 'Notadd',
        ], 'notadd/notadd');

        return $domains->toArray();
    }

    /**
     * @return \GraphQL\Type\Definition\ListOfType
     */
    public function type(): ListOfType
    {
        return Type::listOf($this->graphql->type('moduleDomain'));
    }
}