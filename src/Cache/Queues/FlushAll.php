<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 18:07
 */
namespace Notadd\Foundation\Cache\Queues;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notadd\Foundation\Bus\Dispatchable;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class FlushAll.
 */
class FlushAll implements ShouldQueue
{
    use Dispatchable, Helpers, InteractsWithQueue, Queueable;

    /**
     * Handle Queue.
     */
    public function handle()
    {
        $this->cache->tags('notadd')->flush();
    }
}
