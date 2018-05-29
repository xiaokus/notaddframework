<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-09-09 23:50
 */
namespace Notadd\Foundation\Cache\Controllers;

use Notadd\Foundation\Cache\Queues\FlushAll;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class RedisController.
 */
class RedisController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle()
    {
        FlushAll::dispatch();

        return $this->response->json([
            'message' => '清除缓存成功！',
        ]);
    }
}
