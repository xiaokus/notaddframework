<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:51
 */
namespace Notadd\Foundation\JWTAuth\Claims;

use Notadd\Foundation\JWTAuth\Exceptions\InvalidClaimException;
use Notadd\Foundation\JWTAuth\Exceptions\TokenInvalidException;

/**
 * Class NotBefore.
 */
class NotBefore extends Claim
{
    use DatetimeTrait;

    /**
     * @var string
     */
    protected $name = 'nbf';

    /**
     * @param mixed $value
     *
     * @return mixed
     * @throws \Notadd\Foundation\JWTAuth\Exceptions\InvalidClaimException
     */
    public function validateCreate($value)
    {
        if (!is_numeric($value) || $this->isFuture($value)) {
            throw new InvalidClaimException($this);
        }

        return $value;
    }

    /**
     * @throws \Notadd\Foundation\JWTAuth\Exceptions\TokenInvalidException
     */
    public function validatePayload()
    {
        if ($this->isFuture($this->getValue())) {
            throw new TokenInvalidException('Not Before (nbf) timestamp cannot be in the future');
        }
    }
}
