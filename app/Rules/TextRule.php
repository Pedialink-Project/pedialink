<?php

namespace App\Rules;

use App\Helpers\Validator;

trait TextRule
{
    /**
     * Validate the reason field
     * 
     * @param string $text
     * @param string $attributeName
     * @return string|null
     */
    private function validateText(string $text, string $attributeName = 'Text')
    {
        $error = null;

        if (!Validator::validateFieldExistence($text)) {
            return "{$attributeName} field cannot be empty";
        }

        if (!Validator::validateFieldMinLength($text, 5)) {
            return "{$attributeName} must be at least 5 characters";
        }

        if (!Validator::validateFieldMaxLength($text, 255)) {
            return "{$attributeName} cannot be greater than 255 characters";
        }

        return $error;
    }
}
