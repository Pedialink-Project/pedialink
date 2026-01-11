<?php

namespace App\Middleware;

use Library\Framework\Core\Middleware;
use Library\Framework\Http\Request;

class VerifiedMiddleware implements Middleware
{
    public function handle(Request $request, callable $next, array $params)
    {
        $user = auth()->user();
        $proceed = false;
        $route = route('email.unverified');

        if ($user && $user->email_verified) {
            if ($user->isParent()) {
                $parent = $user->getRole();

                if ($parent && $parent->verified) {
                    $proceed = true;
                } else {
                    $route = route('parent.unverified');
                    
                }
            } else {
                $proceed = true;
            }
        }

        if ($proceed) {
            return $next($request, $params);
        }

        return redirect($route);
    }
}