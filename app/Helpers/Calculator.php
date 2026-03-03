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

    public static function calculateAge(string $dateOfBirth): int
    {
        $dob = new \DateTime($dateOfBirth);
        $today = new \DateTime();

        $diff = $today->diff($dob);

        return $diff->y;
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

    public static function  calculateEdd(string $lmp): string
    {
        $lmpDate = new \DateTime($lmp);

        // Add 280 days (40 weeks)
        $lmpDate->modify('+280 days');

        return $lmpDate->format('Y-m-d');
    }


   public static function calculateMaternalHealthStatus(
    ?float $hemoglobin,
    ?float $glucose,
    ?int $bloodPressure, 
): string {

    $riskScore = 0;

    if ($bloodPressure !== null) {
        if ($bloodPressure >= 160) {
            $riskScore += 3; // Severe hypertension
        } elseif ($bloodPressure >= 140) {
            $riskScore += 2; // Mild hypertension
        }
    }

    if ($hemoglobin !== null) {
        if ($hemoglobin < 7) {
            $riskScore += 3; // Severe anemia
        } elseif ($hemoglobin < 10) {
            $riskScore += 2; // Moderate anemia
        }
    }

    if ($glucose !== null) {
        if ($glucose >= 11) {
            $riskScore += 3; // Very high
        } elseif ($glucose >= 7.8) {
            $riskScore += 2; // Borderline
        }
    }

    // Final decision
    return match (true) {
        $riskScore >= 5 => 'critical',
        $riskScore >= 3 => 'at_risk',
        default => 'good',
    };
}

public static function calculateGestationWeeks(string $lmp): int
{
    $lmpDate = new \DateTime($lmp);
    $today = new \DateTime();

    $diff = $today->diff($lmpDate);

    return (int) floor($diff->days / 7);
}


public static function calculateTrimester(?int $gestationWeeks): ?string
{
    if ($gestationWeeks === null || $gestationWeeks < 0) {
        return null;
    }

    return match (true) {
        $gestationWeeks <= 12 => 'first_trimester',
        $gestationWeeks <= 27 => 'second_trimester',
        $gestationWeeks <= 40 => 'third_trimester',
        default => 'post_term',
    };
}
}
