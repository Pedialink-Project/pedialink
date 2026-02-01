<?php

namespace App\Services\Admin;

use App\Helpers\Validator;
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

    private function validateVaccineName(string $name)
    {
        $error = null;
        if (!Validator::validateFieldExistence($name)) {
            $error = "Vaccine name cannot be empty";
            return $error;
        }

        if (!Validator::validateFieldMaxLength($name, 100)) {
            $error = "Vaccine name cannot be greater than 100 characters";
            return $error;
        }

        return $error;
    }

    private function validateVaccineCode(string $code)
    {
        $error = null;
        if (!Validator::validateFieldExistence($code)) {
            $error = "Vaccine code cannot be empty";
            return $error;
        }

        # NOTE: Add validation for unique code later
        return $error;
    }

    public function validateVaccineData(string $name, string $code, bool $edit = false)
    {
        $errors = [];
        $prefix = "";

        if ($edit) {
            $prefix = "e_";
        }

        $nameError = $this->validateVaccineName($name);
        if ($nameError) {
            $errors["{$prefix}name"] = $nameError;
        }

        $codeError = $this->validateVaccineCode($code);
        if ($codeError) {
            $errors["{$prefix}code"] = $codeError;
        }

        return $errors;
    }
}