<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Schedule extends Model
{
    protected static string $table = "schedules";

    protected array $fillable = ["name", "version", "effective_from", "active"];
}