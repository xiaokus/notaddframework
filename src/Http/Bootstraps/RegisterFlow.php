<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-31 14:32
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Notadd\Foundation\Flow\Events\FlowRegister;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class RegisterFlow.
 */
class RegisterFlow implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        $this->event->dispatch(new FlowRegister($this->container['flow']));
    }
}
