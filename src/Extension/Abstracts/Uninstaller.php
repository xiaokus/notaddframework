<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-10-05 21:55
 */
namespace Notadd\Foundation\Extension\Abstracts;

/**
 * Class Uninstaller.
 */
abstract class Uninstaller
{
    /**
     * @return true
     */
    abstract public function handle();
}