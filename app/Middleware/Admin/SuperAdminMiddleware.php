<?php

namespace App\Middleware\Admin;

use App\Middleware\AdminMiddleware;
use App\Models\Admin;
use Library\Framework\Http\Request;

class SuperAdminMiddleware extends AdminMiddleware
{
    public function handle(Request $request, callable $next, array $params)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $adminType = Admin::find(auth()->user()->id)?->getAdminType();

            // allow only super admin access
            if ($adminType === "super") {
                return $next($request, $params);
            }
        }

        return view('error/404'); // temporary fallback for errors (Need to implement proper Error class!)
    }
}