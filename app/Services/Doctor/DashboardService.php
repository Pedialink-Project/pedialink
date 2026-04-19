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