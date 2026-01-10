<?php

namespace App\Services\Profile;

use App\Rules\PasswordRule;

class PasswordService
{
    use PasswordRule;

    private function validateCurrentPassword(string $currentPassword)
    {
        $error = null;
        $user = auth()->user();

        if ($user && !password_verify($currentPassword, $user->password_hash)) {
            $error = "Your current password is incorrect";
        }

        return $error;
    }

    public function validateData(string $currentPassword, string $newPassword, string $confirmPassword)
    {
        $errors = [];

        $curPasswordError = $this->validateCurrentPassword($currentPassword);
        if ($curPasswordError) {
            $errors['currentPassword'] = $curPasswordError;
        }

        $passwordError = $this->validatePassword($newPassword, $confirmPassword);
        if ($passwordError) {
            $errors['password'] = $passwordError;
        }
        
        return $errors;
    }

    public function updatePassword(string $newPassword)
    {
        $user = auth()->user();

        if ($user) {
            $user->password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $user->save();
        }
    }
}