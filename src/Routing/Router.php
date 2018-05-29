<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-25 10:53
 */
namespace Notadd\Foundation\Routing;

use Illuminate\Routing\Router as IlluminateRouter;

/**
 * Class Router.
 */
class Router extends IlluminateRouter
{
    /**
     * Route a resource to a controller.
     *
     * @param string $name
     * @param string $controller
     * @param array  $options
     *
     * @return \Notadd\Foundation\Routing\PendingResourceRegistration
     */
    public function resource($name, $controller, array $options = [])
    {
        $registrar = new ResourceRegistrar($this);

        return new PendingResourceRegistration(
            $registrar, $name, $controller, $options
        );
    }
}