<?php

namespace App\Middleware;

use Library\Framework\Http\Request;
use Library\Framework\Session\SessionManager;

/**
 * Middleware to start sessions for a route
 */
class StartSessionMiddleware
{
    protected SessionManager $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function handle(Request $request, callable $next)
    {
        $this->sessionManager->start();
        return $next($request);
    }
}