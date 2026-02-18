<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Appointment extends Model
{
    protected static string $table = "appointments";
    protected array $fillable = [
        "slot_id",
        "maternal_id",
        "child_id",
        "reason",
        "status",
        "attended_at",
        "notes"
    ];

    public function getSlot()
    {
        return AppointmentSlot::find($this->slot_id);
    }

    public function getMaternal()
    {
        return Maternal::find($this->maternal_id);
    }

    public function getChild()
    {
        return Child::find($this->child_id);
    }
}