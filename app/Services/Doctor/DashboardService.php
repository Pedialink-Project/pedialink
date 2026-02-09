<?php

namespace App\Services\Doctor;

use App\Models\Child;
use App\Models\ParentM;
use App\Models\ChildAccessRequest;
use App\Models\ChildRecord;
use App\Models\MaternalRecord;
use DateTime;

class DashboardService
{
    /* =========================
       CHILD DASHBOARD
    ========================= */

    public function getChildHealthStatusCounts(int $doctorId): array
    {
        $chartData = $this->emptyChildChartData();

        // 1️⃣ Get accepted child IDs for this doctor
        $childIds = ChildAccessRequest::query()
            ->where('staff_id', '=', $doctorId)
            ->where('accepted', '=', true)
            ->pluck('child_id');

        if (empty($childIds)) {
            return $chartData;
        }

        // 2️⃣ Loop each child (latest record ONLY)
        foreach ($childIds as $childId) {

            $child = Child::find($childId);
            if (!$child || !$child->date_of_birth) {
                continue;
            }

            $latestRecord = ChildRecord::query()
                ->where('child_id', '=', $childId)
                ->orderBy('visit_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->first();

            if (!$latestRecord || !$latestRecord->health_status) {
                continue;
            }

            $ageYears = $this->calculateAgeYears($child->date_of_birth);
            $ageGroup = $this->getChildAgeGroup($ageYears);
            $status   = strtolower($latestRecord->health_status);

            if (isset($chartData[$ageGroup][$status])) {
                $chartData[$ageGroup][$status]++;
            }
        }

        return $chartData;
    }

    private function getChildAgeGroup(float $ageYears): string
    {
        return match (true) {
            $ageYears < 0.5 => '0–0.5',
            $ageYears < 1   => '0.5–1',
            $ageYears < 2   => '1–2',
            $ageYears < 5   => '2–5',
            default         => '5+',
        };
    }

    private function emptyChildChartData(): array
    {
        return [
            '0–0.5' => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '0.5–1' => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '1–2'   => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '2–5'   => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '5+'    => ['good' => 0, 'moderate' => 0, 'critical' => 0],
        ];
    }

    /* =========================
       MATERNAL DASHBOARD
    ========================= */

    public function getMaternalHealthStatusCounts(): array
    {
        $chartData = $this->emptyMaternalChartData();

        // Get records ordered latest first
        $records = MaternalRecord::query()
            ->orderBy('visit_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        $seenParents = [];

        foreach ($records as $record) {

            if (!$record->parent_id || isset($seenParents[$record->parent_id])) {
                continue;
            }

            $seenParents[$record->parent_id] = true;

            $parent = ParentM::find($record->parent_id);
            if (!$parent || !$parent->date_of_birth || !$record->health_status) {
                continue;
            }

            $age    = (int)$this->calculateAgeYears($parent->date_of_birth);
            $group  = $this->getMaternalAgeGroup($age);
            $status = strtolower($record->health_status);

            if (isset($chartData[$group][$status])) {
                $chartData[$group][$status]++;
            }
        }

        return $chartData;
    }

    private function getMaternalAgeGroup(int $age): string
    {
        return match (true) {
            $age >= 18 && $age < 25 => '18 - 25',
            $age < 30               => '25 - 30',
            $age < 40               => '30 - 40',
            $age < 50               => '40 - 50',
            default                 => '50+',
        };
    }

    private function emptyMaternalChartData(): array
    {
        return [
            '18 - 25' => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '25 - 30' => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '30 - 40' => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '40 - 50' => ['good' => 0, 'moderate' => 0, 'critical' => 0],
            '50+'     => ['good' => 0, 'moderate' => 0, 'critical' => 0],
        ];
    }

    /* =========================
       SHARED HELPERS
    ========================= */

    private function calculateAgeYears(string $dob): float
    {
        $dobDt = new DateTime($dob);
        $now   = new DateTime();
        $diff  = $now->diff($dobDt);

        return $diff->y + ($diff->m / 12);
    }
}
