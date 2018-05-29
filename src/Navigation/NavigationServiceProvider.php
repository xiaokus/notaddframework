<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-16 17:03
 */
namespace Notadd\Foundation\Navigation;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;
use Notadd\Foundation\Navigation\Models\Item;
use Notadd\Foundation\Navigation\Observers\ItemObserver;

/**
 * Class NavigationServiceProvider.
 */
class NavigationServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider.
     */
    public function boot()
    {
        Item::observe(ItemObserver::class);
    }
}
