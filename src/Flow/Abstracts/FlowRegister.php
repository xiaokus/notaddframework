<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-31 14:30
 */
namespace Notadd\Foundation\Flow\Abstracts;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Notadd\Foundation\Event\Abstracts\EventSubscriber;
use Notadd\Foundation\Flow\Events\FlowRegister as FlowRegisterEvent;
use Notadd\Foundation\Flow\FlowManager;

/**
 * Class FlowRegister.
 */
abstract class FlowRegister extends EventSubscriber
{
    /**
     * @var \Notadd\Foundation\Flow\FlowManager
     */
    protected $flow;

    /**
     * FlowRegister constructor.
     *
     * @param \Illuminate\Container\Container     $container
     * @param \Illuminate\Events\Dispatcher       $events
     * @param \Notadd\Foundation\Flow\FlowManager $flow
     */
    public function __construct(Container $container, Dispatcher $events, FlowManager $flow)
    {
        parent::__construct($container, $events);
        $this->flow = $flow;
    }

    /**
     * Name of event.
     *
     * @throws \Exception
     * @return string|object
     */
    protected function getEvent()
    {
        return FlowRegisterEvent::class;
    }

    /**
     * Register flow or flows.
     */
    abstract public function handle();
}
