<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;
use Library\Framework\Database\QueryBuilder;
use App\Models\Child;
use App\Models\ChildRecord;
use App\Services\ChildRecordService;

class GrowthMonitorController
{
    public function index(Request $request)
    {
        return $this->renderGrowthMonitoring();
    }

    public function childGrowthIndex(Request $request, int $id)
    {
        return $this->renderGrowthMonitoring($id);
    }

    private function renderGrowthMonitoring(?int $selectedChildId = null)
    {
        $phmId = auth()->id();

        $childRecordService = new ChildRecordService();
        
        // Get only active (non-archived) children
        $childRows = QueryBuilder::rawGet(
            "SELECT * FROM children WHERE phm_id = :phm_id AND archived_at IS NULL ORDER BY id DESC",
            [':phm_id' => $phmId]
        );
        
        $children = [];
        foreach ($childRows as $row) {
            $child = new Child();
            $child->hydrate($row);
            $children[] = $child;
        }
        $childrenById = [];
        $childrenList = [];
        $selectedChildName = null;

        foreach ($children as $child) {
            $childrenById[$child->id] = $child->name;
        }

        $childIds = array_keys($childrenById);
        
        // Only query records if there are children
        $records = [];
        if (!empty($childIds)) {
            $records = ChildRecord::query()
                ->whereIn('child_id', $childIds)
                ->orderBy('visit_date', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get();
        }

        $recordsByChildId = [];
        foreach ($records as $record) {
            $recordsByChildId[$record->child_id][] = $record;
        }

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        $monthKeys = range(1, $currentMonth);
        $dateLabels = array_map(function ($monthNumber) {
            return date('M', mktime(0, 0, 0, $monthNumber, 1));
        }, $monthKeys);

        $bmiDatasets = [];
        $heightDatasets = [];
        $weightDatasets = [];

        $childIds = array_unique(array_merge(array_keys($childrenById), array_keys($recordsByChildId)));

        foreach ($childIds as $childId) {
            $childName = $childrenById[$childId] ?? ('Child #' . $childId);

            $childrenList[] = [
                'id' => $childId,
                'name' => $childName,
            ];

            if ($selectedChildId !== null && (int) $childId === (int) $selectedChildId) {
                $selectedChildName = $childName;
            }

            $childRecords = $recordsByChildId[$childId] ?? [];

            $bmiByMonth = [];
            $heightByMonth = [];
            $weightByMonth = [];

            foreach ($childRecords as $record) {
                // Use visit_date as the key for individual records
                $rawDate = $record->visit_date ?: ($record->created_at ?? null);
                if (empty($rawDate)) {
                    continue;
                }

                $timestamp = strtotime($rawDate);
                if ($timestamp === false) {
                    continue;
                }

                $recordYear = (int) date('Y', $timestamp);
                $recordMonth = (int) date('n', $timestamp);

                // Only include records in the current year up to the current month.
                if ($recordYear !== $currentYear || $recordMonth > $currentMonth) {
                    continue;
                }

                $heightValue = $record->height !== null ? (float) $record->height : null;
                $weightValue = $record->weight !== null ? (float) $record->weight : null;
                $bmiValue = $record->bmi !== null ? (float) $record->bmi : null;

                if ($bmiValue === null && $heightValue !== null && $weightValue !== null) {
                    $bmiValue = $childRecordService->calculateBMI($weightValue, $heightValue);
                }

                // Store the last value for this month (if multiple records in same month)
                $bmiByMonth[$recordMonth] = $bmiValue;
                $heightByMonth[$recordMonth] = $heightValue;
                $weightByMonth[$recordMonth] = $weightValue;
            }

            $bmiDatasets[] = [
                'childId' => $childId,
                'label' => $childName,
                'dataByMonth' => $bmiByMonth,
            ];
            $heightDatasets[] = [
                'childId' => $childId,
                'label' => $childName,
                'dataByMonth' => $heightByMonth,
            ];
            $weightDatasets[] = [
                'childId' => $childId,
                'label' => $childName,
                'dataByMonth' => $weightByMonth,
            ];
        }

        $bmiChartDatasets = $this->buildChartDatasets($bmiDatasets, $monthKeys);
        $heightChartDatasets = $this->buildChartDatasets($heightDatasets, $monthKeys);
        $weightChartDatasets = $this->buildChartDatasets($weightDatasets, $monthKeys);

        // Debug output
        error_log("=== GROWTH MONITOR DEBUG ===");
        error_log("PHM ID: " . $phmId);
        error_log("Children Count: " . count($children));
        error_log("Child IDs: " . json_encode(array_keys($childrenById)));
        error_log("Records Count: " . count($records));
        error_log("Date Labels: " . json_encode($dateLabels));
        error_log("BMI Datasets Count: " . count($bmiChartDatasets));
        if (!empty($bmiChartDatasets)) {
            error_log("First BMI Dataset: " . json_encode($bmiChartDatasets[0]));
        }
        error_log("=== END DEBUG ===");

        return view("phm/growthmonitoring", [
            'children' => $childrenList,
            'dateLabels' => $dateLabels,
            'bmiChartDatasets' => $bmiChartDatasets,
            'heightChartDatasets' => $heightChartDatasets,
            'weightChartDatasets' => $weightChartDatasets,
        ]);
    }

    private function buildChartDatasets(array $datasets, array $monthKeys): array
    {
        $colors = [
            'rgba(59,130,246,1)',
            'rgba(236,72,153,1)',
            'rgba(34,197,94,1)',
            'rgba(168,85,247,1)',
            'rgba(239,68,68,1)',
            'rgba(14,165,233,1)',
            'rgba(245,158,11,1)',
        ];

        $chartDatasets = [];
        $colorIndex = 0;

        foreach ($datasets as $dataset) {
            $data = [];
            foreach ($monthKeys as $monthKey) {
                $data[] = $dataset['dataByMonth'][$monthKey] ?? null;
            }

            $chartDatasets[] = [
                'childId' => $dataset['childId'],
                'label' => $dataset['label'],
                'data' => $data,
                'color' => $colors[$colorIndex % count($colors)],
            ];

            $colorIndex++;
        }

        return $chartDatasets;
    }
}