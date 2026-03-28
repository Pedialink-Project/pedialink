<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;
use App\Models\ChildRecord;
use App\Models\Child;
use App\Services\ChildRecordService;

class ChildHealthController
{
    private ChildRecordService $childRecordService;

    public function __construct()
    {
        $this->childRecordService = new ChildRecordService();
    }
    public function index(Request $request, int $id)
    {
        $child = Child::find($id);
        if (!$child) {
            return redirect(route('phm.child.profiles'))
                ->withMessage(
                    "Child not found",
                    "Error",
                    "error"
                );
        }

        $records = ChildRecord::query()
            ->where('child_id', '=', $id)
            ->orderBy('visit_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        $items = [];
        foreach ($records as $record) {
            $bmi = ($record->height > 0) ? round($record->weight / pow($record->height / 100, 2), 2) : 0;
            $items[] = [
                'visit_date' => $record->visit_date,
                'height' => $record->height,
                'weight' => $record->weight,
                'head_circumference' => $record->head_circumference,
                'health_status' => $record->health_status ?? 'Good',
                'bmi' => $bmi,
                'Recorded at' => $record->visit_date,
                'notes' => $record->notes,
                // 'Height' => $record->height,
                // 'Weight' => $record->weight,
                // 'Head Circumference' => $record->head_circumference,
                // 'Health Status' => $record->health_status ?? 'Good',
            ];
        }

        return view("phm/childhealth", [
            "id" => $id,
            "is_archived" => $child->archived_at !== null,
            "items" => $items,
        ]);
    }

    public function createHealthRecord(Request $request, int $id)
    {
        // Verify child exists
        $child = Child::find($id);
        if (!$child) {
            return redirect(route('phm.child.profiles'))
                ->withErrors(['error' => 'Child not found']);
        }

        if ($child->archived_at !== null) {
            return redirect(route('phm.child.health.records', ['id' => $id]))
                ->withMessage(
                    "Cannot add health records to an archived child profile. Restore the profile to add new records.",
                    "Error",
                    "error"
                );
        }

        $visitDate = $request->input('visit_date');
        $height = $request->input('height');
        $weight = $request->input('weight');
        $headCircumference = $request->input('head_circumference');
        $notes = $request->input('notes') ?? '';

        // Calculate age in months
        $ageInMonths = $this->childRecordService->calculateAgeInMonths($child->date_of_birth);

        // Validate using ChildRecordService
        $errors = $this->childRecordService->validateChildRecordData(
            $visitDate,
            $height,
            $headCircumference,
            null, // bloodSugar - not used for children
            $weight,
            null, // trimester - not used for children
            null, // healthStatus - auto-calculated
            $notes,
            false,
            $ageInMonths
        );

        if (count($errors) > 0) {
            return redirect(route('phm.child.health.records', ['id' => $id]))
                ->withErrors($errors)
                ->withInput([
                    'visit_date' => $visitDate,
                    'height' => $height,
                    'weight' => $weight,
                    'head_circumference' => $headCircumference,
                    'notes' => $notes,
                ])
                ->with('create', true);
        }

        // Create the record using ChildRecordService (which auto-calculates health status)
        $this->childRecordService->createChildRecord(
            $id,
            $visitDate,
            $height,
            $headCircumference,
            $weight,
            null, // healthStatus - will be auto-calculated
            $notes,
            $ageInMonths
        );

        return redirect(route('phm.child.health.records', ['id' => $id]))
            ->withMessage(
                "Health record was successfully added",
                "Success",
                "success"
            );
    }

    public function vaccinationIndex(Request $request, int $id)
    {
        return view("phm/vaccinationrecords", [
            "id" => $id,
        ]);
    }
}