<?php

namespace App\Models;

use Library\Framework\Core\Model;

class User extends Model
{
    protected static string $table = "users";
    protected array $fillable = ["name", "role", "email"];

    public function isParent()
    {
        if ($this->role === "parent") {
            return true;
        }

        return false;
    }

    public function isPublicHealthMidwife()
    {
        if ($this->role === "phm") {
            return true;
        }

        return false;
    }

    public function isDoctor()
    {
        if ($this->role === "doctor") {
            return true;
        }

        return false;
    }

    public function isAdmin()
    {
        if ($this->role === "admin") {
            return true;
        }

        return false;
    }

    public function getRole()
    {
        return Admin::find($this->id);
    }
}