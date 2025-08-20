<?php

namespace App\Middleware;

use Library\Framework\Http\Request;

/**
 * Middleware for guest pages
 */
class GuestMiddleware
{
    public function handle(Request $request, callable $next, array $params)
    {
        if (!auth()->check()) {
            return $next($request, $params);
        }
    }
}