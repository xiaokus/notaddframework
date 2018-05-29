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
use Notadd\Foundation\JWTAuth\Claims\Claim;

/**
 * Class InvalidClaimException.
 */
class InvalidClaimException extends JWTException
{
    /**
     * InvalidClaimException constructor.
     *
     * @param \Notadd\Foundation\JWTAuth\Claims\Claim $claim
     * @param int                                     $code
     * @param \Exception|null                         $previous
     */
    public function __construct(Claim $claim, $code = 0, Exception $previous = null)
    {
        parent::__construct('Invalid value provided for claim [' . $claim->getName() . ']', $code, $previous);
    }
}
