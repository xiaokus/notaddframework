<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 15:24
 */
namespace Notadd\Foundation\Routing\Abstracts;

use Illuminate\Routing\Controller as IlluminateController;
use Notadd\Foundation\Routing\Traits\Helpers;
use Notadd\Foundation\Routing\Traits\Permissionable;
use Notadd\Foundation\Routing\Traits\Viewable;
use Notadd\Foundation\Validation\ValidatesRequests;

/**
 * Class Controller.
 */
abstract class Controller extends IlluminateController
{
    use Helpers, Permissionable, ValidatesRequests, Viewable;

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        if ($this->permissions) {
            foreach ($this->permissions as $permission => $methods) {
                $this->middleware('permission:' . $permission)->only($methods);
            }
        }
    }

    /**
     * Get a command from console instance.
     *
     * @param string $name
     *
     * @return \Notadd\Foundation\Console\Abstracts\Command|\Symfony\Component\Console\Command\Command
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCommand($name)
    {
        return $this->getConsole()->get($name);
    }
}
