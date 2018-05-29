<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 14:10
 */
namespace Notadd\Foundation\Routing\Events;

use Illuminate\Routing\Router;

/**
 * Class RouteRegister.
 */
class RouteRegister
{
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * RouteRegister constructor.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @internal param \Illuminate\Container\Container|\Illuminate\Contracts\Foundation\Application $container
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
}
