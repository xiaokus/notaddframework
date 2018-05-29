<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-12 15:48
 */
namespace Notadd\Foundation\Module\Abstracts;

/**
 * Class Installation.
 */
abstract class Installation
{
    /**
     * Pre-handle for install.
     */
    abstract public function preInstall();

    /**
     * Pre-handle for uninstall.
     */
    abstract public function preUninstall();

    /**
     * Post-handle for install.
     */
    abstract public function postInstall();

    /**
     * Post-handle for uninstall
     */
    abstract public function postUninstall();
}
