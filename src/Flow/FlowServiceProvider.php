<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-29 16:17
 */
namespace Notadd\Foundation\Flow;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;

/**
 * Class FlowServiceProvider.
 */
class FlowServiceProvider extends ServiceProvider
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
        return ['flow'];
    }

    /**
     * Register service to provider.
     */
    public function register()
    {
        $this->app->singleton('flow', function () {
            return new FlowManager();
        });
    }
}
