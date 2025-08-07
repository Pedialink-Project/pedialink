<?php

use Library\Framework\Core\Application;
use Library\Framework\Core\Env;
use Library\Framework\Http\Response;
use Library\Framework\Routing\Router;
use Library\Framework\View\View;

/**
 * Global helper to retrieve application instance
 * 
 * NOTE: Use this helper to access application instance anywhere in the code. 
 * I do not recommend using this helper if you can express clear dependency
 * among objects by including Application $app in the constructor!
 * 
 * @param mixed $abstract Binded key, singleton instance key. Default is null.
 * @return mixed Returns application instance, stored singletons or instantiates binded objects
 */
function app($abstract = null)
{
    $app = Application::getInstance();
    return $abstract ? $app->make($abstract) : $app;
}

/**
 * Global helper to retrieve environment values
 * 
 * NOTE: Use this helper only inside config files and
 * bootstrap logic only. Do not use it anywhere else. Use config
 * global helper instead.
 * 
 * @param mixed $key
 * @param mixed|null $default
 */
function env($key, $default = null)
{
    return app(Env::class)->get($key, $default);
}

/**
 * Global helper to retrieve configuration values
 * 
 * NOTE: Use this helper to access config file entries.
 * 
 * @param mixed $key Config key
 * @return mixed Returns config value or null.
 */
function config($key)
{
    $config = app('config');
    [$file, $subkey] = explode('.', $key);
    return $config[$file][$subkey] ?? null;
}

/**
 * Global helper that wraps the static redirect method
 * of Response class
 * @param string $url Url to redirect to
 * @param int $status Status code for redirection. Default is 302
 * @return Response
 */
function redirect(string $url, int $status = 302): Response
{
    return Response::redirect($url, $status);
}

/**
 * Global helper to access route urls from named routes.
 * @param string $name Name of the route
 * @param array $params Route parameters
 * @param array $query Query string parameters
 * @param array $defaults Default values
 */
function route(string $name, array $params = [], array $query = [], array $defaults = [])
{
    return app(Router::class)->url($name, $params, $query, $defaults);
}

/**
 * Global helper to render the compiled output of view files
 * @param string $template
 * @param array $data
 * @param bool $htmlOnly
 * @return Response|string|null
 */
function view(string $template, array $data = [], bool $htmlOnly = false)
{
    /**
     * @var string|null
     */
    $html = app(View::class)->make('pages/' . $template, $data);

    if ($htmlOnly) {
        return $html;
    }

    return new Response($html);
}