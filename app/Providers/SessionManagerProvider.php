<?php

namespace App\Providers;

use Library\Framework\Core\Application;
use Library\Framework\Core\Provider;
use Library\Framework\Session\SessionManager;

/**
 * Session Manager Provider
 * 
 * Register and boot base session handler.
 */
class SessionManagerProvider extends Provider
{
    /**
     * @var Application
     */
    protected Application $app;

    /**
     * @param \Library\Framework\Core\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register base session handler
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SessionManager::class, fn() => new SessionManager());
        // $this->app->bind('session', SessionManager::class);
    }

    public function boot()
    {
        
    }
}