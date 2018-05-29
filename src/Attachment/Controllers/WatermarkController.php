<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-18 15:40
 */
namespace Notadd\Foundation\Attachment\Controllers;

use Notadd\Foundation\Attachment\Handlers\WatermarkSetHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class WatermarkController.
 */
class WatermarkController extends Controller
{
    /**
     * Api handler.
     *
     * @param \Notadd\Foundation\Attachment\Handlers\WatermarkSetHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse
     * @throws \Exception
     */
    public function handle(WatermarkSetHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
