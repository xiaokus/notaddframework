<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 14:57
 */
namespace Notadd\Foundation\Image;

use Notadd\Foundation\Http\Abstracts\ServiceProvider;

/**
 * Class ImageServiceProvider.
 */
class ImageServiceProvider extends ServiceProvider
{
    /**
     * Determines if Intervention Imagecache is installed
     *
     * @return bool
     */
    private function cacheIsInstalled()
    {
        return class_exists('Notadd\\Image\\ImageCache');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->cacheIsInstalled() ? $this->bootstrapImageCache() : null;
    }

    /**
     * Bootstrap imagecache
     *
     * @return void
     */
    private function bootstrapImageCache()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('images', function () {
            return new ImageManager($this->app['config']->get('image'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'image',
        ];
    }
}
