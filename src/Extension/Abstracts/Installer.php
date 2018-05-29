<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-10-05 21:51
 */
namespace Notadd\Foundation\Extension\Abstracts;

/**
 * Class Installer.
 */
abstract class Installer
{
    /**
     * @return bool
     */
    abstract public function handle();
}
