<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Library\Framework\Http\Request;
use Library\Framework\Http\Response;
use Library\Framework\Session\SessionManager;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function parentRegisterInitial(Request $request)
    {
        if ($registerFormSession = session()->get("register_form", null)) {
            $registerFormSession["passwordHash"] = null;
            session()->set("register_form", $registerFormSession);

            return view('auth/parent-register', [
                "data" => $registerFormSession,
            ]);
        }
        return view('auth/parent-register');
    }

    public function parentVerifyInitial(Request $request)
    {
        $data = [
            "firstName" => $request->input("firstName"),
            "lastName" => $request->input("lastName"),
            "email" => $request->input("email"),
        ];

        $errors = $this->authService->validateInitialForm(array_merge($data, [
            "password" => $request->input("password"),
            "confirmPassword" => $request->input("confirmPassword"),
        ]));

        if (count($errors) !== 0) {
            session()->set("register_form", array_merge($data, [
                "passwordHash" => password_hash(
                    $request->input("password"), PASSWORD_DEFAULT
                ),
            ]));
            return redirect(route("parent.register.final"));
        }

        return redirect(route("parent.register", [
            "data" => $data,
            "errors" => $errors,
        ]));
    }

    public function parentRegisterFinal(Request $request)
    {
        $registerSessionForm = session()->get("register_form", null);
        if ($registerSessionForm && $registerSessionForm["passwordHash"] !== null) {
            return view('auth/parent-register', [
                "final" => true,
            ]);
        }

        return redirect(route("parent.register"));
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