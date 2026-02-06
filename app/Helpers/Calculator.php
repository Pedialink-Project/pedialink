<?php

namespace App\Helpers;

class Calculator
{
     public static function calculateAgeInMonths(string $dob, string $visitDate): int
    {
        $dobDate = new \DateTime($dob);
        $now = new \DateTime($visitDate);

        if ($dobDate > $now) {
            return 0;
        }

        $diff = $now->diff($dobDate);

        $months = ($diff->y * 12) + $diff->m;

        if ($diff->d >= 15) {
            $months++;
        }

        return $months;
    }


    
}