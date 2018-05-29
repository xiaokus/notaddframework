<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 13:24
 */
namespace Notadd\Foundation\Administration;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;

/**
 * Class AdministrationServiceProvider.
 */
class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return array
     */
    public function provides()
    {
        return ['administration'];
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('administration', function () {
            return new AdministrationManager();
        });
    }
}
