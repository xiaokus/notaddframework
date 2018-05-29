<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:51
 */
namespace Notadd\Foundation\JWTAuth\Claims;

/**
 * Class JwtId.
 */
class JwtId extends Claim
{
    /**
     * @var string
     */
    protected $name = 'jti';
}
