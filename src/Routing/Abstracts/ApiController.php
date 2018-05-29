<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-23 14:07
 */
namespace Notadd\Foundation\Routing\Abstracts;

/**
 * Class ApiController.
 */
abstract class ApiController extends Controller
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Send something for handler.
     *
     * @param $handler
     */
    public function send($handler)
    {
    }
}
