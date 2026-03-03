<?php
// worker/cron_worker.php
//
// Single-run worker: scan child_records + children and auto-assign appointment slots
// Uses QueryBuilder::raw, rawGet, rawExec (no direct PDO).
//
// Usage: php worker/cron_worker.php
// (We expect a runner.sh or cron to call this every 30s.)

declare(strict_types=1);

date_default_timezone_set('Asia/Colombo');

require __DIR__ . '/../vendor/autoload.php';

use Library\Framework\Database\QueryBuilder;

// bootstrap app (must call QueryBuilder::init(...) in bootstrap)
$app = require __DIR__ . '/../bootstrap/app.php';

// Config / tuning values
const LOCK_KEY = 987654321;        // unique numeric key for pg advisory lock
const DAYS_AHEAD = 14;             // search window to find a slot
const FOLLOWUP_DAYS = 30;          // if last visit older than this, we schedule follow-up
const SLOT_DEFAULT_CAPACITY = 1;   // fallback capacity when creating new slot
const LOG_PREFIX = '[worker ' . __FILE__ . '] ';

// Utility: log line
function logMsg(string $msg): void {
    echo date('Y-m-d H:i:s') . ' ' . LOG_PREFIX . $msg . PHP_EOL;
}

// Acquire advisory lock to avoid overlapping runs
$lockRow = QueryBuilder::rawGet('SELECT pg_try_advisory_lock(?) AS got', [LOCK_KEY]);
$gotLock = false;
if (is_array($lockRow) && count($lockRow) > 0) {
    $val = $lockRow[0]['got'] ?? null;
    $gotLock = ($val === true || $val === 't' || $val === '1' || $val === 1);
}

if (!$gotLock) {
    logMsg('Another worker is running — exiting.');
    exit(0);
}

// Ensure lock released on shutdown
register_shutdown_function(function() {
    try {
        QueryBuilder::rawGet('SELECT pg_advisory_unlock(?) AS unlocked', [LOCK_KEY]);
    } catch (Throwable $e) {
        // ignore
    }
});

logMsg('Lock acquired — starting run.');

// Mark past appointments as no-show (only for pending/confirmed status)
$now = new DateTimeImmutable('now');
$currentDate = $now->format('Y-m-d');
$currentTime = $now->format('H:i:s');

// Update appointments where the slot date has passed, or slot date is today but end_time has passed
$noShowUpdateSql = "
    UPDATE appointments 
    SET status = 'no-show'
    WHERE status IN ('pending', 'confirmed')
    AND slot_id IN (
        SELECT s.id FROM appointment_slots s
        WHERE s.slot_date < :current_date
        OR (s.slot_date = :current_date2 AND s.end_time < :current_time)
    )
";

try {
    QueryBuilder::rawExec($noShowUpdateSql, [
        ':current_date' => $currentDate,
        ':current_date2' => $currentDate,
        ':current_time' => $currentTime,
    ]);
    logMsg("Checked and updated past appointments to no-show status.");
} catch (Throwable $e) {
    logMsg("Error updating no-show appointments: " . $e->getMessage());
}

// Load clinic weekly availability (0 = Monday .. 6 = Sunday)
$clinicAvailRows = QueryBuilder::rawGet(
    'SELECT weekday, active, start_time, end_time, slot_length_minutes 
    FROM clinic_weekly_availability WHERE active = TRUE'
);

if (empty($clinicAvailRows)) {
    logMsg('No clinic_weekly_availability rows found or none active — nothing to do.');
    exit(0);
}
// Map by weekday for quick lookup: weekday => row
$clinicAvail = [];
foreach ($clinicAvailRows as $r) {
    $weekday = (int)$r['weekday'];
    $clinicAvail[$weekday] = $r;
}

// Load doctor weekly availability (0 = Monday .. 6 = Sunday)
$doctorAvailRows = QueryBuilder::rawGet(
    'SELECT doctor_id, weekday, start_time, end_time 
    FROM doctor_weekly_availability WHERE active = TRUE'
);

// Map by weekday for quick lookup: weekday => array of doctor availability rows
$doctorAvail = [];
foreach ($doctorAvailRows as $r) {
    $weekday = (int)$r['weekday'];
    if (!isset($doctorAvail[$weekday])) {
        $doctorAvail[$weekday] = [];
    }
    $doctorAvail[$weekday][] = $r;
}

/**
 * Find an available doctor for a given weekday and time slot
 * Returns doctor_id or null if no doctor available
 */
function findAvailableDoctor(array $doctorAvail, int $weekday, string $slotStartTime, string $slotEndTime): ?int {
    if (!isset($doctorAvail[$weekday])) {
        return null;
    }
    
    foreach ($doctorAvail[$weekday] as $docAvail) {
        $docStart = $docAvail['start_time'];
        $docEnd = $docAvail['end_time'];
        
        // Check if slot time falls within doctor's availability window
        // Slot must start at or after doctor's start time AND end at or before doctor's end time
        if ($slotStartTime >= $docStart && $slotEndTime <= $docEnd) {
            return (int)$docAvail['doctor_id'];
        }
    }
    
    return null;
}

// Load children with a DOB
$children = QueryBuilder::rawGet(
    'SELECT id, name, date_of_birth 
    FROM children WHERE date_of_birth IS NOT NULL'
);

if (empty($children)) {
    logMsg('No children found (no date_of_birth) — exiting.');
    exit(0);
}

$today = new DateTimeImmutable('today');

foreach ($children as $child) {
    $childId = (int)$child['id'];
    $childName = $child['name'] ?? '(unnamed)';
    $dobRaw = $child['date_of_birth'] ?? null;
    if (!$dobRaw) continue;

    try {
        $dob = new DateTimeImmutable($dobRaw);
    } catch (Exception $e) {
        logMsg("Skipping child {$childId} — invalid DOB format: {$dobRaw}");
        continue;
    }

    // compute age in days
    $ageInterval = $dob->diff($today);
    $ageDays = (int)$ageInterval->days;

    // get last child_records.visit_date
    $lastVisitRow = QueryBuilder::rawGet(
        'SELECT MAX(visit_date) AS last_visit
        FROM child_records WHERE child_id = ?', [$childId]
    );
    $lastVisit = $lastVisitRow[0]['last_visit'] ?? null; // string or null

    $needsAppointment = false;
    $reason = '';

    if ($lastVisit === null) {
        // No prior visits — for demo: if ~1 month old (28..42 days) schedule initial follow-up
        if ($ageDays >= 28 && $ageDays <= 42) {
            $needsAppointment = true;
            $reason = 'initial 1-month routine check';
        } else if ($ageDays > 42) {
            $needsAppointment = true;
            $reason = 'catch-up (no prior records)';
        }
    } else {
        // parse last visit date
        try {
            $lastVisitDate = new DateTimeImmutable($lastVisit);
            $sinceInterval = $lastVisitDate->diff($today);
            $daysSince = (int)$sinceInterval->days;
            if ($daysSince >= FOLLOWUP_DAYS) {
                $needsAppointment = true;
                $reason = 'routine follow-up (last visit older than ' . FOLLOWUP_DAYS . ' days)';
            }
        } catch (Exception $e) {
            // invalid date in DB — treat as needing appointment
            $needsAppointment = true;
            $reason = 'follow-up (invalid last visit date)';
        }
    }

    if (!$needsAppointment) {
        logMsg("Does not need appointment");
        // nothing to do for this child
        continue;
    }

    // Ensure the child doesn't already have a future appointment (pending/confirmed) to avoid duplicate bookings
    $existingAppt = QueryBuilder::rawGet(
        "SELECT a.id FROM appointments a
        JOIN appointment_slots s ON a.slot_id = s.id
        WHERE a.child_id = ? AND s.slot_date >= current_date AND a.status IN ('pending', 'confirmed') LIMIT 1",
        [$childId]
    );

    if (!empty($existingAppt)) {
        logMsg("Child {$childId} ({$childName}) already has a future appointment — skipping.");
        continue;
    }

    // Check if child has a recent cancelled/no-show appointment (today or yesterday)
    // If so, start from tomorrow to prevent immediate re-booking contention
    $recentCancelledOrNoShow = QueryBuilder::rawGet(
        "SELECT a.id FROM appointments a
        JOIN appointment_slots s ON a.slot_id = s.id
        WHERE a.child_id = ? AND s.slot_date >= (current_date - interval '1 day') AND a.status IN ('cancelled', 'no-show') LIMIT 1",
        [$childId]
    );

    $hasRecentCancelledOrNoShow = !empty($recentCancelledOrNoShow);

    logMsg(
        "Child {$childId} ({$childName}) requires appointment: {$reason} — searching slots next " .
        DAYS_AHEAD .
        " days."
    );

    // Compute target window
    // If child had a recent cancelled/no-show, start from tomorrow to add a gap
    $windowStart = $hasRecentCancelledOrNoShow ? $today->modify('+1 day') : $today;
    $windowEnd = $today->modify('+' . DAYS_AHEAD . ' days');

    $assigned = false;

    // iterate days in window
    for ($dt = $windowStart; $dt <= $windowEnd; $dt = $dt->modify('+1 day')) {
        // PHP DateTimeImmutable::format('N') returns 1 (Mon) .. 7 (Sun). We stored 0=Mon .. 6=Sun
        $weekdayIndex = ((int)$dt->format('N')) - 1;
        if (!array_key_exists($weekdayIndex, $clinicAvail)) {
            // clinic closed this weekday
            continue;
        }

        $avail = $clinicAvail[$weekdayIndex];
        $startTime = $avail['start_time'];
        $endTime = $avail['end_time'];
        $slotLengthMinutes = (int)$avail['slot_length_minutes'];

        // convert times to DateTime for the current slot_date
        $slotDateStr = $dt->format('Y-m-d');
        $slotCursor = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $slotDateStr . ' ' . $startTime
        );
        
        $slotEndBoundary = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $slotDateStr . ' ' . $endTime
        );

        if ($slotCursor === false || $slotEndBoundary === false) {
            // malformed time values in DB; skip date
            continue;
        }

        // For today's date, skip slots that have already passed
        $isToday = ($slotDateStr === $today->format('Y-m-d'));
        $currentTimeNow = new DateTimeImmutable('now');

        // iterate slot times
        while ($slotCursor < $slotEndBoundary) {
            $slotStart = $slotCursor;
            $slotFinish = $slotCursor->modify('+' . $slotLengthMinutes . ' minutes');

            // if slotFinish exceeds end boundary, break
            if ($slotFinish > $slotEndBoundary) break;

            // Skip slots that have already passed (for today only)
            if ($isToday && $slotFinish <= $currentTimeNow) {
                $slotCursor = $slotCursor->modify('+' . $slotLengthMinutes . ' minutes');
                continue;
            }

            $startTimeOnly = $slotStart->format('H:i:s');
            $endTimeOnly = $slotFinish->format('H:i:s');

            // Check if a doctor is available for this time slot
            $assignedDoctorId = findAvailableDoctor($doctorAvail, $weekdayIndex, $startTimeOnly, $endTimeOnly);

            // 1) ensure slot exists (with doctor_id if available, otherwise NULL)
            $insertSlotSql = '
                INSERT INTO appointment_slots (slot_date, start_time, end_time, doctor_id, capacity)
                VALUES (:slot_date, :start_time, :end_time, :doctor_id, :capacity)
                ON CONFLICT (slot_date, start_time, doctor_id) DO NOTHING
            ';
            try {
                QueryBuilder::rawExec($insertSlotSql, [
                    ':slot_date' => $slotDateStr,
                    ':start_time' => $startTimeOnly,
                    ':end_time' => $endTimeOnly,
                    ':doctor_id' => $assignedDoctorId,
                    ':capacity' => SLOT_DEFAULT_CAPACITY,
                ]);
            } catch (Throwable $e) {
                logMsg("Failed to INSERT slot for {$slotDateStr} {$startTimeOnly} - {$e->getMessage()}");
                // continue trying other slots
            }

            // 2) fetch slot id (match by doctor_id - could be NULL)
            if ($assignedDoctorId !== null) {
                $slotRow = QueryBuilder::rawGet(
                    'SELECT id, booked_count, capacity FROM appointment_slots WHERE slot_date = ? AND start_time = ? AND doctor_id = ? LIMIT 1',
                    [$slotDateStr, $startTimeOnly, $assignedDoctorId]
                );
            } else {
                $slotRow = QueryBuilder::rawGet(
                    'SELECT id, booked_count, capacity FROM appointment_slots WHERE slot_date = ? AND start_time = ? AND doctor_id IS NULL LIMIT 1',
                    [$slotDateStr, $startTimeOnly]
                );
            }
            if (empty($slotRow)) {
                // unexpected: slot not found; try next slot time
                $slotCursor = $slotCursor->modify('+' . $slotLengthMinutes . ' minutes');
                continue;
            }
            $slotId = (int)$slotRow[0]['id'];

            // 3) attempt to reserve slot atomically (BEGIN; UPDATE ... RETURNING; INSERT appointment; COMMIT)
            try {
                QueryBuilder::raw('BEGIN');

                $reserveRes = QueryBuilder::rawGet(
                    'UPDATE appointment_slots
                    SET booked_count = booked_count + 1
                    WHERE id = ? AND booked_count < capacity RETURNING id',
                    [$slotId]
                );

                if (!empty($reserveRes)) {
                    // reserved successfully — insert appointment
                    QueryBuilder::raw(
                        'INSERT INTO appointments (slot_id, child_id, reason, status)
                        VALUES (:slot_id, :child_id, :reason, :status)',
                        [
                            ':slot_id' => $slotId,
                            ':child_id' => $childId,
                            ':reason' => 'auto-assigned: ' . $reason,
                            ':status' => 'confirmed',
                        ]
                    );

                    QueryBuilder::raw('COMMIT');
                    $doctorInfo = $assignedDoctorId !== null ? " (doctor_id: {$assignedDoctorId})" : " (no doctor assigned)";
                    logMsg("Assigned slot {$slotDateStr} {$startTimeOnly} to child {$childId} ({$childName}){$doctorInfo}.");
                    $assigned = true;
                    break 2; // break out of both slot loops (child assigned)
                } else {
                    // slot full, rollback and try next slot time
                    QueryBuilder::raw('ROLLBACK');
                    // continue to next slot time
                    logMsg("Rollback");
                }
            } catch (Throwable $e) {
                // something failed — rollback to be safe, log and continue
                try {
                    QueryBuilder::raw('ROLLBACK');
                } catch (Throwable $_) {}
                    logMsg("Error while reserving slot id {$slotId} for child {$childId}: " . $e->getMessage());
                    // continue to next slot time
                }

            // advance to next slot time
            $slotCursor = $slotCursor->modify('+' . $slotLengthMinutes . ' minutes');
        } // end while slots in a date

        // if assigned, loop over children will continue to next child
    } // end for each date in window

    if (!$assigned) {
        logMsg(
            "Unable to find a free slot in next " .
            DAYS_AHEAD .
            " days for child {$childId} ({$childName}). Will retry on next run."
        );
    }
} // end foreach child

logMsg('Worker run finished.');
// advisory unlock will run on shutdown handler
exit(0);
