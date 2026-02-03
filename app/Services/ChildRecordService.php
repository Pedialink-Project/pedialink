<?php

namespace App\Services;

use App\Models\ChildRecord;
use App\Models\Staff;
use App\Models\User;
use App\Models\Child;
use Library\Framework\Database\QueryBuilder;

class ChildRecordService
{

    private $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }



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
            $riskScore >= 2 => 'at_risk',
            default => 'good',
        };
    }


    public function getChildRecordsByChildId(
        int $childId,
        ?string $search = null,
        ?array $filters = null
    ): array {

        $recordsQuery = ChildRecord::query()
            ->where('child_id', '=', $childId);

        if ($search) {
            $recordsQuery->where('notes', 'ILIKE', "%{$search}%");
        }
        if (!empty($filters['health_status'])) {
            $recordsQuery->whereIn('health_status', $filters['health_status']);
        }


        $results = $recordsQuery
            ->orderBy('visit_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(7)
            ->toArray();

        $resource = [];

        foreach ($results['items'] as $record) {

            $resource[] = [
                'id' => $record->id,
                'visit_date' => $record->visit_date,
                'age_recorded_at' => $record->age_recorded_at,
                'height' => $record->height,
                'weight' => $record->weight,
                'bmi' => $record->bmi,
                'head_circumference' => $record->head_circumference,
                'health_status' => $record->health_status,
                'notes' => $record->notes,
                'created_at' => $record->created_at,
            ];
        }


        $links = array_diff_key($results, ['items' => true]);


        return [$resource, $links];
    }

    public function validateRecordData(
        $visitDate,
        $height,
        $weight,
        $headCircumference,
    ): array {

        $errors = [];

        if (!$visitDate) {
            $errors['visit_date'] = 'Visit date is required.';
        } elseif (!strtotime($visitDate)) {
            $errors['visit_date'] = 'Invalid visit date.';
        } elseif ($visitDate > date('Y-m-d')) {
            $errors['visit_date'] = 'Visit date cannot be in the future.';
        }

        if ($height !== null) {
            if (!is_numeric($height)) {
                $errors['height'] = 'Height must be numeric.';
            } elseif ($height < 10 || $height > 250) {
                $errors['height'] = 'Height must be between 10cm and 250cm.';
            }
        }

        if ($weight !== null) {
            if (!is_numeric($weight)) {
                $errors['weight'] = 'Weight must be numeric.';
            } elseif ($weight < 1 || $weight > 150) {
                $errors['weight'] = 'Weight must be between 1kg and 150kg.';
            }
        }

        if ($headCircumference !== null) {
            if (!is_numeric($headCircumference)) {
                $errors['head_circumference'] = 'Head circumference must be numeric.';
            } elseif ($headCircumference < 20 || $headCircumference > 70) {
                $errors['head_circumference'] = 'Head circumference must be between 20cm and 70cm.';
            }
        }

        return $errors;
    }

    public function validateEditRecordData(
        $visitDate,
        $height,
        $weight,
        $headCircumference,
    ): array {

        $errors = [];

        if (!$visitDate) {
            $errors['e_visit_date'] = 'Visit date is required.';
        } elseif (!strtotime($visitDate)) {
            $errors['e_visit_date'] = 'Invalid visit date.';
        } elseif ($visitDate > date('Y-m-d')) {
            $errors['e_visit_date'] = 'Visit date cannot be in the future.';
        }


        if (!$height) {
            $errors['e_height'] = 'Height is required.';
        } elseif (!is_numeric($height)) {
            $errors['e_height'] = 'Height must be numeric.';
        } elseif ($height < 10 || $height > 250) {
            $errors['e_height'] = 'Height must be between 10cm and 250cm.';
        }


        if (!$weight) {
            $errors['e_weight'] = 'Weight is required';
        } elseif (!is_numeric($weight)) {
            $errors['e_weight'] = 'Weight must be numeric.';
        } elseif ($weight < 1 || $weight > 150) {
            $errors['e_weight'] = 'Weight must be between 1kg and 150kg.';
        }


        if (!$headCircumference) {
            $errors['e_head_circumference'] = 'Head circumference is required.';
        } elseif (!is_numeric($headCircumference)) {
            $errors['e_head_circumference'] = 'Head circumference must be numeric.';
        } elseif ($headCircumference < 20 || $headCircumference > 70) {
            $errors['e_head_circumference'] = 'Head circumference must be between 20cm and 70cm.';
        }


        return $errors;
    }


    public function addHealthRecord(
        $childId,
        $staffId,
        $visitDate,
        $height,
        $weight,
        $headCircumference,
        $notes
    ) {

        $bmi = $this->calculateBMI($height, $weight);

        $childDob = Child::find($childId)->date_of_birth;
        $ageMonths = $this->calculateAgeInMonths($childDob);

        $healthStatus = $this->evaluateHealthStatus(
            $ageMonths,
            $height,
            $weight,
            $headCircumference,
            $bmi
        );

        $record = new ChildRecord();

        $record->child_id = $childId;
        $record->staff_id = $staffId;
        $record->visit_date = $visitDate;
        $record->age_recorded_at = $ageMonths;

        $record->height = $height;
        $record->weight = $weight;
        $record->bmi = $bmi;
        $record->head_circumference = $headCircumference;
        $record->notes = $notes;


        $record->save();

        return $record;
    }

    public function editHealthRecord(
        $recordId,
        $visitDate,
        $height,
        $weight,
        $headCircumference,
    ) {

        $record = ChildRecord::find($recordId);

        $staffId = $record->staff_id;

        if (!$record) {
            return "Record not found.";
        }

        $bmi = $this->calculateBMI($height, $weight);

        $childDob = Child::find($record->child_id)->date_of_birth;
        $ageMonths = $this->calculateAgeInMonths($childDob);

        $healthStatus = $this->evaluateHealthStatus(
            $ageMonths,
            $height,
            $weight,
            $headCircumference,
            $bmi
        );

        $record->visit_date = $visitDate;

        $record->height = $height;
        $record->weight = $weight;
        $record->bmi = $bmi;
        $record->head_circumference = $headCircumference;

        $record->save();

        $this->notificationService->notify(
            $staffId,
            "Health record updated",
            "The health record of child C-00 " . $record->child_id . " has been updated.",
            "child_record_updated",
            $record->child_id . "" . $record->child_id

        );

        return null;
    }


    public function getChildNameById($id)
    {

        $child = Child::find($id);

        return $child->name;
    }

    private function calculateAgeInMonths(string $dob): int
    {
        $dobDate = new \DateTime($dob);
        $now = new \DateTime();

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
