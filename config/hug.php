<?php

return [
    /*
     | The notification class to use for hugs.
     | https://laravel.com/docs/notifications
     */
    'notification' => \Astrotomic\Hug\Laravel\Hug::class,

    /*
     | The channels the notification should be send on.
     */
    'via' => [
        'mail',
        'nexmo',
        'slack',
    ],

    /*
     * The queue connection to use for the notification.
     */
    'connection' => null,

    /*
     | The queue name to use for the notification.
     */
    'queue' => null,
];
