<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Child;
use App\Models\Maternal;
use App\Models\Pregnancy;
use Library\Framework\Database\QueryBuilder;

class AppointmentSchedulerService
{
    public function scheduleInitialForChild(int $childId): bool
    {
        $child = Child::find($childId);
        if (!$child || !$child->date_of_birth) {
            return false;
        }

        if ($this->hasUpcomingAppointment('child', $childId)) {
            return false;
        }

        $reason = $this->resolveChildReason($child->date_of_birth);
        return $this->scheduleNext('child', $childId, $reason);
    }

    public function scheduleInitialForMaternal(int $maternalId): bool
    {
        $maternal = Maternal::find($maternalId);
        if (!$maternal) {
            return false;
        }

        if ($this->hasUpcomingAppointment('maternal', $maternalId)) {
            return false;
        }

        $reason = $this->resolveMaternalReason($maternalId);
        return $this->scheduleNext('maternal', $maternalId, $reason);
    }

    public function scheduleOnAntenatalStart(int $maternalId): bool
    {
        if ($this->hasUpcomingAppointment('maternal', $maternalId)) {
            return false;
        }

        $reason = $this->resolveMaternalReason($maternalId);
        return $this->scheduleNext('maternal', $maternalId, $reason);
    }

    public function onAppointmentCancelled(int $appointmentId): void
    {
        $this->releaseSlotCapacity($appointmentId);
        $this->recalculateUpcomingFromAppointment($appointmentId);
    }

    public function onAppointmentNoShow(int $appointmentId): void
    {
        $this->releaseSlotCapacity($appointmentId);
        $this->recalculateUpcomingFromAppointment($appointmentId);
    }

    public function onNoShowDetected(?int $slotId, ?int $childId, ?int $maternalId): void
    {
        if ($slotId !== null && $slotId > 0) {
            $this->releaseSlotCapacityBySlotId($slotId);
        }

        if (!empty($childId)) {
            $this->recalculateNextForChild((int)$childId);
            return;
        }

        if (!empty($maternalId)) {
            $this->recalculateNextForMaternal((int)$maternalId);
        }
    }

    public function recalculateUpcomingFromAppointment(int $appointmentId): bool
    {
        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return false;
        }

        if (!empty($appointment->child_id)) {
            return $this->recalculateNextForChild((int)$appointment->child_id);
        }

        if (!empty($appointment->maternal_id)) {
            return $this->recalculateNextForMaternal((int)$appointment->maternal_id);
        }

        return false;
    }

    public function recalculateNextForChild(int $childId): bool
    {
        if ($this->hasUpcomingAppointment('child', $childId)) {
            return false;
        }

        $child = Child::find($childId);
        if (!$child || !$child->date_of_birth) {
            return false;
        }

        $reason = $this->resolveChildReason($child->date_of_birth);
        $startDate = $this->getRebookWindowStart('child', $childId);
        return $this->scheduleNext('child', $childId, $reason, $startDate);
    }

    public function recalculateNextForMaternal(int $maternalId): bool
    {
        if ($this->hasUpcomingAppointment('maternal', $maternalId)) {
            return false;
        }

        $maternal = Maternal::find($maternalId);
        if (!$maternal) {
            return false;
        }

        $reason = $this->resolveMaternalReason($maternalId);
        $startDate = $this->getRebookWindowStart('maternal', $maternalId);
        return $this->scheduleNext('maternal', $maternalId, $reason, $startDate);
    }

    private function scheduleNext(string $ownerType, int $ownerId, string $reason, ?\DateTimeImmutable $windowStart = null): bool
    {
        $daysAhead = (int)(config('appointments.days_ahead') ?? 30);
        $slotCapacity = (int)(config('appointments.slot_default_capacity') ?? 1);

        $clinicAvailRows = QueryBuilder::rawGet(
            'SELECT weekday, active, start_time, end_time, slot_length_minutes
             FROM clinic_weekly_availability
             WHERE active = TRUE'
        );

        if (empty($clinicAvailRows)) {
            return false;
        }

        $clinicAvail = [];
        foreach ($clinicAvailRows as $row) {
            $clinicAvail[(int)$row['weekday']] = $row;
        }

        $doctorAvailRows = QueryBuilder::rawGet(
            'SELECT doctor_id, weekday, start_time, end_time
             FROM doctor_weekly_availability
             WHERE active = TRUE'
        );

        $doctorAvail = [];
        foreach ($doctorAvailRows as $row) {
            $weekday = (int)$row['weekday'];
            if (!isset($doctorAvail[$weekday])) {
                $doctorAvail[$weekday] = [];
            }
            $doctorAvail[$weekday][] = $row;
        }

        $today = new \DateTimeImmutable('today');
        $windowStart = $windowStart ?? $today;
        $windowEnd = $windowStart->modify('+' . $daysAhead . ' days');
        $now = new \DateTimeImmutable('now');

        for ($date = $windowStart; $date <= $windowEnd; $date = $date->modify('+1 day')) {
            $weekdayIndex = ((int)$date->format('N')) - 1;
            if (!isset($clinicAvail[$weekdayIndex])) {
                continue;
            }

            $availability = $clinicAvail[$weekdayIndex];
            $slotLengthMinutes = (int)$availability['slot_length_minutes'];
            $slotDate = $date->format('Y-m-d');

            $slotCursor = \DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $slotDate . ' ' . $availability['start_time']
            );
            $slotBoundary = \DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $slotDate . ' ' . $availability['end_time']
            );

            if ($slotCursor === false || $slotBoundary === false) {
                continue;
            }

            $isToday = ($slotDate === $today->format('Y-m-d'));

            while ($slotCursor < $slotBoundary) {
                $slotStart = $slotCursor;
                $slotEnd = $slotCursor->modify('+' . $slotLengthMinutes . ' minutes');
                if ($slotEnd > $slotBoundary) {
                    break;
                }

                if ($isToday && $slotEnd <= $now) {
                    $slotCursor = $slotCursor->modify('+' . $slotLengthMinutes . ' minutes');
                    continue;
                }

                $startTime = $slotStart->format('H:i:s');
                $endTime = $slotEnd->format('H:i:s');
                $doctorId = $this->findAvailableDoctor($doctorAvail, $weekdayIndex, $startTime, $endTime);

                $slotId = $this->findOrCreateSlot($slotDate, $startTime, $endTime, $doctorId, $slotCapacity);
                if ($slotId !== null && $this->reserveSlotAndCreateAppointment($slotId, $ownerType, $ownerId, $reason)) {
                    return true;
                }

                $slotCursor = $slotCursor->modify('+' . $slotLengthMinutes . ' minutes');
            }
        }

        return false;
    }

    private function findAvailableDoctor(array $doctorAvail, int $weekday, string $slotStartTime, string $slotEndTime): ?int
    {
        if (!isset($doctorAvail[$weekday])) {
            return null;
        }

        foreach ($doctorAvail[$weekday] as $row) {
            $docStart = $row['start_time'];
            $docEnd = $row['end_time'];
            if ($slotStartTime >= $docStart && $slotEndTime <= $docEnd) {
                return (int)$row['doctor_id'];
            }
        }

        return null;
    }

    private function findOrCreateSlot(
        string $slotDate,
        string $startTime,
        string $endTime,
        ?int $doctorId,
        int $defaultCapacity
    ): ?int {
        $slot = $this->getSlotRow($slotDate, $startTime, $doctorId);
        if (!empty($slot)) {
            return (int)$slot[0]['id'];
        }

        QueryBuilder::rawExec(
            'INSERT INTO appointment_slots (slot_date, start_time, end_time, doctor_id, capacity)
             VALUES (:slot_date, :start_time, :end_time, :doctor_id, :capacity)
             ON CONFLICT (slot_date, start_time, doctor_id) DO NOTHING',
            [
                ':slot_date' => $slotDate,
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':doctor_id' => $doctorId,
                ':capacity' => $defaultCapacity,
            ]
        );

        $slot = $this->getSlotRow($slotDate, $startTime, $doctorId);
        if (empty($slot)) {
            return null;
        }

        return (int)$slot[0]['id'];
    }

    private function getSlotRow(string $slotDate, string $startTime, ?int $doctorId): array
    {
        if ($doctorId !== null) {
            return QueryBuilder::rawGet(
                'SELECT id FROM appointment_slots
                 WHERE slot_date = ? AND start_time = ? AND doctor_id = ?
                 ORDER BY id ASC LIMIT 1',
                [$slotDate, $startTime, $doctorId]
            );
        }

        return QueryBuilder::rawGet(
            'SELECT id FROM appointment_slots
             WHERE slot_date = ? AND start_time = ? AND doctor_id IS NULL
             ORDER BY id ASC LIMIT 1',
            [$slotDate, $startTime]
        );
    }

    private function reserveSlotAndCreateAppointment(int $slotId, string $ownerType, int $ownerId, string $reason): bool
    {
        try {
            QueryBuilder::raw('BEGIN');

            $reserve = QueryBuilder::rawGet(
                'UPDATE appointment_slots
                 SET booked_count = booked_count + 1
                 WHERE id = ? AND booked_count < capacity
                 RETURNING id',
                [$slotId]
            );

            if (empty($reserve)) {
                QueryBuilder::raw('ROLLBACK');
                return false;
            }

            if ($ownerType === 'child') {
                QueryBuilder::raw(
                    'INSERT INTO appointments (slot_id, child_id, reason, status)
                     VALUES (:slot_id, :owner_id, :reason, :status)',
                    [
                        ':slot_id' => $slotId,
                        ':owner_id' => $ownerId,
                        ':reason' => $reason,
                        ':status' => 'pending',
                    ]
                );
            } else {
                QueryBuilder::raw(
                    'INSERT INTO appointments (slot_id, maternal_id, reason, status)
                     VALUES (:slot_id, :owner_id, :reason, :status)',
                    [
                        ':slot_id' => $slotId,
                        ':owner_id' => $ownerId,
                        ':reason' => $reason,
                        ':status' => 'pending',
                    ]
                );
            }

            QueryBuilder::raw('COMMIT');
            return true;
        } catch (\Throwable $e) {
            try {
                QueryBuilder::raw('ROLLBACK');
            } catch (\Throwable $_) {
            }

            return false;
        }
    }

    private function hasUpcomingAppointment(string $ownerType, int $ownerId): bool
    {
        $column = $ownerType === 'child' ? 'a.child_id' : 'a.maternal_id';
        $rows = QueryBuilder::rawGet(
            "SELECT a.id FROM appointments a
             JOIN appointment_slots s ON s.id = a.slot_id
             WHERE {$column} = ?
             AND s.slot_date >= current_date
             AND a.status IN ('pending', 'confirmed')
             LIMIT 1",
            [$ownerId]
        );

        return !empty($rows);
    }

    private function releaseSlotCapacity(int $appointmentId): void
    {
        $rows = QueryBuilder::rawGet(
            'SELECT slot_id FROM appointments WHERE id = ? LIMIT 1',
            [$appointmentId]
        );

        if (empty($rows) || empty($rows[0]['slot_id'])) {
            return;
        }

        QueryBuilder::rawExec(
            'UPDATE appointment_slots
             SET booked_count = CASE WHEN booked_count > 0 THEN booked_count - 1 ELSE 0 END
             WHERE id = ?',
            [(int)$rows[0]['slot_id']]
        );
    }

    private function releaseSlotCapacityBySlotId(int $slotId): void
    {
        if ($slotId <= 0) {
            return;
        }

        QueryBuilder::rawExec(
            'UPDATE appointment_slots
             SET booked_count = CASE WHEN booked_count > 0 THEN booked_count - 1 ELSE 0 END
             WHERE id = ?',
            [$slotId]
        );
    }

    private function getRebookWindowStart(string $ownerType, int $ownerId): \DateTimeImmutable
    {
        $delayDays = (int)(config('appointments.rebook_delay_days_after_miss') ?? 1);
        $today = new \DateTimeImmutable('today');
        $column = $ownerType === 'child' ? 'a.child_id' : 'a.maternal_id';

        $rows = QueryBuilder::rawGet(
            "SELECT a.id FROM appointments a
             JOIN appointment_slots s ON s.id = a.slot_id
             WHERE {$column} = ?
             AND s.slot_date >= (current_date - interval '1 day')
             AND a.status IN ('cancelled', 'no-show')
             LIMIT 1",
            [$ownerId]
        );

        if (!empty($rows)) {
            return $today->modify('+' . $delayDays . ' days');
        }

        return $today;
    }

    private function resolveChildReason(string $dateOfBirth): string
    {
        try {
            $dob = new \DateTimeImmutable($dateOfBirth);
            $ageDays = (int)$dob->diff(new \DateTimeImmutable('today'))->days;
        } catch (\Exception $e) {
            return (string)(config('appointments.child_default_reason') ?? 'Child routine health check');
        }

        $rules = config('appointments.child_rules') ?? [];
        foreach ($rules as $rule) {
            $min = (int)($rule['min_age_days'] ?? 0);
            $max = (int)($rule['max_age_days'] ?? PHP_INT_MAX);
            if ($ageDays >= $min && $ageDays <= $max) {
                return (string)($rule['reason'] ?? 'Child routine health check');
            }
        }

        return (string)(config('appointments.child_default_reason') ?? 'Child routine health check');
    }

    private function resolveMaternalReason(int $maternalId): string
    {
        $maternal = Maternal::find($maternalId);
        if (!$maternal) {
            return (string)(config('appointments.maternal_default_reason') ?? 'Maternal routine check-up');
        }

        $latestPregnancy = Pregnancy::query()
            ->where('maternal_id', '=', $maternalId)
            ->orderBy('id', 'DESC')
            ->first();

        if ($maternal->type === 'antenatal' && $latestPregnancy && !empty($latestPregnancy->lmp)) {
            try {
                $lmp = new \DateTimeImmutable($latestPregnancy->lmp);
                $days = (int)$lmp->diff(new \DateTimeImmutable('today'))->days;
                $weeks = (int)floor($days / 7);

                $rules = config('appointments.maternal_rules') ?? [];
                foreach ($rules as $rule) {
                    $min = (int)($rule['min_gestation_weeks'] ?? 0);
                    $max = (int)($rule['max_gestation_weeks'] ?? PHP_INT_MAX);
                    if ($weeks >= $min && $weeks <= $max) {
                        return (string)($rule['reason'] ?? 'Antenatal routine check-up');
                    }
                }
            } catch (\Exception $e) {
                return (string)(config('appointments.maternal_default_reason') ?? 'Maternal routine check-up');
            }
        }

        if ($maternal->type === 'postnatal') {
            return (string)(config('appointments.maternal_postnatal_reason') ?? 'Postnatal routine check-up');
        }

        return (string)(config('appointments.maternal_default_reason') ?? 'Maternal routine check-up');
    }
}
