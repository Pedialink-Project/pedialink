<?php

namespace App\Rules;

use App\Helpers\Validator;

trait NumberRule
{
    /**
     * Validate an integer field with min and max
     *
     * @param mixed  $number
     * @param string $attributeName
     * @param int|null $min
     * @param int|null $max
     * @return string|null
     */
    private function validateInteger($number, string $attributeName = 'Number', ?int $min = null, ?int $max = null)
    {

        $error = null;
        if (!Validator::validateFieldExistence($number)) {
            $error = "{$attributeName} field cannot be empty";
        }

        if (!filter_var($number, FILTER_VALIDATE_INT)) {
            $error = "{$attributeName} must be an integer";
        }

        if ($min !== null && $number < $min) {
            $error = "{$attributeName} must be at least {$min}";
        }

        if ($max !== null && $number > $max) {
            $error = "{$attributeName} must not exceed {$max}";
        }

        return $error;
    }
}
