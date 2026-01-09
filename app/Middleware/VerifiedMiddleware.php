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
        $failView = "auth/email-unverified";
        $viewParams = ["blocked" => false];

        if ($user && $user->email_verified) {
            if ($user->isParent()) {
                $parent = $user->getRole();

                if ($parent && $parent->verified) {
                    $proceed = true;
                } else {
                    $failView = "auth/parent-unverified";
                    $submitted = $parent?->birth_certificate &&
                        $parent?->marriage_certificate &&
                        $parent?->nic_copy;
                    $viewParams = ["submitted" => $submitted];
                }
            } else {
                $proceed = true;
            }
        }

        if ($proceed) {
            return $next($request, $params);
        }

        return view($failView, $viewParams);
    }
}