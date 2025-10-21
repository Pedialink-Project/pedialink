<?php

namespace App\Services;

use App\Models\Child;
use App\Models\ParentM;
use App\Models\Patient;
use App\Models\User;
use DateTime;

class ChildService
{
    private function calculateAge($dob): string
    {
        $dobDt = $dob instanceof DateTime ? clone $dob : new DateTime($dob);
        $now = new DateTime();

        if ($dobDt > $now) {
            return "0 months"; // simple handling for future dates
        }

        $diff = $now->diff($dobDt);

        if ($diff->y >= 1) {
            $y = $diff->y;
            return $y . ' year' . ($y === 1 ? '' : 's');
        }

        // less than 1 year -> return months only (integer)
        $m = $diff->m;
        return $m . ' month' . ($m === 1 ? '' : 's');
    }

    public function getAllChildren()
    {
        $children = Child::all();

        $resource = [];
        foreach ($children as $child) {

            $parent = ParentM::find($child->parent_id);

            $parentResource = NULL;
            if ($parent) {
                $parentResource = [
                    'id' => $parent->id,
                    'name' => User::find($parent->id)->name,
                    'type' => $parent->type,
                ];
            }

            $resource[] = [
                'id' => $child->id,
                'name' => $child->name,
                'age' => $this->calculateAge($child->date_of_birth),
                'date_of_birth' => $child->date_of_birth,
                'gender' => $child->gender,
                'health_status' => $child->health_status,
                'gs_division' => $child->gs_division,
                'vaccination_status' => $child->vaccination_status,
                'notes' => $child->notes,
                'parent' => $parentResource,
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

    public function validateChildProfile(string $name, string $division, string $dob, string $gender, bool $edit = false)
    {
        $errors = [];
        $suffix = $edit ? 'e_' : '';

        $nameError = $this->validateName($name);
        if ($nameError) {
            $errors["{$suffix}name"] = $nameError;
        }

        $divisionError= $this->validateCommonFields($division, "GS Division");
        if ($divisionError) {
            $errors["{$suffix}division"] = $divisionError;
        }

        $dobError= $this->validateCommonFields($dob, "Date of Birth");
        if ($dobError) {
            $errors["{$suffix}dob"] = $dobError;
        }

        $genderError= $this->validateGender($gender);
        if ($genderError) {
            $errors["{$suffix}gender"] = $genderError;
        }

        return $errors;
    }

    public function validateDeleteProfile(int $id)
    {
        $error = null;

        $child = Child::find($id);

        if ($child && $child->parent_id !== NULL) {
            $error = "Cannot delete linked child account";
        }

        return $error;
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

    public function editChildProfile(int $childId, string $name, string $division, string $dob, string $gender)
    {
        $child = Child::find($childId);
        if ($child) {
            $child->name = $name;
            $child->date_of_birth = $dob;
            $child->gender = $gender;
            $child->gs_division = $division;
            $child->save();
        }
    }

    public function deleteChildProfile(int $id)
    {
        $child = Child::find($id);

        $patient = Patient::find($child->id);
        $patient->delete();
        
        $child->delete();
    }
}