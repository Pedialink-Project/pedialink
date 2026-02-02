<?php

namespace App\Rules;

use App\Helpers\Validator;
use App\Models\Area;


trait DivisionRule
{
    /**
     * Validate GS divisions
     * 
     * @param string $division
     * @return string|null
     */
    private function validateDivision(string $division)
    {
        $error = null;

        if (!Validator::validateFieldExistence($division)) {
            $error = "GS Division field cannot be empty";
            return $error;
        }

        if (count(Area::query()->where("id", "=", (int)$division)->get()) === 0) {
            $error = "Invalid GS Division";
            return $error;
        }

        return $error;
    }
}