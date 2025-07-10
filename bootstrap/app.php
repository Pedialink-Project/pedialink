<?php

use Library\Framework\Core\Application;
use Library\Framework\Core\Env;

// Start a new application container
$app = new Application();

// Set static instance for the app container
Application::setInstance($app);

// Import global helper file
require_once __DIR__ . '/../library/helpers/global.php';

// Load env values from .env file
$env = new Env(__DIR__ . '/../.env');
$app->singleton(Env::class, fn() => $env);

// Set up config values from files in config/ folder
$app->singleton('config', function() {
    return [
        'app' => require __DIR__ . '/../config/app.php',
        'database' => require __DIR__ . '/../config/database.php',
    ];
});

// Service providers to register
$providers = [
    App\Providers\RouteServiceProvider::class,
    App\Providers\DatabaseServiceProvider::class,
    App\Providers\SessionManagerProvider::class,
];

foreach ($providers as $providerClass)
{
    (new $providerClass($app))->register();
}

foreach ($providers as $providerClass)
{
    (new $providerClass($app))->boot();
}

return $app;