<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:45
 */
namespace Notadd\Foundation\JWTAuth\Contracts\Providers;

/**
 * Interface JWT.
 */
interface JWT
{
    /**
     * @param array  $payload
     *
     * @return string
     */
    public function encode(array $payload);

    /**
     * @param string  $token
     *
     * @return array
     */
    public function decode($token);
}
