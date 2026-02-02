<?php

namespace Library\Framework\Core;

use Library\Framework\Http\Request;

interface Middleware
{
    public function handle(Request $request, callable $next, array $params);
}