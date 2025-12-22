<?php

namespace App\Services;

use App\Helpers\Validator;
use App\Rules\EmailRule;
use App\Rules\NameRule;
use App\Rules\PasswordRule;
use App\Rules\ValidateDivision;
use App\Rules\ValidateRule;

/**
 * Service class that encapsulates logic
 * related to authentication such as register and login.
 * 
 * NOTE: Also contains specific validation built on top of
 * Validator class.
 * 
 * SELF NOTE: Can be extracted to other classes when needed!
 */
class AuthService
{
    use NameRule, EmailRule, PasswordRule, ValidateRule;

    /**
     * Validate account type
     * 
     * @param string $type
     * @return string|null
     */
    private function validateType(string $type)
    {
        $error = null;

        if (!Validator::validateFieldExistence($type)) {
            $error = "Account type field cannot be empty";
            return $error;
        }

        $type = strtolower($type);
        if ($type !== "mother" && $type !== "father" && $type !== "guardian") {
            $error = "Account type is invalid";
            return $error;
        }

        return $error;
    }

    /**
     * Validate NIC
     * 
     * NOTE: Needs to be improved further. Currently does
     * not strictly adhere unique constraints on NIC!
     * 
     * @param string $nic
     * @return string|null
     */
    private function validateNic(string $nic)
    {
        $error = null;

        if (!Validator::validateFieldExistence($nic)) {
            $error = "NIC field cannot be empty";
            return $error;
        }

        return $error;
    }

    /**
     * Validate address
     * 
     * @param string $address
     * @return string|null
     */
    private function validateAddress(string $address)
    {
        $error = null;

        if (!Validator::validateFieldExistence($address)) {
            $error = "Address field cannot be empty";
            return $error;
        }

        return $error;
    }

    /**
     * Final encapsulated steps to validate the first form
     * 
     * @param array $data
     * @return string[]
     */
    public function validateInitialForm(array $data)
    {
        $firstName = trim(htmlspecialchars($data["firstName"]));
        $lastName = trim(htmlspecialchars($data["lastName"]));
        $email = trim(htmlspecialchars($data["email"]));
        $password = trim(htmlspecialchars($data["password"]));
        $confirmPassword = trim(htmlspecialchars($data["confirmPassword"]));

        $errors = [];

        $firstNameError = $this->validateName($firstName, "First Name");
        if ($firstNameError) {
            $errors["firstName"] = $firstNameError;
        }

        $lastNameError = $this->validateName($lastName, "Last Name");
        if ($lastNameError) {
            $errors["lastName"] = $lastNameError;
        }

        $emailError = $this->validateEmail($email);
        if ($emailError) {
            $errors["email"] = $emailError;
        }

        $passwordError = $this->validatePassword($password, $confirmPassword);
        if ($passwordError) {
            $errors["password"] = $passwordError;
        }

        return $errors;
    }

    /**
     * Final encapsulated steps to validate the second form
     * 
     * @param array $data
     * @return string[]
     */
    public function validateFinalForm(array $data)
    {
        $type = $data["type"];
        $nic = $data["nic"];
        $address = $data["address"];
        $division = $data["division"];

        $errors = [];

        $typeError = $this->validateType($type);
        if ($typeError) {
            $errors["type"] = $typeError;
        }

        $nicError = $this->validateNic($nic);
        if ($nicError) {
            $errors["nic"] = $nicError;
        }

        $addressError = $this->validateAddress($address);
        if ($addressError) {
            $errors["address"] = $addressError;
        }

        $divisonError = $this->validateDivision($division);
        if ($divisonError) {
            $errors["division"] = $divisonError;
        }

        return $errors;
    }
}