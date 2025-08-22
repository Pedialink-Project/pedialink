<?php

namespace Library\Framework\Routing;

use Library\Framework\Core\Application;
use Library\Framework\Http\Request;
use Library\Framework\Http\Response;

/**
 * Base Router Class
 * 
 * Handles routing in the web application
 * including middleware registration and
 * execution.
 */
class Router
{
   /** @var array<string, array<string, array{action: callable|string, middleware: array}>> */
    protected array $routes = [];

    protected array $routeNames = [];

     /** @var array<string, string> Named middleware registry */
    protected array $middlewareMap = [];

    /** @var string[] Global middleware keys applied to every route */
    protected array $globalMiddleware = [];

    public function __construct(protected Application $app) {}

    /**
     * Register available middlewares
     * @param string $key Middleware name
     * @param string $middlewareClass Middleware class
     * @return void
     */
    public function registerMiddleware(string $key, string $middlewareClass)
    {
        $this->middlewareMap[$key] = $middlewareClass;
    }

    /**
     * Register globally active middlewares
     * @param string $key Middleware name
     * @return void
     */
    public function globalMiddleware(string $key)
    {
        $this->globalMiddleware[] = $key;
    }

    /**
     * Register a new route with optional middleware
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri URI pattern
     * @param callable|string $action Controller@method or Closure
     * @param array $middleware List of middleware keys
     */
    public function addRoute(string $method, string $uri, $action, string $name = null, array $middleware = []): void
    {
        $method = strtoupper($method);

        $this->routes[$method][$uri] = [
            'action' => $action,
            'name' => $name,
            'middleware' => array_merge($this->globalMiddleware, $middleware)
        ];

        if ($name) {
            $this->routeNames[$name] = ['method' => $method, 'uri' => $uri];
        }
    }

    /**
     * Dispatch an incoming request to the appropriate route
     * @param Request $request Request object
     * @return Response|null Returns response object or null
     */
    public function dispatch(Request $request): Response|null
    {
        $method = strtoupper($request->method);
        $uri    = $request->uri;

         foreach (($this->routes[$method] ?? []) as $routeUri => $route) {
            // Convert route pattern to regex, capture named segments
            $pattern = preg_replace(
                '/\{(.+?)\}/', 
                '(?P<$1>[^/]+)', 
                $routeUri
            );

            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                // Extract parameters by name
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }
                return $this->runPipeline($route, $request, $params);
            }
        }

        return new Response('404 Not Found', 404);
    }

    protected function runPipeline(array $route, Request $request, array $params)
    {
        $action = $route['action'];
        $middlewareKeys = $route['middleware'];

        // Build the middleware pipeline ending in the action
        $pipeline = array_reduce(
            array_reverse($middlewareKeys),
            function ($next, $key) {
                return function ($request, $params) use ($next, $key) {
                    $middlewareClass = $this->middlewareMap[$key] ?? null;
                    if (!$middlewareClass) {
                        throw new \Exception("Middleware key '{$key}' not registered");
                    }
                    $middleware = $this->app->make($middlewareClass);
                    return $middleware->handle($request, $next, $params);
                };
            },
            // Final handler invokes controller or closure
            function ($request, $params) use ($action) {
                if (is_array($action) && count($action) === 2 && is_string($action[0])) {
                    // e.g. [$className, 'method']
                    list($class, $method) = $action;
                    $instance = new $class;
                    return $instance->$method($request, ...array_values($params));
                }

                throw new \Exception("Invalid route action for URI; expected callable array or Closure");

            }
        );

        // Execute pipeline
        return $pipeline($request, $params);
    }

    public function url(string $name, array $params = [], array $query = [], array $defaults = []): string
    {
        if (!isset($this->routeNames[$name])) {
            throw new \Exception("Route [{$name}] not defined.");
        }
        $uri = $this->routeNames[$name]['uri'];
        // Merge defaults
        $params = array_merge($defaults, $params);
        // Replace placeholders
        foreach ($params as $key => $value) {
            $uri = preg_replace(
                '/\{' . $key . '\}/', 
                (string)$value, 
                $uri
            );
        }
        // Append any unmatched params as query string
        $queryString = http_build_query($query);
        return $uri . ($queryString ? '?' . $queryString : '');
    }

    public function current(string $routeName)
    {
        if (isset($this->routeNames[$routeName])) {
            $currentUrl = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            if ($currentUrl === $this->routeNames[$routeName]['uri']) {
                return true;
            }
        }

        return false;
    }
}