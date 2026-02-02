<?php

namespace App\Models;

use Library\Framework\Core\Model;

class User extends Model
{
    protected static string $table = "users";
    protected array $fillable = ["name", "role", "email", "email_verified"];

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
        if ($this->role === "parent") {
            return ParentM::find($this->id);       
        } else if ($this->role === "phm") {
            return PublicHealthMidwife::find($this->id);
        } else if ($this->role === "doctor") {
            return Doctor::find($this->id);
        } else if ($this->role === "admin") {
            return Admin::find($this->id);
        }

        return null;
    }
}