<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:51
 */
namespace Notadd\Foundation\JWTAuth\Validators;

use Notadd\Foundation\JWTAuth\Support\RefreshFlow;
use Notadd\Foundation\JWTAuth\Exceptions\JWTException;
use Notadd\Foundation\JWTAuth\Contracts\Validator as ValidatorContract;

/**
 * Class Validator.
 */
abstract class Validator implements ValidatorContract
{
    use RefreshFlow;

    /**
     * Helper function to return a boolean.
     *
     * @param array  $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        try {
            $this->check($value);
        } catch (JWTException $e) {
            return false;
        }

        return true;
    }

    /**
     * Run the validation.
     *
     * @param array  $value
     *
     * @return void
     */
    abstract public function check($value);
}
