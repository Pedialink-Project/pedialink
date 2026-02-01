<?php

namespace App\Services\Admin;

use App\Models\ParentM;
use App\Models\User;

class MaternalService
{
    public function getMaternalData(string $search)
    {
        $maternal = ParentM::query()
            ->where("type", "=", "mother")
            // ->where("name", "ILIKE", "")
            ->orderBy("id", "ASC")
            ->paginate(6)
            ->toArray();

        $resource = [];

        foreach ($maternal['items'] as $mother) {
            $user = User::find($mother->id);

            $resource[] = [
                "id" => $mother->id,
                "name" => $user->name,
                "nic" => $mother->nic,
                "age" => calculateAge($mother->date_of_birth),
                "division" => $mother->getArea()->code,
            ];
        }

        $links = array_diff_key($maternal, ['items' => true]);

        return [$resource, $links];
    }
}