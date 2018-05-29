<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-18 18:53
 */
namespace Notadd\Foundation\SearchEngine\Subscribers;

use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;
use Notadd\Foundation\SearchEngine\Controllers\SeoController;

/**
 * Class RouterRegister.
 */
class RouterRegister extends AbstractRouteRegister
{
    /**
     * Handle Route Register.
     */
    public function handle()
    {
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/administration/seo'], function () {
            $this->router->post('/', SeoController::class . '@seo');
            $this->router->post('batch', SeoController::class . '@batch');
            $this->router->post('create', SeoController::class . '@create');
            $this->router->post('edit', SeoController::class . '@edit');
            $this->router->post('list', SeoController::class . '@list');
            $this->router->post('order', SeoController::class . '@order');
            $this->router->post('module', SeoController::class . '@module');
            $this->router->post('remove', SeoController::class . '@remove');
            $this->router->post('template', SeoController::class . '@template');
        });
    }
}
