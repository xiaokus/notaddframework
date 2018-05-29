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
 * Interface Storage.
 */
interface Storage
{
    /**
     * @param string  $key
     * @param mixed  $value
     * @param int  $minutes
     *
     * @return void
     */
    public function add($key, $value, $minutes);

    /**
     * @param string  $key
     * @param mixed  $value
     *
     * @return void
     */
    public function forever($key, $value);

    /**
     * @param string  $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param string  $key
     *
     * @return bool
     */
    public function destroy($key);

    /**
     * @return void
     */
    public function flush();
}
