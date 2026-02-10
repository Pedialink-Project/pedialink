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

     public static function calculateAge($dob): string
    {
        $dobDt = $dob instanceof \DateTime ? clone $dob : new \DateTime($dob);
        $now = new \DateTime();

        if ($dobDt > $now) {
            return "0 months"; // simple handling for future dates
        }

        $diff = $now->diff($dobDt);

        if ($diff->y >= 1) {
            $y = $diff->y;
            return $y . ' year' . ($y === 1 ? '' : 's');
        }

        if ($diff->m >= 1) {
            $m = $diff->m;
            return $m . ' month' . ($m === 1 ? '' : 's');
        }

        $d = $diff->d;
        return $d . ' day' . ($d === 1 ? '' : 's');
    }


    public static function calculateBMI(?float $heightCm, ?float $weightKg): ?float
    {
        if (!$heightCm || !$weightKg) {
            return null;
        }

        $heightM = $heightCm / 100;

        if ($heightM <= 0) {
            return null;
        }

        $bmi = $weightKg / ($heightM * $heightM);

        return round($bmi, 2);
    }

     public static function evaluateChildHealthStatus(
        int $ageMonths,
        ?float $heightCm,
        ?float $weightKg,
        ?float $headCircumference,
        ?float $bmi
    ): string {

        $riskScore = 0;

        if ($bmi !== null) {
            if ($bmi < 14 || $bmi > 25) {
                $riskScore += 2;
            } elseif ($bmi < 15 || $bmi > 23) {
                $riskScore += 1;
            }
        }

        if ($weightKg !== null) {
            if ($weightKg < 2 || $weightKg > 80) {
                $riskScore += 2;
            }
        }

        if ($heightCm !== null) {
            if ($heightCm < 45 || $heightCm > 200) {
                $riskScore += 2;
            }
        }

        if ($ageMonths <= 60 && $headCircumference !== null) {
            if ($headCircumference < 40 || $headCircumference > 55) {
                $riskScore += 2;
            }
        }

        return match (true) {
            $riskScore >= 4 => 'critical',
            $riskScore >= 2 => 'at_risk',
            default => 'good',
        };
    }

}
