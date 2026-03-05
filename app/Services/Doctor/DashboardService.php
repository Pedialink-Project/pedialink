<?php

namespace App\Services\Doctor;

use App\Models\Appointment;
use App\Models\ChildAccessRequest;
use App\Models\Maternal;
use App\Models\MaternalAccessRequest;
use Library\Framework\Database\QueryBuilder;

class DashboardService
{
    public function getPatientsCount()
    {
        $maternalAccessRequest = MaternalAccessRequest::query()
            ->where("staff_id", "=", auth()->user()->id)
            ->where("accepted", "=", 1)
            ->get();

        $childAccessRequest = ChildAccessRequest::query()
            ->where("staff_id", "=", auth()->user()->id)
            ->where("accepted", "=", 1)
            ->get();

        return count($maternalAccessRequest) + count($childAccessRequest);
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
        $userId = auth()->user()->id;

        // Maternal urgent cases: antenatal mothers with latest record showing critical health_status
        $maternalSql = "
            SELECT COUNT(*) as count
            FROM (
                SELECT DISTINCT ON (mr.parent_id) mr.parent_id, mr.health_status
                FROM maternal_records mr
                JOIN maternal m ON m.parent_id = mr.parent_id
                JOIN maternal_access_requests mar ON mar.maternal_id = mr.parent_id
                WHERE m.type = 'antenatal'
                AND mar.accepted = true
                AND mar.staff_id = :staff_id
                ORDER BY mr.parent_id, mr.created_at DESC
            ) AS latest
            WHERE latest.health_status = 'critical'
        ";

        $maternalResult = QueryBuilder::rawGet($maternalSql, ['staff_id' => $userId]);
        $maternalCount = (int) ($maternalResult[0]['count'] ?? 0);

        // Children urgent cases: children with latest record showing critical health_status
        $childSql = "
            SELECT COUNT(*) as count
            FROM (
                SELECT DISTINCT ON (cr.child_id) cr.child_id, cr.health_status
                FROM child_records cr
                JOIN children_access_requests car ON car.child_id = cr.child_id
                WHERE car.accepted = true
                AND car.staff_id = :staff_id
                ORDER BY cr.child_id, cr.created_at DESC
            ) AS latest
            WHERE latest.health_status = 'critical'
        ";

        $childResult = QueryBuilder::rawGet($childSql, ['staff_id' => $userId]);
        $childCount = (int) ($childResult[0]['count'] ?? 0);

        return $maternalCount + $childCount;
    }
}