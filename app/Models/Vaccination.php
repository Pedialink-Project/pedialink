<?php

namespace App\Models;

use Library\Framework\Core\Model;

class Vaccination extends Model
{
    protected static string $table = "vaccinations";
    protected array $fillable = [
        "schedule_vaccine_id",
        "child_id",
        "administered_at",
        "recorded_at",
    ];

    public function getChild()
    {
        return Child::find($this->child_id);
    }

    public function getScheduleVaccine()
    {
        return ScheduledVaccine::find($this->schedule_vaccine_id);
    }
}