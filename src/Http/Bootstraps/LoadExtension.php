<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-22 18:18
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Notadd\Foundation\Extension\Extension;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class LoadExtension.
 */
class LoadExtension implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        $this->container->isInstalled() && $this->extension->repository()->each(function (Extension $extension) {
            $providers = collect($this->config->get('app.providers'));
            $providers->push($extension->service());
            $this->config->set('app.providers', $providers->toArray());
        });
    }
}
