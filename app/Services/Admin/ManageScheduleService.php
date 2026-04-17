<?php

namespace App\Services\Admin;

use App\Helpers\Validator;
use App\Models\ScheduledVaccine;
use App\Models\Vaccine;

class ManageScheduleService
{
    public function getScheduleVaccineData(int $id, string $search)
    {
        # NOTE: implement search later
        $scheduledVaccines = ScheduledVaccine::query()
            ->join("vaccines", "vaccines.id", "=", "schedule_vaccines.vaccine_id")
            ->where("schedule_vaccines.schedule_id", "=", $id)
            ->whereGroup(function ($query) use ($search) {
                $query->where("vaccines.code", "ILIKE", "{$search}%")
                    ->orWhere("vaccines.name", "ILIKE", "{$search}%");
            })
            ->orderBy("id", "ASC")
            ->paginate(10)
            ->toArray();

        $resource = [];

        $vaccines = Vaccine::all();

        foreach ($scheduledVaccines['items'] as $scheduledVaccine) {
            $vaccine = $scheduledVaccine->getVaccine();
            $schedule = $scheduledVaccine->getSchedule();

            // track vaccine index to remove
            $vaccineIndexToRemove = [];
            foreach ($vaccines as $key => $existingVaccine) {
                if ($vaccine->id == $existingVaccine->id) {
                    $vaccineIndexToRemove[] = $key;
                }
            }

            // unset tracked vaccine index from array
            foreach ($vaccineIndexToRemove as $vaccineIndex) {
                unset($vaccines[$vaccineIndex]);
            }

            // remove gaps from array
            array_values($vaccines);

            $resource[] = [
                "id" => $scheduledVaccine->id,
                "vaccine" => [
                    "name" => $vaccine->name,
                    "code" => $vaccine->code,
                ],
                "schedule" => [
                    "id" => $schedule->id,
                    "name" => $schedule->name
                ],
                "dose_number" => $scheduledVaccine->dose_number,
                "min_age_days" => $scheduledVaccine->min_age_days,
                "due_age_days" => $scheduledVaccine->due_age_days,
                "min_age_gap_days" => $scheduledVaccine->min_age_gap_days,
                "additional_information" => $scheduledVaccine->additional_information
            ];
        }

        $links = array_diff_key($scheduledVaccines, ['items' => true]);

        return [$resource, $vaccines, $links];
    }

    private function validateVaccine(int $vaccine_id, int $schedule_id)
    {
        $error = null;
        $vaccine = Vaccine::find($vaccine_id);

        if (!Validator::validateFieldExistence($vaccine_id)) {
            $error = "Vaccine selection is required.";
            return $error;
        }

        if (!$vaccine) {
            $error = "Selected vaccine does not exist.";
            return $error;
        }

        $schedule = ScheduledVaccine::query()
            ->where("vaccine_id", "=", $vaccine_id)
            ->where("schedule_id", "=", $schedule_id)
            ->first();

        if ($schedule) {
            $error = "Selected vaccine is already added to this schedule.";
            return $error;
        }

        return $error;
    }

    private function validateDoseNumber(int $dose_number)
    {
        $error = null;

        if (!Validator::validateFieldExistence($dose_number)) {
            $error = "Dose number is required.";
            return $error;
        }

        if ($dose_number <= 0) {
            $error = "Dose number must be a positive integer.";
            return $error;
        }

        return $error;
    }

    private function validateAgeDays(int $age_days, string $fieldName)
    {
        $error = null;

        if (!Validator::validateFieldExistence($age_days)) {
            $error = "{$fieldName} is required.";
            return $error;
        }

        if ($age_days < 0) {
            $error = "{$fieldName} cannot be negative.";
            return $error;
        }

        return $error;
    }

    private function validateAdditionalInformation(string $additional_information)
    {
        $error = null;

        if (!Validator::validateFieldMaxLength($additional_information, 500)) {
            $error = "Additional information cannot exceed 500 characters.";
            return $error;
        }

        return $error;
    }

    public function validateAddScheduleVaccineData(array $data, int $schedule_id, bool $edit = false)
    {
        $errors = [];
        $prefix = $edit ? "e_" : "";

        if (!$edit) {
            $vaccineError = $this->validateVaccine($data[$prefix . 'vaccine'], $schedule_id);
            if ($vaccineError) {
                $errors[$prefix . 'vaccine'] = $vaccineError;
            }
        }

        $doseNumberError = $this->validateDoseNumber($data[$prefix . 'dose_number']);
        if ($doseNumberError) {
            $errors[$prefix . 'dose_number'] = $doseNumberError;
        }

        $minAgeDaysError = $this->validateAgeDays($data[$prefix . 'min_age_days'], "Minimum age days");
        if ($minAgeDaysError) {
            $errors[$prefix . 'min_age_days'] = $minAgeDaysError;
        }

        $dueAgeDaysError = $this->validateAgeDays($data[$prefix . 'due_age_days'], "Due age days");
        if ($dueAgeDaysError) {
            $errors[$prefix . 'due_age_days'] = $dueAgeDaysError;
        }

        $minAgeGapDaysError = $this->validateAgeDays($data[$prefix . 'min_age_gap_days'], "Minimum age gap days");
        if ($minAgeGapDaysError) {
            $errors[$prefix . 'min_age_gap_days'] = $minAgeGapDaysError;
        }

        $additionalInfoError = $this->validateAdditionalInformation($data[$prefix . 'additional_information'] ?? '');
        if ($additionalInfoError) {
            $errors[$prefix . 'additional_information'] = $additionalInfoError;
        }

        return $errors;
    }
}