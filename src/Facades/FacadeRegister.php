<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-01-15 13:29
 */
namespace Notadd\Foundation\Facades;

use Illuminate\Container\Container;
use Notadd\Foundation\AliasLoader;

/**
 * Class FacadeRegister.
 */
class FacadeRegister
{
    /**
     * @var \Notadd\Foundation\AliasLoader
     */
    protected $aliasLoader;

    /**
     * FacadeRegister constructor.
     *
     * @param \Notadd\Foundation\AliasLoader $aliasLoader
     *
     * @internal param \Illuminate\Container\Container|\Illuminate\Contracts\Foundation\Application $container
     */
    public function __construct(AliasLoader $aliasLoader)
    {
        $this->aliasLoader = $aliasLoader;
    }

    /**
     * @param $key
     * @param $path
     */
    public function register($key, $path) {
        $this->aliasLoader->alias($path, $key);
    }
}
