<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-18 20:14
 */
namespace Notadd\Foundation\Notification\Types;

use Notadd\Foundation\Notification\Abstracts\NotificationType;

/**
 * Class CommonType.
 */
class CommonType extends NotificationType
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
        ];
    }
}
