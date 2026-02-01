<?php

namespace App\Services\Admin;

use App\Models\Vaccine;

class VaccineService
{
    public function getVaccineData(string $search)
    {
        $vaccines = Vaccine::query()
            ->where("code", "ILIKE", "{$search}%")
            ->orderBy("id", "ASC")
            ->paginate(10)
            ->toArray();

        $resources = [];

        foreach ($vaccines["items"] as $vaccine) {
            $resources[] = [
                "id" => $vaccine->id,
                "name" => $vaccine->name,
                "code" => $vaccine->code,
            ];
        }

        $links = array_diff_key($vaccines, ['items' => true]);

        return [$resources, $links];
    }
}