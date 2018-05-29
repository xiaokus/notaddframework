<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:47
 */
namespace Notadd\Foundation\JWTAuth\Exceptions;

use Exception;

/**
 * Class JWTException.
 */
class JWTException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'An error occurred';
}
