<?php

namespace App\Services;

use App\Models\Child;
use DateInterval;
use DateTimeImmutable;
use Exception;
use Library\Framework\Database\QueryBuilder;

class VaccinationSchedulerService
{
    public function createInitialRemindersForChild(int $childId): void
    {
        $this->syncRemindersForChild($childId, false);
    }

    public function refreshRemindersAfterVaccination(int $childId): void
    {
        $this->syncRemindersForChild($childId, true);
    }

    public function recalculateForChild(int $childId): void
    {
        $this->syncRemindersForChild($childId, true);
    }

    private function syncRemindersForChild(int $childId, bool $clearUnadministered): void
    {

        $child = Child::find($childId);

        $dobValue = $child ? (string)($child->date_of_birth ?? '') : '';
        $areaValue = $child ? ($child->area_id ?? null) : null;

        $hasChild = $child !== null;
        $hasDob = trim($dobValue) !== '';
        $hasArea = $areaValue !== null && (string)$areaValue !== '';

        if (!$hasChild || !$hasDob || !$hasArea) {
            return;
        }

        $areaId = (int)$child->area_id;

        try {
            $dob = new DateTimeImmutable($child->date_of_birth);
        } catch (Exception $e) {
            return;
        }

        $clinicRules = QueryBuilder::rawGet(
            "SELECT weekday, ordinal_week
             FROM area_clinic_schedules
             WHERE area_id = :area_id AND active = TRUE
             ORDER BY weekday ASC, ordinal_week ASC",
            [':area_id' => $areaId]
        );

        if (empty($clinicRules)) {
            $this->insertDefaultClinicRulesForArea($areaId);

            $clinicRules = QueryBuilder::rawGet(
                "SELECT weekday, ordinal_week
                 FROM area_clinic_schedules
                 WHERE area_id = :area_id AND active = TRUE
                 ORDER BY weekday ASC, ordinal_week ASC",
                [':area_id' => $areaId]
            );

            if (empty($clinicRules)) {
                return;
            }
        }

        $scheduleVaccines = QueryBuilder::rawGet(
            "SELECT sv.id, sv.vaccine_id, sv.dose_number, sv.min_age_days, sv.due_age_days, sv.min_age_gap_days
             FROM schedules s
             JOIN schedule_vaccines sv ON sv.schedule_id = s.id
             WHERE s.active = TRUE
             ORDER BY sv.vaccine_id ASC, sv.dose_number ASC"
        );

        if (empty($scheduleVaccines)) {
            return;
        }

        $givenRows = QueryBuilder::rawGet(
            "SELECT v.schedule_vaccine_id, v.administered_at, sv.vaccine_id, sv.dose_number
             FROM vaccinations v
             JOIN schedule_vaccines sv ON sv.id = v.schedule_vaccine_id
             WHERE v.child_id = :child_id",
            [':child_id' => $childId]
        );

        $givenScheduleVaccineIds = [];
        $givenByVaccineDose = [];

        foreach ($givenRows as $givenRow) {
            $svId = (int)$givenRow['schedule_vaccine_id'];
            $givenScheduleVaccineIds[$svId] = true;

            try {
                $administered = new DateTimeImmutable((string)$givenRow['administered_at']);
            } catch (Exception $e) {
                continue;
            }

            $vaccineId = (int)$givenRow['vaccine_id'];
            $doseNumber = (int)$givenRow['dose_number'];

            if (!isset($givenByVaccineDose[$vaccineId][$doseNumber]) || $administered > $givenByVaccineDose[$vaccineId][$doseNumber]) {
                $givenByVaccineDose[$vaccineId][$doseNumber] = $administered;
            }
        }

                if ($clearUnadministered) {
                    $deleted = QueryBuilder::rawExec(
                                "DELETE FROM vaccination_reminders vr
                                 WHERE vr.child_id = :child_id
                                     AND NOT EXISTS (
                                             SELECT 1
                                             FROM vaccinations v
                                             WHERE v.child_id = vr.child_id
                                                 AND v.schedule_vaccine_id = vr.schedule_vaccine_id
                                     )",
                                [':child_id' => $childId]
                        );
                }

        $today = new DateTimeImmutable('today');

        foreach ($scheduleVaccines as $scheduleVaccine) {
            $scheduleVaccineId = (int)$scheduleVaccine['id'];
            if (isset($givenScheduleVaccineIds[$scheduleVaccineId])) {
                continue;
            }

            $vaccineId = (int)$scheduleVaccine['vaccine_id'];
            $doseNumber = (int)$scheduleVaccine['dose_number'];
            $minAgeDays = (int)$scheduleVaccine['min_age_days'];
            $dueAgeDays = (int)$scheduleVaccine['due_age_days'];
            $minGapDays = (int)$scheduleVaccine['min_age_gap_days'];

            $earliest = $dob->add(new DateInterval("P{$minAgeDays}D"));
            $preferred = $dob->add(new DateInterval("P{$dueAgeDays}D"));

            $threshold = $earliest > $preferred ? $earliest : $preferred;

            $previousDose = $doseNumber - 1;
            if ($previousDose > 0 && isset($givenByVaccineDose[$vaccineId][$previousDose])) {
                $gapDate = $givenByVaccineDose[$vaccineId][$previousDose]->add(new DateInterval("P{$minGapDays}D"));
                if ($gapDate > $threshold) {
                    $threshold = $gapDate;
                }
            }

            if ($today > $threshold) {
                $threshold = $today;
            }

            $scheduledClinicDate = $this->findNextClinicDate($threshold, $clinicRules);
            if ($scheduledClinicDate === null) {
                continue;
            }

            QueryBuilder::rawExec(
                "INSERT INTO vaccination_reminders (child_id, schedule_vaccine_id, scheduled_date)
                 VALUES (:child_id, :schedule_vaccine_id, :scheduled_date)
                 ON CONFLICT (child_id, schedule_vaccine_id, scheduled_date) DO NOTHING",
                [
                    ':child_id' => $childId,
                    ':schedule_vaccine_id' => $scheduleVaccineId,
                    ':scheduled_date' => $scheduledClinicDate->format('Y-m-d'),
                ]
            );
        }
    }

    private function insertDefaultClinicRulesForArea(int $areaId): void
    {
        QueryBuilder::rawExec(
            "INSERT INTO area_clinic_schedules (area_id, weekday, ordinal_week, active)
             VALUES (:area_id, 2, 1, TRUE), (:area_id, 2, 3, TRUE)
             ON CONFLICT (area_id, weekday, ordinal_week) DO NOTHING",
            [':area_id' => $areaId]
        );
    }

    private function findNextClinicDate(DateTimeImmutable $threshold, array $clinicRules): ?DateTimeImmutable
    {
        $monthCursor = new DateTimeImmutable($threshold->format('Y-m-01'));

        for ($monthOffset = 0; $monthOffset < 24; $monthOffset++) {
            $candidates = [];

            foreach ($clinicRules as $rule) {
                $weekday = (int)$rule['weekday'];
                $ordinalWeek = (int)$rule['ordinal_week'];
                $candidate = $this->nthWeekdayOfMonth((int)$monthCursor->format('Y'), (int)$monthCursor->format('m'), $weekday, $ordinalWeek);
                if ($candidate !== null && $candidate >= $threshold) {
                    $candidates[] = $candidate;
                }
            }

            if (!empty($candidates)) {
                usort($candidates, fn(DateTimeImmutable $a, DateTimeImmutable $b) => $a <=> $b);
                return $candidates[0];
            }

            $monthCursor = $monthCursor->modify('first day of next month');
        }

        return null;
    }

    private function nthWeekdayOfMonth(int $year, int $month, int $weekday, int $ordinalWeek): ?DateTimeImmutable
    {
        if ($weekday < 1 || $weekday > 7 || $ordinalWeek < 1 || $ordinalWeek > 5) {
            return null;
        }

        $firstDay = new DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));
        $firstWeekday = (int)$firstDay->format('N');

        $deltaDays = ($weekday - $firstWeekday + 7) % 7;
        $candidate = $firstDay->modify('+' . $deltaDays . ' days')->modify('+' . (($ordinalWeek - 1) * 7) . ' days');

        if ((int)$candidate->format('m') !== $month) {
            return null;
        }

        return $candidate;
    }
}
