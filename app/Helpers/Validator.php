<?php

namespace App\Helpers;

use App\Models\User;

/**
 * Common validator methods
 */
class Validator
{
    /**
     * Validate if an input field does not contain data (empty).
     * 
     * NOTE: Right now only considers input fields with string like data.
     * 
     * @param string $value
     * @return bool
     */
    public static function validateFieldExistence(string $value)
    {
        $value = trim($value);
        if (strlen($value) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if a field is greater or equal to minimum length
     * 
     * @param string $value
     * @param int $minLength
     * @return bool
     */
    public static function validateFieldMinLength(string $value, int $minLength)
    {
        $value = trim($value);
        if (strlen($value) < $minLength) {
            return false;
        }

        return true;
    }

    /**
     * Check if a field is lesser or equal to maximum length
     * 
     * @param string $value
     * @param int $maxLength
     * @return bool
     */
    public static function validateFieldMaxLength(string $value, int $maxLength)
    {
        $value = trim($value);
        if (strlen($value) > $maxLength) {
            return false;
        }

        return true;
    }

    /**
     * Validate email formats according to common
     * standards
     * 
     * @param mixed $email
     * @return bool
     */
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

    /**
     * Check if email already exists in the system
     * 
     * @param string $email
     * @return bool
     */
    public static function validateEmailExists(string $email)
    {
        $users = User::query()
            ->where("email", "=", htmlspecialchars(trim($email)))
            ->get();

        if (count($users) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Verify if the password is valid.
     * 
     * @param string $password
     * @param string $passwordHash
     * @return bool
     */
    public static function validatePassword(string $password, string $passwordHash)
    {
        if (password_verify($password, $passwordHash)) {
            return true;
        }

        return false;
    }
}