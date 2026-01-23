<?php

namespace App\Rules;

use App\Helpers\Validator;


trait PasswordRule
{
    /**
     * Validate the password
     * 
     * @param string $password
     * @param string $confirmPassword
     * @return string|null
     */
    private function validatePassword(string $password, string $confirmPassword)
    {
        $error = null;
        if (!Validator::validateFieldExistence($password)) {
            $error = "Password field cannot be empty";
            return $error;
        }

        if (!Validator::validateFieldMinLength($password, 6)) {
            $error = "Password cannot be less than 6 characters";
            return $error;
        }

        if (!Validator::validatePassword($password, password_hash($confirmPassword, PASSWORD_DEFAULT))) {
            $error = "Passwords do not match";
            return $error;
        }

        return $error;
    }
}