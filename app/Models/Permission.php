<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Permission extends Model
{
    protected static string $table = "permissions";
    protected array $fillable = ["type"];
}