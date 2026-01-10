<?php

namespace App\Controllers;

use App\Services\Profile\NameService;
use App\Services\Profile\PasswordService;
use Library\Framework\Http\Request;

class SettingController
{
    private NameService $nameService;
    private PasswordService $passwordService;

    public function __construct()
    {
        $this->nameService = new NameService();
        $this->passwordService = new PasswordService();
    }

    public function index()
    {
        $user = auth()->user();
        return view("auth/settings", [
            "name" => $user->name,
            "email" => $user->email,
        ]);
    }

    public function updateName(Request $request)
    {
        $name = $request->input('name') ?? '';
        $email = $request->input('email') ?? '';
        $user = auth()->user();
        $redirectRoutePrefix = $user ? strtolower($user->role) : '';

        $errors = $this->nameService->validateData(
            $name,
            $email
        );

        if (count($errors) === 0) {
            $this->nameService->updateProfileInformation(
                $name,
                $email
            );
            
            return redirect(route($redirectRoutePrefix . '.settings'))
                ->withMessage(
                    'Profile Information Updated!',
                    'Success',
                    'success'
                );

        }

        return redirect(route($redirectRoutePrefix . '.settings'))
            ->withInput([
                'name' => $name,
                'email' => $email,
            ])
            ->withErrors($errors);

    }

    public function updatePassword(Request $request)
    {
        $data = [
            "currentPassword" => $request->input("currentPassword") ?? "",
            "password" => $request->input("password") ?? "",
            "confirmPassword" => $request->input("confirmPassword"),
        ];
        $user = auth()->user();
        $redirectRoutePrefix = $user ? strtolower($user->role) : '';

        $errors = $this->passwordService->validateData(
            $data['currentPassword'],
            $data['password'],
            $data['confirmPassword']
        );

        if (count($errors) === 0) {
            $this->passwordService->updatePassword($data['password']);

            return redirect(route($redirectRoutePrefix . '.settings') . '#update-password')
                ->withMessage(
                    'Passwprd Updated!',
                    'Success',
                    'success'
                );
        }

        return redirect(route($redirectRoutePrefix . '.settings') . '#update-password')
            ->withInput($data)
            ->withErrors($errors);
    }
}