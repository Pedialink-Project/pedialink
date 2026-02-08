<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Vaccine extends Model
{
    protected static string $table = "vaccines";

    protected array $fillable = ["code", "name"];
}