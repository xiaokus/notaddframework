<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-14 14:10
 */
namespace Notadd\Foundation\Routing\Traits;

/**
 * Trait Permissionable.
 */
trait Permissionable
{
    /**
     * Check for permission.
     *
     * @param $key
     *
     * @return bool
     */
    protected function permission($key)
    {
        return $this->container->make('permission')->check($key);
    }
}
