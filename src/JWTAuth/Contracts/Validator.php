<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:43
 */
namespace Notadd\Foundation\JWTAuth\Contracts;

/**
 * Interface Validator.
 */
interface Validator
{
    /**
     * Perform some checks on the value.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function check($value);

    /**
     * Helper function to return a boolean.
     *
     * @param array $value
     *
     * @return bool
     */
    public function isValid($value);
}
