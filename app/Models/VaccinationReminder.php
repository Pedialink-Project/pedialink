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
        return Vaccination::query()
            ->where("child_id", "=", $this->child_id)
            ->where("schedule_vaccine_id", "=", $this->schedule_vaccine_id)
            ->first();
    }

    public function getComputedStatus(): string
    {
        $linkedVaccination = $this->getLinkedVaccination();
        if ($linkedVaccination) {
            return "complete";
        }

        $today = new \DateTimeImmutable('today');

        try {
            $scheduledDate = new \DateTimeImmutable((string)$this->scheduled_date);
        } catch (\Exception $e) {
            return "pending";
        }

        if ($scheduledDate < $today) {
            return "overdue";
        }

        return "pending";
    }
}