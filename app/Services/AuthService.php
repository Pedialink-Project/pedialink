<?php

namespace App\Services;

use App\Helpers\NicExtractor;
use App\Helpers\NicValidator;
use App\Helpers\Validator;
use App\Models\ParentM;
use App\Models\Staff;
use App\Models\User;
use App\Rules\DivisionRule;
use App\Rules\EmailRule;
use App\Rules\NameRule;
use App\Rules\PasswordRule;


class AuthService
{
    use NameRule, EmailRule, PasswordRule, DivisionRule, NicValidator;

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
        $nic = $data["nic"];
        $address = $data["address"];
        $division = $data["division"];

        $errors = [];


        $nicError = $this->validateNic($nic);
        if ($nicError) {
            $errors["nic"] = $nicError;
        } else {
            $parents = ParentM::query()
                ->where("nic", "=", $nic)
                ->get();
    
            if (count($parents) > 0) {
                $errors['nic'] = "Nic already used in system";
            }
    
            $staff = Staff::query()
                ->where("nic", "=", $nic)
                ->get();

            if (count($staff) > 0) {
                $errors['nic'] = "Nic already used in system";
            }

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

    public function createParentAccount(array $data)
    {
        $user = new User();
        $user->name = $data["firstName"] . " " . $data["lastName"];
        $user->email = $data["email"];
        $user->password_hash = $data["passwordHash"];
        $user->role = "parent";
        $userId = $user->save();

        $nicExtractor = new NicExtractor($data["nic"]);
        $extractedResults = $nicExtractor->getExtractedNic();

        $parent = new ParentM();
        $parent->id = $userId;
        $parent->type = $extractedResults["gender"] === "M" ? "father" : "mother";
        $parent->address = $data["address"];
        $parent->date_of_birth = $extractedResults["dob"];
        $parent->nic = $data["nic"];
        $parent->area_id = (int)$data["division"];
        $parent->save();

        return $user;
    }
}