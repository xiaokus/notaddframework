<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-19 15:44
 */
namespace Notadd\Foundation\GraphQL\Subscribers;

use Notadd\Foundation\Routing\Router;
use Notadd\Foundation\GraphQL\Controllers\GraphQLController;
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
        $this->router->group(['middleware' => [/*'auth:api', */'cross', 'web'], 'prefix' => 'api/administration'], function (Router $route) {
            $route->post('/', GraphQLController::class.'@query');
        });
    }
}
