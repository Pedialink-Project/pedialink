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
            $common = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role,
                "email_verified_at" => false,
                "created_at" => $user->created_at,
            ];

            $additional = [];

            switch ($user->role) {
                case "parent":
                    $parent = $user->getRole();
                    $additional = [
                        "nic" => $parent->nic,
                        "type" => $parent->type,
                        "area" => $parent->getArea()?->code,
                    ];
                    break;
                case "phm":
                    $phm = $user->getRole();
                    $staff = $phm->getStaff();
                    $additional = [
                        "nic" => $staff->nic,
                        "license_no" => $staff->license_no,
                        "area" => $phm->getArea()->code,
                    ];
                    break;
                case "doctor":
                    $doctor = $user->getRole();
                    $staff = $doctor->getStaff();
                    $additional = [
                        "nic" => $staff->nic,
                        "license_no" => $staff->license_no,
                    ];
                    break;
                case "admin":
                    $admin = $user->getRole();
                    $additional = [
                        "type" => $admin->getAdminType(),
                    ];
                    break;
            }

            $resource[] = array_merge($common, $additional);
        }

        return $resource;
    }
}