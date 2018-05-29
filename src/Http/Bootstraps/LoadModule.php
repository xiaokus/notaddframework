<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-30 20:19
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Module\Module;
use Notadd\Foundation\Module\ModuleLoaded;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class LoadModule.
 */
class LoadModule implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        if ($this->container->isInstalled()) {
            $this->module->enabled()->each(function (Module $module) {
                $this->module->registerExcept($module->get('csrf', []));
                collect($module->get('events', []))->each(function ($data, $key) {
                    switch ($key) {
                        case 'subscribes':
                            collect($data)->each(function ($subscriber) {
                                if (class_exists($subscriber)) {
                                    $this->events->subscribe($subscriber);
                                }
                            });
                            break;
                    }
                });
                $schemas = $module->get('graphql.schemas', []);
                foreach ($schemas as $key => $value) {
                    if (isset($value['query']) && is_array($value['query']) && count($value['query']) > 0) {
                        $key = 'graphql.schemas.' . $key . '.query';
                        $queries = collect($this->config->get($key, []));
                        if ($queries->isEmpty()) {
                            $queries = collect($value['query']);
                        } else {
                            $queries = $queries->merge((array)$value['query']);
                        }
                        $this->config->set($key, $queries->toArray());
                    }
                    if (isset($value['mutation']) && is_array($value['mutation']) && count($value['mutation']) > 0) {
                        $key = 'graphql.schemas.' . $key . '.mutation';
                        $mutations = collect($this->config->get($key, []));
                        if ($mutations->isEmpty()) {
                            $mutations = collect($value['query']);
                        } else {
                            $mutations = $mutations->merge((array)$value['query']);
                        }
                        $this->config->set($key, $mutations->toArray());
                    }
                }
                $types = collect($this->config->get('graphql.types'));
                $types = $types->merge($module->get('graphql.types', []));
                $this->config->set('graphql.types', $types->toArray());
                $providers = collect($this->config->get('app.providers'));
                $providers->push($module->service());
                collect($module->get('providers', []))->each(function ($provider) use ($providers) {
                    $providers->push($provider);
                });
                $this->config->set('app.providers', $providers->toArray());
            });
        }
        $this->event->dispatch(new ModuleLoaded());
    }
}
