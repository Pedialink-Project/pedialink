<?php

namespace App\Controllers;

class SettingController
{
    public function index()
    {
        $user = auth()->user();
        return view("auth/settings", [
            "name" => $user->name,
            "email" => $user->email,
        ]);
    }
}