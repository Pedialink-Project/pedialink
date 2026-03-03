<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ClinicWeeklyAvailability extends Model
{
    protected static string $table = "clinic_weekly_availability";
    protected array $fillable = [
        "weekday",
        "active",
        "start_time",
        "end_time",
        "slot_length_minutes"
    ];
}