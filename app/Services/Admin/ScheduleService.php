<?php

namespace App\Services\Admin;

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
}