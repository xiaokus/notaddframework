<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-30 20:24
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Notadd\Foundation\Addon\Events\AddonLoaded;
use Notadd\Foundation\Addon\Addon;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class LoadAddon.
 */
class LoadAddon implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        $this->addon->enabled()->each(function (Addon $addon) {
            $this->addon->registerExcept($addon->get('csrf', []));
            collect($addon->get('events', []))->each(function ($data, $key) {
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
            $this->container->register($addon->provider());
        });
        $this->event->dispatch(new AddonLoaded());
    }
}
