<?php

return [
    'default' => env('BROADCAST_DRIVER', 'log'),

    'connections' => [
        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

        'reverb' => [
            'driver' => 'reverb',
            'host' => env('REVERB_SERVER_HOST', '127.0.0.1'),
            'port' => env('REVERB_SERVER_PORT', 8080),
            'key' => env('REVERB_SERVER_KEY', 'local'),
            'secret' => env('REVERB_SERVER_SECRET', ''),
            'scheme' => env('REVERB_SERVER_SCHEME', 'http'),
            'ssl' => [
                'verify' => env('REVERB_SSL_VERIFY', false),
            ],
        ],
    ],
];
