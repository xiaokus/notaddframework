<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime      2016-10-24 10:07
 */
namespace Notadd\Foundation\Setting;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;
use Notadd\Foundation\Module\Module;

/**
 * Class SettingServiceProvider.
 */
class SettingServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return array
     */
    public function provides()
    {
        return ['setting'];
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('setting', function () {
            $database = new DatabaseSettingsRepository($this->app->make('db.connection'));
            $cached = new MemoryCacheSettingsRepository($database);
            if ($this->app->isInstalled()) {
                $this->app->make('module')->repository()->each(function (Module $module) use ($cached) {
                    if ($module->offsetExists('settings')) {
                        foreach ((array)$module->get('settings') as $key => $definition) {
                            $cached->registerFormat($key, $definition);
                        }
                    }
                });
            }

            return $cached;
        });
    }
}
