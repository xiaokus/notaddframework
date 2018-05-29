<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-17 11:44
 */
namespace Notadd\Foundation\JWTAuth\Contracts\Providers;

/**
 * Interface Auth.
 */
interface Auth
{
    /**
     * Check a user's credentials.
     *
     * @param array  $credentials
     *
     * @return mixed
     */
    public function byCredentials(array $credentials);

    /**
     * Authenticate a user via the id.
     *
     * @param mixed  $id
     *
     * @return mixed
     */
    public function byId($id);

    /**
     * Get the currently authenticated user.
     *
     * @return mixed
     */
    public function user();
}
