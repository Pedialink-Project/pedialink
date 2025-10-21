<?php

namespace App\Services;

use App\Models\Child;
use App\Models\Patient;

class ChildService
{
    public function getAllChildren()
    {
        $children = Child::all();

        $resource = [];
        foreach ($children as $child) {
            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'date_of_birth' => $child->date_of_birth,
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'gs_division' => $child->gs_division,
                'vaccination_status' => $child->vaccination_status,
                'notes' => $child->notes,
            ];
        }

        return $resource;
    }

    private function validateName(string $name)
    {
        $error = null;
        if (!Validator::validateFieldExistence($name)) {
            $error = "Name field cannot be empty";
            return $error;
        }

        if (!Validator::validateFieldMinLength($name, 3)) {
            $error = "Name cannot be less than 3 characters";
            return $error;
        }

        if (!Validator::validateFieldMaxLength($name, 20)) {
            $error = "Name cannot be greater than 20 characters";
            return $error;
        }

        return $error;
    }

    private function validateCommonFields(string $field, string $attributeName)
    {
        $error = null;
        if (!Validator::validateFieldExistence($field)) {
            $error = "{$attributeName} field cannot be empty";
            return $error;
        }

        return $error;
    }


    private function validateGender(string $gender)
    {
        $error = null;
        if (!Validator::validateFieldExistence($gender)) {
            $error = "Gender field cannot be empty";
            return $error;
        }

        $gender = strtolower($gender);
        if ($gender !== "male" && $gender !== "female") {
            $error = "Invalid Gender";
            return $error;
        }

        return $error;
    }

    public function validateChildProfile(string $name, string $division, string $dob, string $gender)
    {
        $errors = [];

        $nameError = $this->validateName($name);
        if ($nameError) {
            $errors["name"] = $nameError;
        }

        $divisionError= $this->validateCommonFields($division, "GS Division");
        if ($divisionError) {
            $errors["division"] = $divisionError;
        }

        $dobError= $this->validateCommonFields($dob, "Date of Birth");
        if ($dobError) {
            $errors["dob"] = $dobError;
        }

        $genderError= $this->validateGender($gender);
        if ($genderError) {
            $errors["gender"] = $genderError;
        }

        return $errors;
    }

    public function createChildProfile(string $name, string $division, string $dob, string $gender)
    {
        $phmId = auth()->id();

        $patient = new Patient();
        $patient->type = "child";
        $patientId = $patient->save();

        $child = new Child();
        $child->id = $patientId;
        $child->name = $name;
        $child->date_of_birth = $dob;
        $child->gender = $gender;
        $child->gs_division = $division;
        $child->phm_id = $phmId;
        $child->save();
    }
}