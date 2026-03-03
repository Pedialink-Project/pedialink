<?php

namespace App\Models;

use Library\Framework\Core\Model;

class AppointmentSlot extends Model
{
    protected static string $table = "appointment_slots";
    protected array $fillable = [
        "slot_date",
        "start_time",
        "end_time",
        "capacity",
        "booked_count",
        "status",
        "doctor_id"
    ];

    public function getDoctor()
    {
        return Doctor::find($this->doctor_id);
    }
}