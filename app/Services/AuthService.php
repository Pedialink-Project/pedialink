<?php

namespace App\Services;

use App\Models\Area;

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
    /**
     * Validate the name fields (first and last name)
     * 
     * @param string $name
     * @param string $attributeName
     * @return string|null
     */
    private function validateName(string $name, string $attributeName)
    {
        $error = null;
        if (!Validator::validateFieldExistence($name)) {
            $error = "{$attributeName} field cannot be empty";
            return $error;
        }

        if (!Validator::validateFieldMinLength($name, 3)) {
            $error = "{$attributeName} cannot be less than 3 characters";
            return $error;
        }

        if (!Validator::validateFieldMaxLength($name, 20)) {
            $error = "{$attributeName} cannot be greater than 20 characters";
            return $error;
        }

        return $error;
    }

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
     * Validate GS divisions
     * 
     * @param string $division
     * @return string|null
     */
    private function validateDivision(string $division)
    {
        $error = null;

        if (!Validator::validateFieldExistence($division)) {
            $error = "GS Division field cannot be empty";
            return $error;
        }

        if (count(Area::query()->where("id", "=", (int)$division)->get()) === 0) {
            $error = "Invalid GS Division";
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