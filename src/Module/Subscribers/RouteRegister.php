<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-18 19:02
 */
namespace Notadd\Foundation\Module\Subscribers;

use Notadd\Foundation\Module\Controllers\ModuleController;
use Notadd\Foundation\Module\Controllers\ModulesController;
use Notadd\Foundation\Module\Controllers\ModulesDomainsController;
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
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/administration'], function () {
            $this->router->resource('modules/domains', ModulesDomainsController::class)->methods([
                'store' => 'update',
            ])->names([
                'store' => 'domains.update',
            ])->only([
                'store',
            ]);
            $this->router->resource('modules', ModulesController::class)->methods([
                'destroy' => 'uninstall',
                'index'   => 'list',
                'store'   => 'install',
                'update'  => 'enable',
            ])->names([
                'destroy' => 'modules.uninstall',
                'index'   => 'modules.list',
                'store'   => 'modules.install',
                'update'  => 'modules.enable',
            ])->only([
                'destroy',
                'index',
                'store',
                'update',
            ]);
        });
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api'], function () {
            $this->router->post('module/exports', ModuleController::class . '@exports');
            $this->router->post('module/imports', ModuleController::class . '@imports');
            $this->router->post('module/update', ModuleController::class . '@update');
        });
    }
}
