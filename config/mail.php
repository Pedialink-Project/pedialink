<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Default Mailer Connection
    |--------------------------------------------------------------------------
    */
    'default' => env('MAIL_DRIVER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer from config
    |--------------------------------------------------------------------------
    */
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS') ?: 'no-reply@example.com',
        'name'    => env('MAIL_FROM_NAME') ?: 'Example App',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMTP Settings
    |--------------------------------------------------------------------------
    */
    'smtp' => [
        'host'       => env('SMTP_HOST', 'sandbox.smtp.mailtrap.io'),
        'port'       => (int) (env('SMTP_PORT', 587)),
        'username'   => env('SMTP_USER', 'fc402512f1b937'),
        'password'   => env('SMTP_PASS', '****1b8f'),
        // 'tls' uses STARTTLS on port 587; 'ssl' uses direct SSL on port 465
        'encryption' => env('SMTP_ENCRYPTION', 'tls'),
        'timeout'    => (int) (env('SMTP_TIMEOUT', 30)),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log path
    |--------------------------------------------------------------------------
    */
    'log' => [
        'path' => env('MAIL_LOG_PATH', __DIR__ . '/../storage/logs/mail.log'),
    ],

    'template' => [
        'path' => __DIR__ . '/../resources/emails',
    ],
];
