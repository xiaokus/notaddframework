<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-17 20:27
 */
namespace Notadd\Foundation\Notification\Abstracts;

use Illuminate\Notifications\Notification;

/**
 * Class NotificationType.
 */
abstract class NotificationType extends Notification
{
    /**
     * @var array
     */
    protected $attributes;

    /**
     * NotificationType constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    abstract public function via($notifiable);
}
