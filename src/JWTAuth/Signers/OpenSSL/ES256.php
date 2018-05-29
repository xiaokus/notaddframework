<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-16 16:22
 */
namespace Notadd\Foundation\JWTAuth\Signers\OpenSSL;

use Namshi\JOSE\Signer\OpenSSL\ES256 as NamshiES256;

/**
 * Class ES256.
 */
class ES256 extends NamshiES256
{
    /**
     * ES256 constructor.
     */
    public function __construct()
    {
    }
}
