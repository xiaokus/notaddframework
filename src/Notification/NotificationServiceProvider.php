<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-22 13:08
 */
namespace Notadd\Foundation\Notification;

use Illuminate\Notifications\NotificationServiceProvider as IlluminateNotificationServiceProvider;
use Notadd\Foundation\Notification\Types\CommonType;

/**
 * Class NotificationServiceProvider.
 */
class NotificationServiceProvider extends IlluminateNotificationServiceProvider
{
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $app;

    /**
     * Boot the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'notifications');
        $this->app->make(NotificationTypeManager::class)->type('common', new CommonType([]));
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        parent::register();
        $this->app->singleton('notification.type', function () {
            return new NotificationTypeManager();
        });
    }
}
