<?php

use App\Auth\Auth;
use Library\Framework\Core\Application;
use Library\Framework\Core\Env;
use Library\Framework\Http\RedirectResponse;
use Library\Framework\Http\Response;
use Library\Framework\Logger\Logger;
use Library\Framework\Mail\Mailer;
use Library\Framework\Routing\Router;
use Library\Framework\Session\SessionManager;
use Library\Framework\Storage\Storage;
use Library\Framework\View\View;
use Library\Framework\Http\Request;


function app($abstract = null)
{
    $app = Application::getInstance();
    return $abstract ? $app->make($abstract) : $app;
}

function env($key, $default = null)
{
    return app(Env::class)->get($key, $default);
}

function config($key)
{
    $config = app('config');
    [$file, $subkey] = explode('.', $key);
    return $config[$file][$subkey] ?? null;
}

function redirect(string $url, int $status = 302): RedirectResponse
{
    return new RedirectResponse($url);
}

function asset(string $path)
{
    if (!str_starts_with($path, '/')) {
        $path = '/' . $path;
    }

    return $path;
}

function route(string|null $name = null, array $params = [], array $query = [], array $defaults = [])
{
    if ($name === null) {
        return app(Router::class);
    }

    return app(Router::class)->url($name, $params, $query, $defaults);
}

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

function auth(): Auth
{
    return app(Auth::class);
}


function session(): SessionManager
{
    return app(SessionManager::class);
}

function old(string $key, $default = null)
{
    $old = session()->getFlash('_old_input', []);
    if (!is_array($old)) {
        return $default;
    }
    return $old[$key] ?? $default;
}

function errors(string $key = null)
{
    $s = session();
    $errs = $s->getFlash('_errors', []);
    if ($key === null) return $errs;
    return $errs[$key] ?? null;
}

function flash(string $key, $default = null)
{
    return session()->getFlash($key, $default);
}

function request($key = null, $default = null)
{
    $req = app(Request::class);

    if ($key === null) {
        return $req; 
    }

    return $req->input($key, $default);
}

function storage(): Storage
{
    $storage = app(Storage::class);

    return $storage;
}

function logger(): Logger
{
    return app(Logger::class);
}

function log_activity(string $message, array $context = [], string $level = 'info'): bool
{
    try {
        return logger()->log($level, $message, $context);
    } catch (\Throwable) {
        return false;
    }
}

function mailer(): Mailer
{
    $mailer = app(Mailer::class);
    return $mailer;
}

function display_entity_id(string $entity, $id, int $padLength = 3): string
{
    $prefixMap = [
        'appointment' => 'AP',
        'event' => 'EV',
        'child' => 'C',
        'maternal' => 'M',
        'parent' => 'P',
        'phm' => 'PHM',
        'publichealthmidwife' => 'PHM',
        'doctor' => 'D',
        'record' => 'REC',
        'vaccination' => 'V',
        'vaccinationschedule' => 'VS',
        'user' => 'U',
        'childhealthrecord' => 'CHR',
        'maternalhealthrecord' => 'MHR',

    ];

    $prefix = $prefixMap[strtolower($entity)] ?? strtoupper($entity);
    $number = max((int) $id, 0);

    return sprintf('%s-%0' . $padLength . 'd', $prefix, $number);
}

  function formatAmPmToTime(?string $time): ?string
{
    if (!$time) {
        return null;
    }

    $dateTime = \DateTime::createFromFormat('g:i A', $time);

    if (!$dateTime) {
        return null; 
    }

    return $dateTime->format('H:i');
}