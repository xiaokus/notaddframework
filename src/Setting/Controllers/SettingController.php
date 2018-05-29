<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-08 17:01
 */
namespace Notadd\Foundation\Setting\Controllers;

use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Foundation\Setting\Handlers\AllHandler;
use Notadd\Foundation\Setting\Handlers\HandlerHandler;

/**
 * Class SettingController.
 */
class SettingController extends Controller
{
    /**
     * @var array
     */
    protected $permissions = [
        'global::global::global::setting.set' => 'set',
    ];

    /**
     * All handler.
     *
     * @param \Notadd\Foundation\Setting\Handlers\AllHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse
     * @throws \Exception
     */
    public function all(AllHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Set handler.
     *
     * @param \Notadd\Foundation\Setting\Handlers\HandlerHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse
     * @throws \Exception
     */
    public function handler(HandlerHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
