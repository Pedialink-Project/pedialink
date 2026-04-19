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