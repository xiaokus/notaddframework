<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-04 10:48
 */
namespace Notadd\Foundation\SearchEngine;

use Illuminate\Events\Dispatcher;
use Notadd\Foundation\Http\Abstracts\ServiceProvider;
use Notadd\Foundation\SearchEngine\Subscribers\PermissionGroupRegister;
use Notadd\Foundation\SearchEngine\Subscribers\PermissionRegister;
use Notadd\Foundation\SearchEngine\Subscribers\RouterRegister;

/**
 * Class SearchEngineServiceProvider.
 */
class SearchEngineServiceProvider extends ServiceProvider
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
        return ['searchengine.optimization'];
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('searchengine.optimization', function () {
            return new Optimization();
        });
    }
}
