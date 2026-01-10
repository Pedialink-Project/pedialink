<?php

namespace App\Services\Profile;

use App\Rules\EmailRule;
use App\Rules\NameRule;

class NameService
{
    use NameRule, EmailRule;

    public function validateData(string $name, string $email)
    {
        $errors = [];
        $user = auth()->user();

        $nameError = $this->validateName($name, "Name");
        if ($nameError) {
            $errors["name"] = $nameError;
        }

        if ($user && $user->email !== $email) {
            $emailError = $this->validateEmail($email);
            if ($emailError) {
                $errors["email"] = $emailError;
            }
        }

        return $errors;
    }

    public function updateProfileInformation(string $name, string $email) 
    {
        $user = auth()->user();

        if ($user) {
            $user->name = $name;
            $user->email = $email;
            $user->save();
        }
    }
}