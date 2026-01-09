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

        // Should I redirect user to home if authenticated user tries to access guest pages?
        // CS 28 members: Please suggest a better option (if you have any..)
        return redirect(route('home'));
    }
}