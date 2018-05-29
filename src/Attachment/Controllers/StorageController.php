<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-17 19:22
 */
namespace Notadd\Foundation\Attachment\Controllers;

use Notadd\Foundation\Attachment\Handlers\StorageSetHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class StorageController.
 */
class StorageController extends Controller
{
    /**
     * Api handler.
     *
     * @param \Notadd\Foundation\Attachment\Handlers\StorageSetHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse
     * @throws \Exception
     */
    public function handle(StorageSetHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
