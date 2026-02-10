<?php

namespace App\Middleware\Admin;

use App\Middleware\AdminMiddleware;
use App\Models\Admin;
use Library\Framework\Http\Request;

class DataAdminMiddleware extends AdminMiddleware
{
    public function handle(Request $request, callable $next, array $params)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $adminType = Admin::find(auth()->user()->id)
                ->getAdminType();

            // allow bypass for super admin while primary access
            // is for data admin
            if ($adminType === "super" || $adminType === "data") {
                return $next($request, $params);
            }
        }

        return view('error/404'); // temporary fallback for errors (Need to implement proper Error class!)
    }
}