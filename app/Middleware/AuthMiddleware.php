<?php

namespace App\Middleware;

use Library\Framework\Http\Request;

/**
 * Middleware for authenticated pages
 */
class AuthMiddleware
{
    public function handle(Request $request, callable $next, array $params)
    {
        if (auth()->check()) {
            return $next($request, $params);
        }

        return view('error/404'); // temporary fallback for errors (Need to implement proper Error class!)
    }
}