<?php

namespace App\Providers;

use App\Auth\Auth;
use Library\Framework\Core\Application;
use Library\Framework\Core\Provider;
use Library\Framework\Session\SessionManager;

class AuthServiceProvider extends Provider
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $this->app->singleton(Auth::class, function (Application $app) {
            return new Auth($app->make(SessionManager::class));
        });

        $this->app->bind('auth', Auth::class);
    }

    public function boot()
    {

    }
}