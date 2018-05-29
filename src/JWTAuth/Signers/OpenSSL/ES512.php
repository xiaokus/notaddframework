<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-16 16:23
 */
namespace Notadd\Foundation\JWTAuth\Signers\OpenSSL;

use Namshi\JOSE\Signer\OpenSSL\ES512 as NamshiES512;

/**
 * Class ES512.
 */
class ES512 extends NamshiES512
{
    /**
     * ES512 constructor.
     */
    public function __construct()
    {
    }
}
