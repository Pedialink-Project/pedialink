<?php

use App\Auth\Auth;
use Library\Framework\Core\Application;
use Library\Framework\Core\Env;
use Library\Framework\Http\RedirectResponse;
use Library\Framework\Http\Response;
use Library\Framework\Routing\Router;
use Library\Framework\Session\SessionManager;
use Library\Framework\View\View;
use Library\Framework\Http\Request;

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
 * @return RedirectResponse
 */
function redirect(string $url, int $status = 302): RedirectResponse
{
    return new RedirectResponse($url);
}

/**
 * Global helper to correctly parse the path to files in public/ folder
 * @param string $path
 * @return string
 */
function asset(string $path)
{
    if (!str_starts_with($path, '/')) {
        $path = '/' . $path;
    }

    return $path;
}

/**
 * Global helper to access route urls from named routes.
 * @param string|null $name Name of the route
 * @param array $params Route parameters
 * @param array $query Query string parameters
 * @param array $defaults Default values
 */
function route(string|null $name = null, array $params = [], array $query = [], array $defaults = [])
{
    if ($name === null) {
        return app(Router::class);
    }

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

/**
 * GLobal helper to get the auth instance
 * @return App\Auth\Auth
 */
function auth(): Auth
{
    return app(Auth::class);
}

/**
 * Global helper class to retrieve session class
 * @return Library\Framework\Session\SessionManager
 */
function session(): SessionManager
{
    return app(SessionManager::class);
}

/**
 * Retrieve old input data from the previous request.
 * 
 * NOTE: Returns null when no matching key is found so make sure to
 * check before parsing to view php directives!
 * 
 * @param string $key
 * @param mixed $default
 */
function old(string $key, $default = null)
{
    $old = session()->getFlash('_old_input', []);
    if (!is_array($old)) {
        return $default;
    }
    return $old[$key] ?? $default;
}

/**
 * Retrieves errors (if any is found) for the validation errors
 * that occured in your previous request.
 * 
 * NOTE: Returns null when no matching key is found so make sure to
 * check before parsing to view php directives!
 * 
 * @param string $key
 */
function errors(string $key = null)
{
    $s = session();
    $errs = $s->getFlash('_errors', []);
    if ($key === null) return $errs;
    return $errs[$key] ?? null;
}

/**
 * Get the flash content sent for the next request.
 * 
 * @param string $key
 * @param mixed $default default is null
 */
function flash(string $key, $default = null)
{
    return session()->getFlash($key, $default);
}

/**
 * Global helper to access the current HTTP request or retrieve input values.
 *
 * If $key is null the Request instance is returned; otherwise the input value
 * for the given key is returned (or $default if not present).
 *
 * @param string|null $key
 * @param mixed $default
 * @return mixed
 */
function request($key = null, $default = null)
{
    $req = app(Request::class);

    if ($key === null) {
        return $req; 
    }

    return $req->input($key, $default);
}