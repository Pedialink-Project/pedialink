<?php
// worker/appointment.php
//
// Single-run worker: mark overdue appointments as no-show and trigger
// next-appointment recalculation.

declare(strict_types=1);

date_default_timezone_set('Asia/Colombo');

require __DIR__ . '/../vendor/autoload.php';

use Library\Framework\Database\QueryBuilder;
use App\Services\AppointmentSchedulerService;

// bootstrap app (must call QueryBuilder::init(...) in bootstrap)
$app = require __DIR__ . '/../bootstrap/app.php';

// Config / tuning values
const LOCK_KEY = 987654321;        // unique numeric key for pg advisory lock
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
// Return updated appointment ids so we can trigger follow-up scheduling.
$noShowUpdateSql = "
    UPDATE appointments 
    SET status = 'no-show'
    WHERE status IN ('pending', 'confirmed')
    AND slot_id IN (
        SELECT s.id FROM appointment_slots s
        WHERE s.slot_date < :current_date
        OR (s.slot_date = :current_date2 AND s.end_time < :current_time)
    )
    RETURNING id
";

try {
    $updatedAppointments = QueryBuilder::rawGet($noShowUpdateSql, [
        ':current_date' => $currentDate,
        ':current_date2' => $currentDate,
        ':current_time' => $currentTime,
    ]);

    $updatedCount = is_array($updatedAppointments) ? count($updatedAppointments) : 0;
    logMsg("Checked and updated past appointments to no-show status. Updated: {$updatedCount}");

    if ($updatedCount > 0) {
        $scheduler = new AppointmentSchedulerService();
        foreach ($updatedAppointments as $row) {
            $appointmentId = (int)($row['id'] ?? 0);
            if ($appointmentId <= 0) {
                continue;
            }
            $scheduler->onAppointmentNoShow($appointmentId);
        }
        logMsg('Triggered recalculation for no-show appointments.');
    }
} catch (Throwable $e) {
    logMsg("Error updating no-show appointments: " . $e->getMessage());
}

logMsg('Worker run finished.');
// advisory unlock will run on shutdown handler
exit(0);
