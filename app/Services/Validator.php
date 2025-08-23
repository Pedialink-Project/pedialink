<?php

namespace App\Services;

use App\Models\User;

/**
 * Common validator methods
 */
class Validator
{
    public static function validateFieldExistence(string $value)
    {
        $value = trim($value);
        if (strlen($value) === 0) {
            return false;
        }

        return true;
    }

    public static function validateFieldMinLength(string $value, int $minLength)
    {
        $value = trim($value);
        if (strlen($value) < $minLength) {
            return false;
        }

        return true;
    }

    public static function validateFieldMaxLength(string $value, int $maxLength)
    {
        $value = trim($value);
        if (strlen($value) > $maxLength) {
            return false;
        }

        return true;
    }

    public static function validateEmailFormat($email)
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (mb_strlen($email, 'UTF-8') > 320) {
            return false;
        }

        $parts = explode('@', $email);

        if (count($parts) !== 2) {
            return false;
        }

        if (mb_strlen($parts[0], 'UTF-8') > 64) {
            return false;
        }

        return true;
    }

    public static function validateEmailExists(string $email)
    {
        $users = User::query()
            ->where("email", htmlspecialchars(trim($email)))
            ->get();

        if (count($users) === 0) {
            return true;
        }

        return false;
    }

    public static function validatePassword(string $password, string $passwordHash)
    {
        if (password_verify($password, $passwordHash)) {
            return true;
        }

        return false;
    }
}