<?php

namespace App\Services\Admin;

use App\Models\User;
use Library\Framework\Database\QueryBuilder;

class UserOverviewService
{
    private function applySearch(QueryBuilder $users, string $search)
    {
        $users->where('name', 'ILIKE', "$search%");

        return $users;
    }

    private function applyFilters(QueryBuilder $users, array $filters)
    {
        foreach ($filters as $filterName => $filterValue) {
            if ($filterValue && is_array($filterValue)) {
                $users->whereIn('role', $filterValue);
            }
        }

        return $users;
    }

    public function getUserDetails(?string $search, ?array $filters)
    {
        $users = User::query();

        if ($search) {
            $users = $this->applySearch($users, $search);
        }

        if ($filters) {
            $users = $this->applyFilters($users, $filters);
        }

        $results = $users
            ->orderBy('id', 'ASC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($results['items'] as $user) {
            $common = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role,
                "email_verified_at" => $user->email_verified,
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

        $links = array_diff_key($results, ['items' => true]);

        return [$resource, $links];
    }
}