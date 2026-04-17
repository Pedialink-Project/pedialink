<?php

namespace App\Services\Doctor;

use App\Helpers\Calculator;
use App\Models\Appointment;
use App\Models\Child;
use App\Models\ChildRecord;
use App\Models\Doctor;
use App\Models\Maternal;
use App\Models\MaternalRecord;
use Library\Framework\Database\QueryBuilder;

class DashboardService
{
    public function getPatientsCount()
    {
        return count(Maternal::query()->get()) + count(Child::query()->get());
    }

    public function getAppointmentsCount()
    {
        $appointments = Appointment::query()
            ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id')
            ->where("appointment_slots.doctor_id", "=", auth()->user()->id)
            ->whereIn("appointments.status", ["confirmed", "pending"])
            ->get();
        return count($appointments);
    }

    public function getUrgentCasesCount()
    {
        // Maternal urgent cases: antenatal mothers with latest record showing critical health_status
        $maternalSql = "
            SELECT COUNT(*) as count
            FROM (
                SELECT DISTINCT ON (mr.parent_id) mr.parent_id, mr.health_status
                FROM maternal_records mr
                JOIN maternal m ON m.parent_id = mr.parent_id
                WHERE m.type = 'antenatal'
                ORDER BY mr.parent_id, mr.created_at DESC
            ) AS latest
            WHERE latest.health_status = 'critical'
        ";

        $maternalResult = QueryBuilder::rawGet($maternalSql, []);
        $maternalCount = (int) ($maternalResult[0]['count'] ?? 0);

        // Children urgent cases: children with latest record showing critical health_status
        $childSql = "
            SELECT COUNT(*) as count
            FROM (
                SELECT DISTINCT ON (cr.child_id) cr.child_id, cr.health_status
                FROM child_records cr
                ORDER BY cr.child_id, cr.created_at DESC
            ) AS latest
            WHERE latest.health_status = 'critical'
        ";

        $childResult = QueryBuilder::rawGet($childSql, []);
        $childCount = (int) ($childResult[0]['count'] ?? 0);

        return $maternalCount + $childCount;
    }

    public function upcomingAppointments()
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $doctor = Doctor::find($currentUser->id);

            $appointments = Appointment::query()
                ->join('appointment_slots', 'appointments.slot_id', '=', 'appointment_slots.id')
                ->where('appointment_slots.doctor_id', '=', $doctor->id)
                ->whereIn("appointments.status", ["confirmed", "pending"])
                ->orderBy("id", "ASC")
                ->paginate(3)
                ->toArray();

            $resource = [];

            foreach ($appointments['items'] as $appointment) {
                $child = $appointment->getChild();
                $slot = $appointment->getSlot();
                $doctor = $slot ? $slot->getDoctor() : null;
                $resource[] = [
                    "id" => $appointment->id,
                    "child_name" => $child ? $child->name : null,
                    "slot_date" => $slot ? $slot->slot_date : null,
                    "start_time" => $slot ? Calculator::formatTimeToAmPm($slot->start_time) : null,
                    "end_time" => $slot ? Calculator::formatTimeToAmPm($slot->end_time) : null,
                    "status" => $appointment->status,
                    "reason" => $appointment->reason,
                    "doctor_name" => $doctor ? $doctor->getUser()->name : null,
                ];
            }

            return $resource;
        }
    }

    public function getWeeklyAppointmentData()
    {
        $userId = auth()->user()->id;

        // Get the current week's Monday and Friday
        $today = new \DateTimeImmutable('today');
        $dayOfWeek = (int)$today->format('N'); // 1 = Monday, 7 = Sunday
        $monday = $today->modify('-' . ($dayOfWeek - 1) . ' days')->format('Y-m-d');
        $friday = $today->modify('+' . (5 - $dayOfWeek) . ' days')->format('Y-m-d');

        // SQL: join appointments with slots, filter by doctor_id, group by day of week (1=Mon..5=Fri)
        // Count attended as completed, no-show and cancelled as cancelled
        $sql = "
        SELECT
            EXTRACT(DOW FROM s.slot_date)::int AS dow,
            SUM(CASE WHEN a.status = 'attended' THEN 1 ELSE 0 END)::int AS completed,
            SUM(CASE WHEN a.status IN ('no-show', 'cancelled') THEN 1 ELSE 0 END)::int AS cancelled,
            COUNT(*)::int AS booked
        FROM appointments a
        JOIN appointment_slots s ON a.slot_id = s.id
        WHERE s.slot_date >= :monday AND s.slot_date <= :friday
        AND s.doctor_id = :doctor_id
        GROUP BY dow
        ORDER BY dow
        ";

        $rows = QueryBuilder::rawGet($sql, ['monday' => $monday, 'friday' => $friday, 'doctor_id' => $userId]);

        // Initialize 5-day buckets (index 0 -> Monday, index 4 -> Friday)
        $booked = array_fill(0, 5, 0);
        $completed = array_fill(0, 5, 0);
        $cancelled = array_fill(0, 5, 0);

        if (!empty($rows) && is_array($rows)) {
            foreach ($rows as $r) {
                $dow = null;
                $comp = 0;
                $canc = 0;
                $book = 0;

                if (is_array($r)) {
                    // PostgreSQL DOW: 0=Sunday, 1=Monday, ..., 5=Friday, 6=Saturday
                    $dow = isset($r['dow']) ? (int)$r['dow'] : null;
                    $comp = isset($r['completed']) ? (int)$r['completed'] : 0;
                    $canc = isset($r['cancelled']) ? (int)$r['cancelled'] : 0;
                    $book = isset($r['booked']) ? (int)$r['booked'] : 0;
                }

                // Map DOW 1-5 (Mon-Fri) to array index 0-4
                if ($dow !== null && $dow >= 1 && $dow <= 5) {
                    $idx = $dow - 1;
                    $booked[$idx] = $book;
                    $completed[$idx] = $comp;
                    $cancelled[$idx] = $canc;
                }
            }
        }

        return [
            'booked' => $booked,
            'completed' => $completed,
            'cancelled' => $cancelled,
        ];
    }

    public function getLatestHealthRecords()
    {
        $maternalRecord = MaternalRecord::query()
            ->orderBy("created_at", "DESC")
            ->limit(2)
            ->get();

        $maternalHealthRecord = [];
        foreach ($maternalRecord as $record) {
            $parent = $record->getParent();
            $user = $parent ? $parent->getUser() : null;
            $maternal = Maternal::query()
                ->where("parent_id", "=", $record->parent_id)
                ->first();
            $maternalHealthRecord[] = [
                "id" => $record->id,
                "patient" => [
                    "id" => $maternal ? $maternal->id : null,
                    "name" => $user ? $user->name : null,
                ],
                "staff" => [
                    "id" => $record->staff_id,
                    "name" => $record->getStaff() ? $record->getStaff()->getUser()->name : null,
                ],
                "type" => "Mother",
                "health_status" => $record->health_status,
            ];
        }

        $childRecord = ChildRecord::query()
            ->orderBy("created_at", "DESC")
            ->limit(2)
            ->get();

        $childHealthRecord = [];
        foreach ($childRecord as $record) {
            $child = $record->getChild();
            $childHealthRecord[] = [
                "id" => $record->id,
                "patient" => [
                    "id" => $child ? $child->id : null,
                    "name" => $child ? $child->name : null,
                ],
                "staff" => [
                    "id" => $record->staff_id,
                    "name" => $record->getStaff() ? $record->getStaff()->getUser()->name : null,
                ],
                "type" => "Child",
                "health_status" => $record->health_status,
            ];
        }

        return array_merge($maternalHealthRecord, $childHealthRecord);
    }

    public function getPatientRiskOverviewData(): array
    {
        // Children risk data: Get latest child_record per child grouped by age
        $childSql = "
            SELECT
                CASE
                    WHEN EXTRACT(YEAR FROM AGE(c.date_of_birth)) >= 0 AND EXTRACT(YEAR FROM AGE(c.date_of_birth)) < 1 THEN '0 - 1'
                    WHEN EXTRACT(YEAR FROM AGE(c.date_of_birth)) >= 1 AND EXTRACT(YEAR FROM AGE(c.date_of_birth)) < 2 THEN '1 - 2'
                    WHEN EXTRACT(YEAR FROM AGE(c.date_of_birth)) >= 2 AND EXTRACT(YEAR FROM AGE(c.date_of_birth)) < 3 THEN '2 - 3'
                    WHEN EXTRACT(YEAR FROM AGE(c.date_of_birth)) >= 3 AND EXTRACT(YEAR FROM AGE(c.date_of_birth)) < 4 THEN '3 - 4'
                    ELSE '4+'
                END as age_group,
                latest.health_status,
                COUNT(*) as count
            FROM (
                SELECT DISTINCT ON (cr.child_id) cr.child_id, cr.health_status
                FROM child_records cr
                ORDER BY cr.child_id, cr.created_at DESC
            ) AS latest
            JOIN children c ON c.id = latest.child_id
            GROUP BY age_group, latest.health_status
            ORDER BY age_group, latest.health_status
        ";

        $childResults = QueryBuilder::rawGet($childSql, []);

        // Initialize children data structure
        $childLabels = ['0 - 1', '1 - 2', '2 - 3', '3 - 4', '4+'];
        $childData = [
            'labels' => $childLabels,
            'good' => array_fill(0, 5, 0),
            'at_risk' => array_fill(0, 5, 0),
            'critical' => array_fill(0, 5, 0),
        ];

        $childAgeGroupIndex = array_flip($childLabels);

        foreach ($childResults as $row) {
            $ageGroup = $row['age_group'];
            $healthStatus = $row['health_status'];
            $count = (int) $row['count'];

            if (isset($childAgeGroupIndex[$ageGroup]) && isset($childData[$healthStatus])) {
                $index = $childAgeGroupIndex[$ageGroup];
                $childData[$healthStatus][$index] = $count;
            }
        }

        // Maternal risk data: Similar to PHM dashboard
        $maternalSql = "
            SELECT
                CASE
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 18 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 25 THEN '18 - 25'
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 25 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 30 THEN '25 - 30'
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 30 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 40 THEN '30 - 40'
                    WHEN EXTRACT(YEAR FROM AGE(p.date_of_birth)) >= 40 AND EXTRACT(YEAR FROM AGE(p.date_of_birth)) < 50 THEN '40 - 50'
                    ELSE '50+'
                END as age_group,
                mr.health_status,
                COUNT(*) as count
            FROM maternal_records mr
            JOIN parents p ON mr.parent_id = p.id
            JOIN maternal m ON m.parent_id = p.id
            WHERE m.type = 'antenatal'
            GROUP BY age_group, mr.health_status
            ORDER BY age_group, mr.health_status
        ";

        $maternalResults = QueryBuilder::rawGet($maternalSql, []);

        // Initialize maternal data structure
        $maternalLabels = ['18 - 25', '25 - 30', '30 - 40', '40 - 50', '50+'];
        $maternalData = [
            'labels' => $maternalLabels,
            'good' => array_fill(0, 5, 0),
            'at_risk' => array_fill(0, 5, 0),
            'critical' => array_fill(0, 5, 0),
        ];

        $maternalAgeGroupIndex = array_flip($maternalLabels);

        foreach ($maternalResults as $row) {
            $ageGroup = $row['age_group'];
            $healthStatus = $row['health_status'];
            $count = (int) $row['count'];

            if (isset($maternalAgeGroupIndex[$ageGroup]) && isset($maternalData[$healthStatus])) {
                $index = $maternalAgeGroupIndex[$ageGroup];
                $maternalData[$healthStatus][$index] = $count;
            }
        }

        return [
            'children' => $childData,
            'maternal' => $maternalData,
        ];
    }
}