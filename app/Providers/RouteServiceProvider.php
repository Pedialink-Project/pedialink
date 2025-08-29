<?php

namespace App\Providers;

use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\DoctorMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\ParentMiddleware;
use App\Middleware\PublicHealthMidwifeMiddleware;
use App\Middleware\StartSessionMiddleware;
use Library\Framework\Core\Application;
use Library\Framework\Core\Provider;
use Library\Framework\Routing\Router;

/**
 * Route Service Provider
 * 
 * Registers the router which handles
 * routing for the web application and loads
 * all the routes for the web app.
 * 
 * NOTE: To register middlewares, include them
 * in the setup method. Only middlewares registered here will
 * be available!
 */
class RouteServiceProvider extends Provider
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
     * Register all middlewares and global middlewares.
     * @param \Library\Framework\Routing\Router $router Base router class
     * @param array $middlewares All middlewares that needs to be registered
     * @param array $globalMiddlewares
     * @return void
     */
    private function setup(Router $router, array $middlewares = [], array $globalMiddlewares = [])
    {
        foreach ($middlewares as $name => $class) {
            $router->registerMiddleware($name, $class);
        }

        foreach ($globalMiddlewares as $name) {
            $router->globalMiddleware($name);
        }
    }
    
    /**
     * Register base router class
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Router::class, function () {
            return new Router($this->app);
        });

        // $this->app->bind('router', Router::class);
    }

    /**
     * Register middleware and load web routes
     * @return void
     */
    public function boot()
    {
        /**
         * @var Router
         */
        $router = $this->app->make(Router::class);

        // register middlewares
        $this->setup($router, [
            'session' => StartSessionMiddleware::class,
            'auth' => AuthMiddleware::class,
            'guest' => GuestMiddleware::class,
            'parent' => ParentMiddleware::class,
            'phm' => PublicHealthMidwifeMiddleware::class,
            'doctor' => DoctorMiddleware::class,
            'admin' => AdminMiddleware::class,
        ], [
            'session',
        ]);

        $routes = require __DIR__ . '/../../routes/web.php'; // import all web routes

        // Boot all web routes
        foreach ($routes as $route) {
            list($method, $uri, $action, $name, $middleware) = array_pad($route, 5, null);
            
            // reset middleware to empty array if no optional
            // middlewares are available for this route
            if (!isset($route[4])) {
                $middleware = [];
            }

            $router->addRoute($method, $uri, $action, $name, $middleware);
        }
    }
}