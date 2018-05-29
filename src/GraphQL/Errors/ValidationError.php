<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:43
 */
namespace Notadd\Foundation\GraphQL\Errors;

use GraphQL\Error\Error;

/**
 * Class ValidationError.
 */
class ValidationError extends Error
{
    public $validator;

    /**
     * @param $validator
     *
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function getValidatorMessages()
    {
        return $this->validator ? $this->validator->messages() : [];
    }
}
