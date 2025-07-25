<?php

namespace App\Providers;

use Library\Framework\Core\Application;
use Library\Framework\Core\Provider;
use Library\Framework\Database\Connection;

/**
 * Database Service Provider
 * 
 * Registers core database connections and
 * loads relavant entries
 * 
 * NOTE for cs 28 members: If you need to add
 * custom code after registering this service provider, then
 * add them inside boot method.
 */
class DatabaseServiceProvider extends Provider
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
     * Register database connections
     * @return void
     */
    public function register()
    {
        $config = config('database.connections.pgsql');

        $this->app->singleton(Connection::class, function () use ($config) {
            return new Connection($config);
        });
    }

    public function boot()
    {

    }
}