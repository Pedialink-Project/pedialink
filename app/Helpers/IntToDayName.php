<?php

namespace App\Helpers;

class IntToDayName
{
    public static function convert(int $day): string
    {
        $days = [
            0 => "Monday",
            1 => "Tuesday",
            2 => "Wednesday",
            3 => "Thursday",
            4 => "Friday",
            5 => "Saturday",
            6 => "Sunday"
        ];

        return $days[$day] ?? "Unknown";
    }
}