<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-31 10:53
 */
namespace Notadd\Foundation\Http\Subscribers;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Notadd\Foundation\Event\Abstracts\EventSubscriber;

/**
 * Class CheckPublicPath.
 */
class CheckPublicPath extends EventSubscriber
{
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $request;

    /**
     * CheckPublicPath constructor.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher   $events
     * @param \Illuminate\Http\Request        $router
     */
    public function __construct(Container $container, Dispatcher $events, Request $router)
    {
        parent::__construct($container, $events);
        $this->request = $router;
    }

    /**
     * Name of event.
     *
     * @throws \Exception
     * @return string|object
     */
    protected function getEvent()
    {
        return RouteMatched::class;
    }

    public function handle()
    {
        if ($this->request->getBasePath() == '/public') {
            throw new \Exception('public目录 必须为网站根目录');
        }
    }
}
