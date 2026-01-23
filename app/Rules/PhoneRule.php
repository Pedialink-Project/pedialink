<?php

namespace App\Rules;

use App\Helpers\Validator;

trait PhoneRule
{
    /**
     * Validate the phone number
     * 
     * @param string $phone
     * @return string|null
     */
    private function validatePhone(string $phone)
    {
        $error = null;
        if(!Validator::validateFieldExistence($phone)) {
            $error = "Phone number is required";
            return $error;
        }

        if (!Validator::validatePhoneNumberFormat($phone)) {
            $error = "Phone number format is invalid";
            return $error;
        }

        return $error;
    }
}