<?php

namespace App\Rules;

use App\Helpers\Validator;

trait ReasonRule
{
    /**
     * Validate the reason field
     * 
     * @param string $reason
     * @param string $attributeName
     * @return string|null
     */
    private function validateReason(string $reason, string $attributeName = 'Reason')
    {
        $error = null;

        if (!Validator::validateFieldExistence($reason)) {
            return "{$attributeName} field cannot be empty";
        }

        if (!Validator::validateFieldMinLength($reason, 5)) {
            return "{$attributeName} must be at least 5 characters";
        }

        if (!Validator::validateFieldMaxLength($reason, 255)) {
            return "{$attributeName} cannot be greater than 255 characters";
        }

        return $error;
    }
}
