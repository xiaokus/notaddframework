<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 11:05
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Illuminate\Support\Facades\Facade;
use Notadd\Foundation\AliasLoader;
use Notadd\Foundation\Facades\FacadeRegister;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class RegisterFacades.
 */
class RegisterFacades implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->container);
        $aliasLoader = AliasLoader::getInstance($this->container->make('config')->get('app.aliases', []));
        $this->container->make('events')->dispatch(new FacadeRegister($aliasLoader));
        $aliasLoader->register();
    }
}
