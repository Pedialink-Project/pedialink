<?php

namespace App\Models;

use Library\Framework\Core\Model;

class DoctorWeeklyAvailability extends Model
{
    protected static string $table = "doctor_weekly_availability";
    protected array $fillable = [
        "doctor_id",
        "weekday",
        "active",
        "start_time",
        "end_time",
        "slot_length_minutes"
    ];

    public function getDoctor()
    {
        return Doctor::find($this->doctor_id);
    }
}