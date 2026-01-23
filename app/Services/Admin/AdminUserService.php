<?php

namespace App\Services\Admin;

use App\Helpers\Validator;
use App\Models\Admin;
use App\Models\AdminType;
use App\Models\User;
use Library\Framework\Database\QueryBuilder;

class AdminUserService
{
    private function applySearch(QueryBuilder $admins, string $search)
    {
        $admins->where('name', 'ILIKE', "{$search}%");
        return $admins;

    }
    public function getAdminDetails(?string $search, ?array $filters)
    {
        $admins = User::query()->where("role", "=", "admin");

        if ($search) {
            $admins = $this->applySearch($admins, $search);
        }

        // TODO: Implement nested where for filters like this to function
        // if ($filters) {
        //     foreach ($filters as $filterName => $filterValue) {
        //         if ($filterValue && is_array($filterValue)) {
        //             $adminType = AdminType::query()
        //                 ->whereIn('type', $filterValue)
        //                 ->get();

        //             $typeId = array_map(function ($type) {
        //                 return $type->id;
        //             }, $adminType);

        //             $admins->whereIn('admin_type_id', $typeId);
        //         }
        //     }
        // }

        $results = $admins
            ->orderBy('id', 'ASC')
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($results['items'] as $admin) {
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

    private function validateChangedEmail(int $id, string $email)
    {
        $error = null;

        $userHasSameEmail = User::query()->where("email", "=", strtolower($email))
            ->where("id", "=", $id)
            ->first() !== NULL ? true : false;

        if ($userHasSameEmail) {
            return $error;
        }

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

    public function validateAdminUserChanges(int $id, string $name, string $email, string $type)
    {
        $errors = [];

        $nameError = $this->validateName($name);
        if ($nameError) {
            $errors["e_name"] = $nameError;
        }

        $emailError = $this->validateChangedEmail($id, $email);
        if ($emailError) {
            $errors["e_email"] = $emailError;
        }

        $typeError = $this->validateType($type);
        if ($typeError) {
            $errors["e_type"] = $typeError;
        }

        return $errors;
    }

    public function validateDeleteAdminUser(int $id)
    {
        $error = null;

        if (auth()->id() === $id) {
            $error = "Cannot delete your own account";
            return $error;
        }

        $admin = Admin::find($id);

        if ($admin->getAdminType() === "super") {
            $superAdminType = AdminType::query()->where("type", "=", "super")->first();
            $otherSuperAdmins = Admin::query()->where("admin_type_id", "=", $superAdminType->id)->get();

            if (count($otherSuperAdmins) <= 1) {
                $error = "Cannot delete default super admin";
                return $error;
            }
        }

        return $error;
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

    public function editAdminUser(int $id, string $name, string $email, string $type)
    {
        $user = User::find($id);
        $user->name = $name;
        $user->email = $email;
        $user->save();

        $adminType = AdminType::query()->where("type", "=", $type)->first();
        
        $admin = Admin::find($id);
        $admin->admin_type_id = $adminType->id;
        $admin->save();
    }

    public function deleteAdminUser(int $id)
    {
        $admin = Admin::find($id);
        $admin->delete();

        $user = User::find($id);
        $user->delete();

    }
}