<?php

namespace App\Controllers;

use App\Models\User;
use Library\Framework\Http\Request;
use Library\Framework\Http\Response;

class AuthController
{
    public function parentRegister(Request $request)
    {
        return view('auth/parent-register');
    }

    public function parentLogin(Request $request)
    {
        return view('auth/parent-login');
    }

    public function staffLogin(Request $request)
    {
        return view('auth/staff-login');
    }

    public function forgotPassword(Request $request)
    {
        return view('auth/forgot-password');
    }
}