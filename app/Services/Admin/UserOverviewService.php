<?php

namespace App\Services\Admin;

use App\Models\User;

class UserOverviewService
{
    public function getUserDetails()
    {
        $users = User::all();

        $resource = [];

        foreach ($users as $user) {
            $resource[] = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role,
                "email_verified_at" => false,
            ];
        }

        return $resource;
    }
}