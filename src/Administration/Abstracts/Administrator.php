<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-25 17:50
 */
namespace Notadd\Foundation\Administration\Abstracts;

use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;
use InvalidArgumentException;

/**
 * Class Administrator.
 */
abstract class Administrator
{
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Administrator constructor.
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Illuminate\Routing\Router    $router
     */
    public function __construct(Dispatcher $events, Router $router)
    {
        $this->events = $events;
        $this->router = $router;
    }

    /**
     * Get administration handler.
     *
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Administration route path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Init administrator.
     *
     * @throws \InvalidArgumentException
     */
    final public function init()
    {
        if (is_null($this->path) || is_null($this->handler)) {
            throw new InvalidArgumentException('Handler or Path must be Setted!');
        }
        $this->router->group(['middleware' => 'web'], function () {
            $this->router->get($this->path, $this->handler);
        });
    }

    /**
     * Register administration handler.
     *
     * @param $handler
     */
    public function registerHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Register administration route path.
     *
     * @param string $path
     */
    public function registerPath($path)
    {
        $this->path = $path;
    }
}
