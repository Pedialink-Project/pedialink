<?php

namespace App\Models;

use Library\Framework\Core\Model;

class AdminType extends Model
{
    protected static string $table = "admin_types";
    protected array $fillable = ["id", "type"];
}