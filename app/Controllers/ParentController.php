<?php

namespace App\Controllers;

use Library\Framework\Http\Request;

class ParentController
{
    public function dashboard(Request $request)
    {
        return view("parent/dashboard");
    }
}