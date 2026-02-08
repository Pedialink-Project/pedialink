<?php

namespace App\Models;

use Library\Framework\Core\Model;

class ScheduledVaccine extends Model
{
    protected static string $table = "schedule_vaccines";

    protected array $fillable = [
        "vaccine_id",
        "schedule_id",
        "dose_number",
        "min_age_days",
        "due_age_days",
        "min_age_gap_days",
        "additional_information"
    ];

    public function getVaccine()
    {
        $vaccine = Vaccine::find($this->vaccine_id);
        return $vaccine;
    }

    public function getSchedule()
    {
        $schedule = Schedule::find($this->schedule_id);
        return $schedule;
    }
}