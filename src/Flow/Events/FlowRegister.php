<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-31 14:28
 */
namespace Notadd\Foundation\Flow\Events;

use Illuminate\Container\Container;
use Notadd\Foundation\Flow\FlowManager;

/**
 * Class FlowRegister.
 */
class FlowRegister
{
    /**
     * @var \Notadd\Foundation\Flow\FlowManager
     */
    protected $flow;

    /**
     * FlowRegister constructor.
     *
     * @param \Notadd\Foundation\Flow\FlowManager $flow
     */
    public function __construct(FlowManager $flow)
    {
        $this->flow = $flow;
    }
}
