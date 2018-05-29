<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-16 16:23
 */
namespace Notadd\Foundation\JWTAuth\Signers\OpenSSL;

use Namshi\JOSE\Signer\OpenSSL\ES384 as NamshiES384;

/**
 * Class ES384.
 */
class ES384 extends NamshiES384
{
    /**
     * ES384 constructor.
     */
    public function __construct()
    {
    }
}
