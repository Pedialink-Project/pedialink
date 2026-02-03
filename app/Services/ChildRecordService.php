<?php

namespace App\Services;

use App\Models\ChildRecord;
use App\Models\Staff;
use App\Models\User;
use App\Models\Child;

class ChildRecordService
{


    private function calculateBMI(?float $heightCm, ?float $weightKg): ?float
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

    private function evaluateHealthStatus(
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
        $riskScore >= 2 => 'at-risk',
        default => 'good',
    };
}


    public function getChildRecordsByChildId(int $childId): array
    {
        $records = ChildRecord::query()
            ->where('child_id', '=', $childId)
            ->orderBy('visit_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        $resource = [];

        foreach ($records as $record) {

            $staff = Staff::find($record->staff_id);

            $staffResource = null;

            if ($staff) {
                $user = User::find($staff->id);

                $staffResource = [
                    'id' => $staff->id,
                    'name' => $user?->name,
                    'role' => $staff->role,
                ];
            }

            $resource[] = [
                'id' => $record->id,
                'visit_date' => $record->visit_date,
                'age_recorded_at' => $record->age_recorded_at,
                'height' => $record->height,
                'weight' => $record->weight,
                'bmi' => $record->bmi,
                'head_circumference' => $record->head_circumference,
                'health_status' => $this->evaluateHealthStatus(
                    $record->age_recorded_at,
                    $record->height,
                    $record->weight,
                    $record->head_circumference,
                    $record->bmi
                ),
                'notes' => $record->notes,
                'created_at' => $record->created_at,

                'staff' => $staffResource
            ];
        }

        return $resource;
    }

    public function getChildNameById($id){

        $child = Child::find($id);

        return $child->name;

    

    }
}
