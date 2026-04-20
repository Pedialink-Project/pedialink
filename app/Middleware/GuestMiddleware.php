<?php

namespace App\Middleware;

use Library\Framework\Core\Middleware;
use Library\Framework\Http\Request;

/**
 * Middleware for guest pages
 */
class GuestMiddleware implements Middleware
{
    public function handle(Request $request, callable $next, array $params)
    {
        if (!auth()->check()) {
            return $next($request, $params);
        }

        return redirect(route('home'));
    }
}