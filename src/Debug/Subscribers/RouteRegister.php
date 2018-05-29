<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-03-10 14:12
 */
namespace Notadd\Foundation\Debug\Subscribers;

use Notadd\Foundation\Debug\Controllers\ConfigurationsController;
use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;

/**
 * Class RouteRegister.
 */
class RouteRegister extends AbstractRouteRegister
{
    /**
     * Handle Route Register.
     */
    public function handle()
    {
        $this->router->group([
            'middleware' => ['auth:api', 'cross', 'web'],
            'prefix'     => 'api/administration/debug',
        ], function () {
            $this->router->resource('configurations', ConfigurationsController::class)->methods([
                'index' => 'list',
            ])->names([
                'index' => 'debug.configurations.list',
                'store' => 'debug.configurations.store',
            ])->only([
                'index',
                'store',
            ]);
        });
    }
}
