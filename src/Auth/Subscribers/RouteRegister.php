<?php

// +----------------------------------------------------------------------+
// | The Notadd Framework.                                                |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016-2017 Shanxi Benchu Network Technology Co,.Ltd     |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the Apache license,    |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.apache.org/licenses/LICENSE-2.0.html                      |
// +----------------------------------------------------------------------+
// | Author: TwilRoad <heshudong@ibenchu.com>                             |
// |         Seven Du <shiweidu@outlook.com>                              |
// +----------------------------------------------------------------------+

namespace Notadd\Foundation\Auth\Subscribers;

use Notadd\Foundation\Routing\Router;
use Notadd\Foundation\Auth\Controllers\AuthController;
use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;

class RouteRegister extends AbstractRouteRegister
{
    /**
     * The subscribers handle, Register routes.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        $this->router->group(['middleware' => 'web'], function (Router $route) {

            // Logout route.
            $route->get(
                $this->container->bound('auth.logout') ? $this->container->make('auth.logout') : 'logout',
                $this->container->bound('auth.logout.resolver') ? $this->container->make('auth.logout.resolver') : AuthController::class.'@logout'
            );

            // Login resource route.
            // @TwilRoad The why resource route? - @medz
            $route->resource(
                $this->container->bound('auth.login') ? $this->container->make('auth.login') : 'login',

                // @TwilRoad The why is `auth.logout.resolver` ? I think the is error. - @medz
                $this->container->bound('auth.logout.resolver') ? $this->container->make('auth.logout.resolver') : AuthController::class
            );
        });
    }
}
