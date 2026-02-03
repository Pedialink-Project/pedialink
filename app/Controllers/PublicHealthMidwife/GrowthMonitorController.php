<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;
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
        $children = Child::query()->where('phm_id', '=', $phmId)->get();
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

        $allDates = [];
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

            $bmiByDate = [];
            $heightByDate = [];
            $weightByDate = [];

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

                // Use the full date as key (not aggregated by month)
                $dateKey = date('Y-m-d', $timestamp);
                $allDates[$dateKey] = true;

                $heightValue = $record->height !== null ? (float) $record->height : null;
                $weightValue = $record->weight !== null ? (float) $record->weight : null;
                $bmiValue = $record->bmi !== null ? (float) $record->bmi : null;

                if ($bmiValue === null && $heightValue !== null && $weightValue !== null) {
                    $bmiValue = $childRecordService->calculateBMI($weightValue, $heightValue);
                }

                // Store the last value for this date (if multiple records on same date)
                $bmiByDate[$dateKey] = $bmiValue;
                $heightByDate[$dateKey] = $heightValue;
                $weightByDate[$dateKey] = $weightValue;
            }

            $bmiDatasets[] = [
                'childId' => $childId,
                'label' => $childName,
                'dataByDate' => $bmiByDate,
            ];
            $heightDatasets[] = [
                'childId' => $childId,
                'label' => $childName,
                'dataByDate' => $heightByDate,
            ];
            $weightDatasets[] = [
                'childId' => $childId,
                'label' => $childName,
                'dataByDate' => $weightByDate,
            ];
        }

        $dateLabels = array_keys($allDates);
        usort($dateLabels, function ($a, $b) {
            return strtotime($a) <=> strtotime($b);
        });

        $bmiChartDatasets = $this->buildChartDatasets($bmiDatasets, $dateLabels);
        $heightChartDatasets = $this->buildChartDatasets($heightDatasets, $dateLabels);
        $weightChartDatasets = $this->buildChartDatasets($weightDatasets, $dateLabels);

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

    private function buildChartDatasets(array $datasets, array $dateLabels): array
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
            foreach ($dateLabels as $dateLabel) {
                $data[] = $dataset['dataByDate'][$dateLabel] ?? null;
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