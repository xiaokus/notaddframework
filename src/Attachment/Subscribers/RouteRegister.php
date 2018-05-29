<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime      2016-11-02 15:55
 */
namespace Notadd\Foundation\Attachment\Subscribers;

use Notadd\Foundation\Attachment\Controllers\CdnController;
use Notadd\Foundation\Attachment\Controllers\ConfigurationsController;
use Notadd\Foundation\Attachment\Controllers\StorageController;
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
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/attachment'], function () {
            $this->router->post('cdn', CdnController::class . '@handle');
            $this->router->post('storage', StorageController::class . '@handle');
        });
        $this->router->group([
            'middleware' => ['auth:api', 'cross', 'web'],
            'prefix'     => 'api/administration/attachment',
        ], function () {
            $this->router->resource('configurations', ConfigurationsController::class)->methods([
                'index' => 'list',
            ])->only([
                'index',
                'store',
            ]);
        });
    }
}
