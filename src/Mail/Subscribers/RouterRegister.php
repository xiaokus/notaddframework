<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-18 18:08
 */
namespace Notadd\Foundation\Mail\Subscribers;

use Notadd\Foundation\Mail\Controllers\MailController;
use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;

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
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/mail'], function () {
            $this->router->post('get', MailController::class . '@get');
            $this->router->post('set', MailController::class . '@set');
            $this->router->post('test', MailController::class . '@test');
        });
    }
}
