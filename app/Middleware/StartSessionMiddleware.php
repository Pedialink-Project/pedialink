<?php

namespace App\Middleware;

use Library\Framework\Core\Middleware;
use Library\Framework\Http\Request;
use Library\Framework\Session\SessionManager;

/**
 * Middleware to start sessions for a route
 */
class StartSessionMiddleware implements Middleware
{
    protected SessionManager $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function handle(Request $request, callable $next, array $params)
    {
        $this->sessionManager->start();
        return $next($request, $params);
    }
}