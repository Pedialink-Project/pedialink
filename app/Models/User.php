<?php

namespace App\Models;

use Library\Framework\Core\Model;

class User extends Model
{
    protected static string $table = "users";
    protected array $fillable = ["name", "email"];
}