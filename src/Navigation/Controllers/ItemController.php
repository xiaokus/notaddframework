<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-16 17:47
 */
namespace Notadd\Foundation\Navigation\Controllers;

use Notadd\Foundation\Navigation\Handlers\Item\CreateHandler;
use Notadd\Foundation\Navigation\Handlers\Item\DeleteHandler;
use Notadd\Foundation\Navigation\Handlers\Item\EditHandler;
use Notadd\Foundation\Navigation\Handlers\Item\FetchHandler;
use Notadd\Foundation\Navigation\Handlers\Item\SortHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class ItemController.
 */
class ItemController extends Controller
{
    /**
     * Create handler.
     *
     * @param \Notadd\Foundation\Navigation\Handlers\Item\CreateHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function create(CreateHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Delete handler.
     *
     * @param \Notadd\Foundation\Navigation\Handlers\Item\DeleteHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function delete(DeleteHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Edit handler.
     *
     * @param \Notadd\Foundation\Navigation\Handlers\Item\EditHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function edit(EditHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Fetch handler.
     *
     * @param \Notadd\Foundation\Navigation\Handlers\Item\FetchHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function fetch(FetchHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Sort handler.
     *
     * @param \Notadd\Foundation\Navigation\Handlers\Item\SortHandler $handler
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function sort(SortHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
