<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:44
 */
namespace Notadd\Foundation\JWTAuth\Contracts;

/**
 * Interface Claim.
 */
interface Claim
{
    /**
     * Set the claim value, and call a validate method.
     *
     * @param mixed $value
     *
     * @throws \Notadd\Foundation\JWTAuth\Exceptions\InvalidClaimException
     *
     * @return $this
     */
    public function setValue($value);

    /**
     * Get the claim value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set the claim name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Get the claim name.
     *
     * @return string
     */
    public function getName();

    /**
     * Validate the Claim value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function validateCreate($value);
}
