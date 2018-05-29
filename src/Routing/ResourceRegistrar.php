<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-25 10:41
 */
namespace Notadd\Foundation\Routing;

use Illuminate\Routing\ResourceRegistrar as IlluminateResourceRegistrar;

/**
 * Class ResourceRegistrar.
 */
class ResourceRegistrar extends IlluminateResourceRegistrar
{
    /**
     * Get the action array for a resource route.
     *
     * @param  string  $resource
     * @param  string  $controller
     * @param  string  $method
     * @param  array   $options
     * @return array
     */
    protected function getResourceAction($resource, $controller, $method, $options)
    {
        $name = $this->getResourceRouteName($resource, $method, $options);

        isset($options['methods'][$method]) && $method = $options['methods'][$method];

        $action = ['as' => $name, 'uses' => $controller.'@'.$method];

        if (isset($options['middleware'])) {
            $action['middleware'] = $options['middleware'];
        }

        return $action;
    }
}
