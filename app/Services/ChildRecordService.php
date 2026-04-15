<?php

namespace App\Services;

use App\Models\ChildRecord;
use App\Models\Staff;
use App\Models\User;
use App\Models\Child;
use Library\Framework\Database\QueryBuilder;
use App\Helpers\Calculator;

class ChildRecordService
{

    private $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
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

            if ($record->mark_as_invalid) {
                continue;
            }

            $resource[] = [
                'id' => $record->id,
                'visit_date' => $record->visit_date,
                'age_recorded_at' => Calculator::calculateAgeWithVisitDate(Child::find($childId)->date_of_birth, $record->visit_date),
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

    public function getLatestHeathRecord($childId){

    $record = ChildRecord::query()
        ->where('child_id', '=', $childId)
        ->where('mark_as_invalid', '=', 'false')
        ->orderBy('visit_date', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->first();

        if (!$record) {
            return null;
        }

        return [
            'id' => $record->id,
            'visit_date' => $record->visit_date,
            'age_recorded_at' => Calculator::calculateAgeWithVisitDate(Child::find($childId)->date_of_birth, $record->visit_date),
            'height' => $record->height,
            'weight' => $record->weight,
            'bmi' => $record->bmi,
            'head_circumference' => $record->head_circumference,
            'health_status' => $record->health_status,
            'notes' => $record->notes,
            'created_at' => $record->created_at,
        ];


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

        $bmi = Calculator::calculateBMI($height, $weight);

        $childDob = Child::find($childId)->date_of_birth;
        $ageMonths = Calculator::calculateAgeWithVisitDate($childDob, $visitDate);

        $healthStatus = Calculator::evaluateChildHealthStatus(
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
        $record->health_status = $healthStatus;
        $record->height = $height;
        $record->weight = $weight;
        $record->bmi = $bmi;
        $record->head_circumference = $headCircumference;
        $record->notes = $notes;


        $record->save();

        $child = Child::find($childId);
        if ($child) {
            $parents = $child->getParents();
            $recipientIds = [];
            if ($parents) {
                foreach ($parents as $parent) {
                    $user = $parent->getUser();
                    if ($user) {
                        $recipientIds[] = (int)$user->id;
                    }
                }
            }

            if (!empty($recipientIds)) {
                $message = "A new health record for {$child->name} was added on {$visitDate}.";
                $this->notificationService->notifyMany(
                    $recipientIds,
                    "Child health record added",
                    $message,
                    "child_record",
                    (int)$record->id
                );
            }
        }

        return $record;
    }

    public function editHealthRecord(
        $recordId,
        $staffId,
        $visitDate,
        $height,
        $weight,
        $headCircumference,
    ) {

        $record = ChildRecord::find($recordId);

        $recordStaffId = $record->staff_id;

        if (!$record) {
            return "Record not found.";
        }

        $bmi = Calculator::calculateBMI($height, $weight);

        $record->visit_date = $visitDate;
        $record->height = $height;
        $record->weight = $weight;
        $record->bmi = $bmi;
        $record->head_circumference = $headCircumference;

        $record->save();
        if ($recordStaffId !== $staffId) {
            $this->notificationService->notify(
                $staffId,
                "Health record updated",
                "The health record of child C-00 " . $record->child_id . " has been updated.",
                "child_record_updated",
                $record->child_id . "" . $record->child_id

            );
        }

        return null;
    }

    public function markAsInvalidRecord($recordId, $staffId)
    {
        $record = ChildRecord::find($recordId);


        $recordStaffId = $record->staff_id;

        if (!$record) {
            return "Record not found.";
        }

        $record->mark_as_invalid = true;

        $record->save();

        if ($recordStaffId !== $staffId) {

            $this->notificationService->notify(
                $recordStaffId,
                "Health record marked as invalid",
                "The health record of child C-00 " . $record->child_id . " has been marked as invalid.",
                "child_record_updated",
                $record->child_id . "" . $record->child_id

            );
        }

        return null;
    }


    public function getChildNameById($id)
    {

        $child = Child::find($id);

        return $child->name;
    }

    
}
