<?php

namespace App\Helpers;

class Calculator
{
    public static function calculateAgeWithVisitDate(string $dob, string $visitDate): int
    {
        $dobDate = new \DateTime($dob);
        $now = new \DateTime($visitDate);

        if ($dobDate > $now) {
            return 0;
        }

        $diff = $now->diff($dobDate);

        if ($diff->days < 30) {
            return $diff->days;
        }

        $months = ($diff->y * 12) + $diff->m;

        if ($diff->d >= 15) {
            $months++;
        }

        return $months;
    }

    public static function calculateAgeInYears(string $dateOfBirth): int
    {
        $dob = new \DateTime($dateOfBirth);
        $today = new \DateTime();

        $diff = $today->diff($dob);

        return $diff->y;
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
        $ageMonths = max(0, $ageMonths);
        $riskScore = 0;
        $severeFlags = 0;

        $weightRange = self::resolveAgeRange($ageMonths, [
            ['min' => 0, 'max' => 1, 'low' => 2.5, 'high' => 5.5],
            ['min' => 2, 'max' => 3, 'low' => 4.0, 'high' => 7.5],
            ['min' => 4, 'max' => 6, 'low' => 5.0, 'high' => 9.5],
            ['min' => 7, 'max' => 12, 'low' => 6.0, 'high' => 11.5],
            ['min' => 13, 'max' => 24, 'low' => 7.5, 'high' => 14.5],
            ['min' => 25, 'max' => 36, 'low' => 10.0, 'high' => 16.5],
            ['min' => 37, 'max' => 60, 'low' => 12.0, 'high' => 22.0],
            ['min' => 61, 'max' => 96, 'low' => 16.0, 'high' => 31.0],
            ['min' => 97, 'max' => 144, 'low' => 22.0, 'high' => 50.0],
            ['min' => 145, 'max' => 216, 'low' => 35.0, 'high' => 90.0],
        ]);

        $heightRange = self::resolveAgeRange($ageMonths, [
            ['min' => 0, 'max' => 1, 'low' => 46.0, 'high' => 58.0],
            ['min' => 2, 'max' => 3, 'low' => 53.0, 'high' => 64.0],
            ['min' => 4, 'max' => 6, 'low' => 58.0, 'high' => 70.0],
            ['min' => 7, 'max' => 12, 'low' => 63.0, 'high' => 80.0],
            ['min' => 13, 'max' => 24, 'low' => 72.0, 'high' => 92.0],
            ['min' => 25, 'max' => 36, 'low' => 82.0, 'high' => 102.0],
            ['min' => 37, 'max' => 60, 'low' => 95.0, 'high' => 116.0],
            ['min' => 61, 'max' => 96, 'low' => 108.0, 'high' => 135.0],
            ['min' => 97, 'max' => 144, 'low' => 128.0, 'high' => 160.0],
            ['min' => 145, 'max' => 216, 'low' => 145.0, 'high' => 185.0],
        ]);

        $headRange = $ageMonths <= 60
            ? self::resolveAgeRange($ageMonths, [
                ['min' => 0, 'max' => 1, 'low' => 33.0, 'high' => 39.0],
                ['min' => 2, 'max' => 3, 'low' => 36.0, 'high' => 42.0],
                ['min' => 4, 'max' => 6, 'low' => 39.0, 'high' => 45.0],
                ['min' => 7, 'max' => 12, 'low' => 42.0, 'high' => 48.0],
                ['min' => 13, 'max' => 24, 'low' => 45.0, 'high' => 50.0],
                ['min' => 25, 'max' => 36, 'low' => 46.0, 'high' => 51.0],
                ['min' => 37, 'max' => 60, 'low' => 47.0, 'high' => 53.0],
            ])
            : null;

        if ($weightRange !== null) {
            self::applyRangeRisk($weightKg, $weightRange['low'], $weightRange['high'], $riskScore, $severeFlags);
        }

        if ($heightRange !== null) {
            self::applyRangeRisk($heightCm, $heightRange['low'], $heightRange['high'], $riskScore, $severeFlags);
        }

        if ($headRange !== null) {
            self::applyRangeRisk($headCircumference, $headRange['low'], $headRange['high'], $riskScore, $severeFlags);
        }

        if ($bmi !== null) {
            if ($ageMonths >= 24) {
                $bmiRange = self::resolveAgeRange($ageMonths, [
                    ['min' => 24, 'max' => 59, 'low' => 14.0, 'high' => 18.5],
                    ['min' => 60, 'max' => 119, 'low' => 13.5, 'high' => 21.0],
                    ['min' => 120, 'max' => 216, 'low' => 14.0, 'high' => 25.0],
                ]);

                if ($bmiRange !== null) {
                    self::applyRangeRisk($bmi, $bmiRange['low'], $bmiRange['high'], $riskScore, $severeFlags);
                }
            } elseif ($bmi < 10 || $bmi > 30) {
                $riskScore += 2;
                $severeFlags++;
            }
        }

        return match (true) {
            $severeFlags >= 2 || $riskScore >= 4 => 'critical',
            $severeFlags >= 1 || $riskScore >= 2 => 'at_risk',
            default => 'good',
        };
    }

    private static function resolveAgeRange(int $ageMonths, array $ranges): ?array
    {
        foreach ($ranges as $range) {
            if ($ageMonths >= $range['min'] && $ageMonths <= $range['max']) {
                return $range;
            }
        }

        return null;
    }

    private static function applyRangeRisk(
        ?float $value,
        float $low,
        float $high,
        int &$riskScore,
        int &$severeFlags
    ): void {
        if ($value === null) {
            return;
        }

        if ($value >= $low && $value <= $high) {
            return;
        }

        $range = max(0.5, $high - $low);
        $margin = max(0.5, $range * 0.15);

        $isSevere = $value < ($low - $margin) || $value > ($high + $margin);

        if ($isSevere) {
            $riskScore += 2;
            $severeFlags++;
            return;
        }

        $riskScore += 1;
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
        if ($glucose >= 200) {
            $riskScore += 3; // Very high
        } elseif ($glucose >= 140) {
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
        $gestationWeeks <= 12 => 'first',
        $gestationWeeks <= 27 => 'second',
        $gestationWeeks <= 40 => 'third',
        default => 'post_term',
    };
}

public static function formatTimeToAmPm(?string $time): ?string
{
    if (!$time) {
        return null;
    }

    $dateTime = new \DateTime($time);

    return $dateTime->format('g:i A');
}

public static function calculateMaternalAgeAtLMP(?string $dateOfBirth, ?string $lmp): ?int
{
    if (!$dateOfBirth || !$lmp) {
        return null;
    }

    $dob = new \DateTime($dateOfBirth);
    $lmpDate = new \DateTime($lmp);

    if ($dob > $lmpDate) {
        return null; 
    }

    $diff = $lmpDate->diff($dob);

    return $diff->y; 
}

public static function formatAmPmToTime(?string $time): ?string
{
    if (!$time) {
        return null;
    }

    $dateTime = \DateTime::createFromFormat('g:i A', $time);

    if (!$dateTime) {
        return null; 
    }

    return $dateTime->format('H:i:s');
}

}
