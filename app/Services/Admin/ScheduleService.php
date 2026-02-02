<?php

namespace App\Services\Admin;

use App\Helpers\Validator;
use App\Models\Schedule;

class ScheduleService
{
    public function getScheduleData(string $search)
    {
        $schedules = Schedule::query()
            ->where("name", "ILIKE", "{$search}%")
            ->orderBy("id", "ASC")
            ->paginate()
            ->toArray();

        $resource = [];

        foreach ($schedules["items"] as $schedule) {
            $resource[] = [
                "id" => $schedule->id,
                "name" => $schedule->name,
                "version" => $schedule->version,
                "effective_from" => $schedule->effective_from,
                "active" => $schedule->active
            ]; 
        }

        $links = array_diff_key($schedules, ['items' => true]);

        return [$resource, $links];
    }

    private function validateScheduleField(string $data, string $attributeName)
    {
        $error = null;

        if (!Validator::validateFieldExistence($data)) {
            $error = "{$attributeName} cannot be empty";
            return $error;
        }

        return $error;
    }

    public function validateScheduleData(
        string $name,
        string $version,
        string $date,
        bool $edit = false
    )
    {
        $errors = [];
        $prefix = "";
        if ($edit) {
            $prefix = "e_";
        }

        $scheduleNameError = $this->validateScheduleField(
            $name, 
            "Schedule name"
        );

        if ($scheduleNameError) {
            $errors["{$prefix}name"] = $scheduleNameError;
        }

        $scheduleVersionError = $this->validateScheduleField(
            $version, 
            "Schedule version"
        );

        if ($scheduleVersionError) {
            $errors["{$prefix}version"] = $scheduleVersionError;
        }

        $scheduleDateError = $this->validateScheduleField(
            $date, 
            "Date"
        );

        if ($scheduleDateError) {
            $errors["{$prefix}effective_from"] = $scheduleDateError;
        }

        return $errors;
    }
}