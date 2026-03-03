<?php

namespace App\Services;

use App\Helpers\Calculator;
use App\Models\MaternalStat;
use App\Models\Maternal;
use App\Models\ParentM;
use App\Models\MaternalRecord;
use App\Models\Pregnancy;
use App\Models\User;

class MaternalRecordService
{

    private $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }
    public function getAllMaternalRecords()
    {
        $maternalRecords = MaternalRecord::all();

        $resource = [];
        foreach ($maternalRecords as $record) {
            $resource[] = [
                'id' => $record->id,
                'maternal_id' => $record->maternal_id,
                'parent_id' => $record->parent_id,
                'staff_id' => $record->staff_id,
                'visit_date' => $record->visit_date,
                'trimester' => $record->trimester,
                'bmi' => $record->bmi,
                'weight' => $record->weight,
                'height' => $record->height,
                'blood_sugar' => $record->blood_sugar,
                'blood_pressure' => $record->blood_pressure,
                'health_status' => $record->health_status,
                'fundal_height' => $record->fundal_height,
                'notes' => json_decode($record->notes),
            ];
        }


        return $resource;
    }





    public function getMaternalRecordsByMaternalId(
        int $maternalId,
        ?string $search = null,
        ?array $filters = null
    ): array {

        $parentId = Maternal::find($maternalId)->parent_id;
        $recordsQuery = MaternalRecord::query()
            ->where('parent_id', '=', $parentId);

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
                'maternal_id' => $record->maternal_id,
                'parent_id' => $record->parent_id,
                'age_recorded-at' => Calculator::calculateAgeInMonths(ParentM::find($record->parent_id)->date_of_birth, $record->visit_date),
                'staff_id' => $record->staff_id,
                'staff' => [
                    'id' => $record->staff_id,
                    'name' => User::find($record->staff_id)->name,
                    'role' => User::find($record->staff_id)->role,
                ],
                'visit_date' => $record->visit_date,
                'trimester' => $record->trimester,
                'bmi' => $record->bmi,
                'weight' => $record->weight,
                'glucose' => $record->glucose,
                'blood_sugar' => $record->blood_sugar,
                'hemoglobin' => $record->hemoglobin,
                'blood_pressure' => $record->blood_pressure,
                'health_status' => $record->health_status,
                'fetal_heart_rate' => $record->fetal_heart_rate,
                'fundal_height' => $record->fundal_height,
                'notes' => json_decode($record->notes),
            ];
        }


        $links = array_diff_key($results, ['items' => true]);


        return [$resource, $links];
    }



    public function validateMaternalHealthData(
        $visitDate,
        $bloodPressure,
        $weight,
        $hemoglobin,
        $glucose,
        $fetalHeartRate,
        $fundalHeight,
        bool $edit = false
    ): array {

        $errors = [];
        $prefix = $edit ? 'e_' : '';

       if(!$edit) {
        if (!$visitDate) {
            $errors["{$prefix}visit_date"] = 'Visit date is required.';
        } elseif (!strtotime($visitDate)) {
            $errors["{$prefix}visit_date"] = 'Invalid visit date.';
        } elseif ($visitDate > date('Y-m-d')) {
            $errors["{$prefix}visit_date"] = 'Visit date cannot be in the future.';
        }
       }

        if ($weight !== null) {
            if (!is_numeric($weight)) {
                $errors["{$prefix}weight"] = 'Weight must be numeric.';
            } elseif ($weight < 30 || $weight > 200) {
                $errors["{$prefix}weight"] = 'Weight must be between 30kg and 200kg.';
            }
        }

        if ($hemoglobin !== null) {
            if (!is_numeric($hemoglobin)) {
                $errors["{$prefix}hemoglobin"] = 'Hemoglobin must be numeric.';
            } elseif ($hemoglobin < 7 || $hemoglobin > 20) {
                $errors["{$prefix}hemoglobin"] = 'Hemoglobin must be between 7g/dL and 20g/dL.';
            }
        }

        if ($glucose !== null) {
            if (!is_numeric($glucose)) {
                $errors["{$prefix}glucose"] = 'Glucose must be numeric.';
            } elseif ($glucose < 50 || $glucose > 500) {
                $errors["{$prefix}glucose"] = 'Glucose must be between 50mg/dL and 500mg/dL.';
            }
        }

        if ($bloodPressure !== null) {
            if (!is_numeric($bloodPressure)) {
                $errors["{$prefix}blood_pressure"] = 'Blood pressure must be numeric.';
            } elseif ($bloodPressure < 50 || $bloodPressure > 250) {
                $errors["{$prefix}blood_pressure"] = 'Blood pressure must be between 50 and 250 mmHg.';
            }
        }

        if ($fetalHeartRate !== null) {
            if (!is_numeric($fetalHeartRate)) {
                $errors["{$prefix}fetal_heart_rate"] = 'Fetal heart rate must be numeric.';
            } elseif ($fetalHeartRate < 110 || $fetalHeartRate > 160) {
                $errors["{$prefix}fetal_heart_rate"] = 'Fetal heart rate must be between 110 and 160 bpm.';
            }
        }

        if ($fundalHeight !== null) {
            if (!is_numeric($fundalHeight)) {
                $errors["{$prefix}fundal_height"] = 'Fundal height must be numeric.';
            } elseif ($fundalHeight < 10 || $fundalHeight > 40) {
                $errors["{$prefix}fundal_height"] = 'Fundal height must be between 10cm and 40cm.';
            }
        }

        return $errors;
    }


    public function addHealthRecord(
        $maternalId,
        $staffId,
        $visitDate,
        $bloodPressure,
        $weight,
        $hemoglobin,
        $glucose,
        $fetalHeartRate,
        $fundalHeight,
        $notes
    ) {

        $parentId = Maternal::find($maternalId)->parent_id;

        $height = Maternal::find($maternalId)->height;

        $bmi = Calculator::calculateBMI($height, $weight);

        $lmp = Pregnancy::query()->where('maternal_id', '=', $maternalId)->first()->lmp;


        $gestationWeeks = Calculator::calculateGestationWeeks($lmp);

        $trimester = Calculator::calculateTrimester($gestationWeeks);


        $healthStatus = Calculator::calculateMaternalHealthStatus($hemoglobin, $glucose, $bloodPressure);

        $record = new MaternalRecord();

        $record->parent_id = $parentId;
        $record->staff_id = $staffId;
        $record->visit_date = $visitDate;
        $record->trimester = $trimester;
        $record->health_status = $healthStatus;
        $record->weight = $weight;
        $record->blood_pressure = $bloodPressure;
        $record->glucose = $glucose;
        $record->hemoglobin = $hemoglobin;
        $record->fetal_heart_rate = $fetalHeartRate;
        $record->fundal_height = $fundalHeight;

        $record->bmi = $bmi;
        $record->notes = $notes;


        $record->save();

        return $record;
    }

    public function editHealthRecord(
        $recordId,
        $staffId,
        $visitDate,
        $bloodPressure,
        $weight,
        $hemoglobin,
        $glucose,
        $fetalHeartRate,
        $fundalHeight
    ) {

        $record = MaternalRecord::find($recordId);

        if (!$record) {
            return "Record not found.";
        }

        $recordStaffId = $record->staff_id;

        $parentId = $record->parent_id;

        $maternalId = Maternal::query()->where('parent_id', '=', $parentId)->first()->id;


        $height = Maternal::find($maternalId)->height;

        $bmi = Calculator::calculateBMI($height, $weight);

        $lmp = Pregnancy::query()->where('maternal_id', '=', $maternalId)->first()->lmp;


        $gestationWeeks = Calculator::calculateGestationWeeks($lmp);

        $trimester = Calculator::calculateTrimester($gestationWeeks);


        $healthStatus = Calculator::calculateMaternalHealthStatus($hemoglobin, $glucose, $bloodPressure);


        $record->staff_id = $staffId;
        $record->visit_date = $visitDate;
        $record->trimester = $trimester;
        $record->health_status = $healthStatus;
        $record->weight = $weight;
        $record->blood_pressure = $bloodPressure;
        $record->glucose = $glucose;
        $record->hemoglobin = $hemoglobin;
        $record->fetal_heart_rate = $fetalHeartRate;
        $record->fundal_height = $fundalHeight;

        $record->bmi = $bmi;


        $record->save();

        if ($recordStaffId !== $staffId) {
            $this->notificationService->notify(
                $staffId,
                "Health record updated",
                "The health record of maternal M-00 " . $maternalId . " has been updated.",
                "maternal_record_updated",
                $record->maternal_id . "" . $record->maternal_id

            );
        }

        return null;
    }



    public function markAsInvalidRecord($recordId, $staffId)
    {
        $record = MaternalRecord::find($recordId);


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
                "The health record of maternal M-00 " . $record->maternal_id . " has been marked as invalid.",
                "maternal_record_updated",
                $record->maternal_id . "" . $record->maternal_id

            );
        }

        return null;
    }
}
