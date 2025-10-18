<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\AdminType;
use App\Models\User;
use App\Services\Validator;

class AdminUserService
{
    public function getAdminDetails()
    {
        $admins = User::query()->where("role", "=", "admin")->get();

        $resource = [];

        foreach ($admins as $admin) {
            $resource[] = [
                "id" => $admin->id,
                "name" => $admin->name,
                "email" => $admin->email,
                "type" => $admin->getRole()->getAdminType(),
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

    private function validateType(string $type)
    {
        $error = null;
        if (!Validator::validateFieldExistence($type)) {
            $error = "Admin role cannot be empty";
            return $error;
        }

        $adminTypes = AdminType::all();
        $validType = false;

        foreach ($adminTypes as $adminType) {
            if (strtolower($type) === strtolower($adminType->type)) {
                $validType = true;
                break;
            }
        }

        if (!$validType) {
            $error = "Invalid admin type";
            return $error;
        }

        return $error;
    }

    public function validateAdminUser(string $name, string $email, string $type)
    {
        $errors = [];

        $nameError = $this->validateName($name);
        if ($nameError) {
            $errors["name"] = $nameError;
        }

        $emailError = $this->validateEmail($email);
        if ($emailError) {
            $errors["email"] = $emailError;
        }

        $typeError = $this->validateType($type);
        if ($typeError) {
            $errors["type"] = $typeError;
        }

        return $errors;
    }

    public function createAdminUser(string $name, string $email, string $type)
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password_hash = password_hash("password", PASSWORD_DEFAULT);
        $user->role = "admin";
        $userId = $user->save();

        $adminType = AdminType::query()->where("type", "=", $type)->first();

        $admin = new Admin();
        $admin->id = $userId;
        $admin->admin_type_id = $adminType->id;
        $admin->save();
    }
}