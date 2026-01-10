<?php

namespace App\Rules;

use App\Helpers\Validator;

trait NameRule
{
    /**
     * Validate the name fields (first and last name)
     * 
     * @param string $name
     * @param string $attributeName
     * @return string|null
     */
    private function validateName(string $name, string $attributeName)
    {
        $error = null;
        if (!Validator::validateFieldExistence($name)) {
            $error = "{$attributeName} field cannot be empty";
            return $error;
        }

        if (!Validator::validateFieldMinLength($name, 3)) {
            $error = "{$attributeName} cannot be less than 3 characters";
            return $error;
        }

        if (!Validator::validateFieldMaxLength($name, 20)) {
            $error = "{$attributeName} cannot be greater than 20 characters";
            return $error;
        }

        return $error;
    }
}