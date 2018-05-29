<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-14 12:46
 */
namespace Notadd\Foundation\Extension;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;

/**
 * Class ExpandServiceProvider.
 */
class ExtensionServiceProvider extends ServiceProvider
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
        return ['extension'];
    }

    /**
     * Register service provider.
     */
    public function register()
    {
        $this->app->singleton('extension', function ($app) {
            return new ExtensionManager();
        });
    }
}
