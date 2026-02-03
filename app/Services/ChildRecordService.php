<?php

namespace App\Services;

use App\Models\ChildRecord;
use App\Models\Staff;
use App\Models\User;

class ChildRecordService
{

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
                'health_status'=> "good",
                'notes' => $record->notes,
                'created_at' => $record->created_at,

                'staff' => $staffResource
            ];
        }

        return $resource;
    }
}
