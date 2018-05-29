<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-25 17:41
 */
return [
    'default'     => env('BROADCAST_DRIVER', 'null'),
    'connections' => [
        'pusher' => [
            'driver'  => 'pusher',
            'key'     => env('PUSHER_APP_KEY'),
            'secret'  => env('PUSHER_APP_SECRET'),
            'app_id'  => env('PUSHER_APP_ID'),
            'options' => [
            ],
        ],
        'redis'  => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],
        'log'    => [
            'driver' => 'log',
        ],
        'null'   => [
            'driver' => 'null',
        ],
    ],
];
