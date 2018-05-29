<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 10:58
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Http\Events\ProviderLoaded;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class BootProviders.
 */
class LoadProviders implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        $this->container->registerConfiguredProviders();
        $this->container->boot();
        $this->container->make('events')->dispatch(new ProviderLoaded());
    }
}
