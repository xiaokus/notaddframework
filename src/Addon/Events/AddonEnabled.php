<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-03 14:25
 */
namespace Notadd\Foundation\Addon\Events;

use Notadd\Foundation\Addon\Addon;

/**
 * Class ExtensionEnabled.
 */
class AddonEnabled
{
    /**
     * @var \Notadd\Foundation\Addon\AddonManager
     */
    protected $manager;

    /**
     * ExtensionEnabled constructor.
     *
     * @param \Notadd\Foundation\Addon\Addon $extension
     *
     * @internal param \Illuminate\Container\Container|\Illuminate\Contracts\Foundation\Application|\Notadd\Foundation\Application $container
     * @internal param \Notadd\Foundation\Addon\ExtensionManager $manager
     */
    public function __construct(Addon $extension)
    {
        $this->extension = $extension;
    }
}
