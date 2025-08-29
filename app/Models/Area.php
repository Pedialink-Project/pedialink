<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Area extends Model
{
    protected static string $table = "areas";
    protected array $fillable = ["code"];
}