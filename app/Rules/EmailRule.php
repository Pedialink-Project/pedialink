<?php

namespace App\Rules;

use App\Helpers\Validator;

trait EmailRule
{
    /**
     * Validate the email
     * 
     * @param string $email
     * @return string|null
     */
    private function validateEmail(string $email)
    {
        $error = null;
        if(!Validator::validateFieldExistence($email)) {
            $error = "Email field cannot be empty";
            return $error;
        }

        if (!Validator::validateEmailFormat($email)) {
            $error = "Email format is invalid";
            return $error;
        }

        if (Validator::validateEmailExists($email)) {
            $error = "This email is already registered with our system";
            return $error;
        }

        return $error;
    }
}