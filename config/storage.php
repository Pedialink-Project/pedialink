<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Storage Disk
    |--------------------------------------------------------------------------
    */
    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Storage private file url signing key
    |--------------------------------------------------------------------------
    */
    'secret' => env('APP_KEY', 'change_me_to_a_random_secret'),

    // define disks
    'disks' => [
        'local' => [
            'root' => realpath(__DIR__ . '/../storage/media'),
            'visibility' => 'private',
            'url_prefix' => null,
        ],
        'public' => [
            'root' => realpath(__DIR__ . '/../storage/media/public'),
            'visibility' => 'public',
            // url_prefix is the web-accessible prefix if you symlink storage/media/public -> public/media
            'url_prefix' => '/media/public',
        ],
        'private' => [
            'root' => realpath(__DIR__ . '/../storage/media/private'),
            'visibility' => 'private',
            'url_prefix' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Temporary lifetime of signed URL
    |--------------------------------------------------------------------------
    */
    'temp_lifetime' => 300,
];