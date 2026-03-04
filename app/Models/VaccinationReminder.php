<?php

namespace App\Models;

use Library\Framework\Core\Model;

class VaccinationReminder extends Model
{
    protected static string $table = "vaccination_reminders";
    protected array $fillable = [
        "child_id",
        "schedule_vaccine_id",
        "scheduled_date",
        "status",
    ];

    public function getChild()
    {
        return Child::find($this->child_id);
    }

    public function getScheduleVaccine()
    {
        return ScheduledVaccine::find($this->schedule_vaccine_id);
    }

    public function getLinkedVaccination()
    {
        if ($this->status !== "complete") {
            return null;
        }

        return Vaccination::query()
            ->where("child_id", "=", $this->child_id)
            ->where("schedule_vaccine_id", "=", $this->schedule_vaccine_id)
            ->first();
    }
}