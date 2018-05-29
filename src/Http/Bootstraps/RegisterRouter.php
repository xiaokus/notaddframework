<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 14:08
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Routing\Events\RouteRegister;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class RegisterRouter.
 */
class RegisterRouter implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        if ($this->container->isInstalled()) {
            if ($this->container->routesAreCached()) {
                $this->container->booted(function () {
                    require $this->container->getCachedRoutesPath();
                });
            } else {
                $this->event->dispatch(new RouteRegister($this->container['router']));
                $this->container->booted(function () {
                    $this->container['router']->getRoutes()->refreshNameLookups();
                });
            }
        }
    }
}
