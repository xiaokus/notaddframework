<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:51
 */
namespace Notadd\Foundation\JWTAuth\Claims;

use Notadd\Foundation\JWTAuth\Exceptions\TokenExpiredException;

/**
 * Class Expiration.
 */
class Expiration extends Claim
{
    use DatetimeTrait;

    /**
     * @var string
     */
    protected $name = 'exp';

    /**
     * @throws \Notadd\Foundation\JWTAuth\Exceptions\TokenExpiredException
     */
    public function validatePayload()
    {
        if ($this->isPast($this->getValue())) {
            throw new TokenExpiredException('Token has expired');
        }
    }
}
