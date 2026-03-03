<?php

namespace App\Helpers;

class AppointmentConfigurationHelper
{
    public static function weekdaySearch(string $search): int
    {
        $searchLower = strtolower($search);
        $dayMap = [
            'monday' => 0,
            'tuesday' => 1,
            'wednesday' => 2,
            'thursday' => 3,
            'friday' => 4,
            'saturday' => 5,
            'sunday' => 6,
        ];
        
        foreach ($dayMap as $dayName => $weekdayNum) {
            if (str_starts_with($dayName, $searchLower)) {
                return $weekdayNum;
            }
        }

        return -1;
    }

    public static function statusFilter(array $filters): array
    {
        $value = [];
        foreach ($filters as $status) {
            if ($status === "active") {
                $value[] = 1;
            } else if ($status === "inactive") {
                $value[] = 0;
            }
        }
        return $value;
    }
}