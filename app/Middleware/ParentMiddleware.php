<?php

namespace App\Middleware;

use Library\Framework\Core\Middleware;
use Library\Framework\Http\Request;

/**
 * Middleware for authenticated pages
 * that is authorized only for parent accounts
 */
class ParentMiddleware implements Middleware
{
    public function handle(Request $request, callable $next, array $params)
    {
        if (auth()->check() && auth()->user()->isParent()) {
            return $next($request, $params);
        }

        return view('error/404'); // temporary fallback for errors (Need to implement proper Error class!)
    }
}