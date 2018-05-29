<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-18 16:09
 */
namespace Notadd\Foundation\Attachment\Controllers;

use Notadd\Foundation\Attachment\Handlers\CdnSetHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class CdnController.
 */
class CdnController extends Controller
{
    /**
     * Api handler.
     *
     * @param \Notadd\Foundation\Attachment\Handlers\CdnSetHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse
     * @throws \Exception
     */
    public function handle(CdnSetHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
