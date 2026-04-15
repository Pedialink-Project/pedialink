<?php
// worker/appointment.php
//
// Single-run worker: mark overdue appointments as no-show and trigger
// next-appointment recalculation.

declare(strict_types=1);

date_default_timezone_set('Asia/Colombo');

require __DIR__ . '/../vendor/autoload.php';

use Library\Framework\Database\QueryBuilder;
use App\Helpers\Calculator;
use App\Models\AppointmentSlot;
use App\Models\Child;
use App\Models\Maternal;
use App\Services\AppointmentSchedulerService;
use App\Services\NotificationService;

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
    RETURNING id, slot_id, child_id, maternal_id
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
        $notificationService = new NotificationService();
        foreach ($updatedAppointments as $row) {
            $slotId = isset($row['slot_id']) ? (int)$row['slot_id'] : 0;
            $childId = isset($row['child_id']) ? (int)$row['child_id'] : null;
            $maternalId = isset($row['maternal_id']) ? (int)$row['maternal_id'] : null;

            if ($slotId <= 0) {
                continue;
            }

            $scheduler->onNoShowDetected(
                $slotId,
                $childId > 0 ? $childId : null,
                $maternalId > 0 ? $maternalId : null
            );

            $slot = AppointmentSlot::find($slotId);
            if (!$slot) {
                continue;
            }

            $doctor = $slot->getDoctor();
            $doctorId = $doctor ? (int)$doctor->id : null;

            $patientName = null;
            $parentRecipientIds = [];

            if ($childId && $childId > 0) {
                $child = Child::find($childId);
                if ($child) {
                    $patientName = $child->name;
                    $parents = $child->getParents();
                    if ($parents) {
                        foreach ($parents as $parent) {
                            $user = $parent->getUser();
                            if ($user) {
                                $parentRecipientIds[] = (int)$user->id;
                            }
                        }
                    }
                }
            } elseif ($maternalId && $maternalId > 0) {
                $maternal = Maternal::find($maternalId);
                if ($maternal && $maternal->getUser()) {
                    $patientName = $maternal->getUser()->name;
                    $parentRecipientIds[] = (int)$maternal->getUser()->id;
                }
            }

            $patientPart = $patientName ? " for {$patientName}" : "";

            if ($doctorId !== null) {
                $doctorMessage = "An appointment{$patientPart} on {$slot->slot_date} was marked as no-show.";
                $notificationService->notify(
                    $doctorId,
                    "Missed appointment",
                    $doctorMessage,
                    "appointment",
                    isset($row['id']) ? (int)$row['id'] : null
                );
            }

            if (!empty($parentRecipientIds)) {
                $parentMessage = "Your appointment{$patientPart} on {$slot->slot_date} from "
                    . Calculator::formatTimeToAmPm($slot->start_time)
                    . " to " . Calculator::formatTimeToAmPm($slot->end_time)
                    . " was marked as missed (no-show). You can book a new appointment through the portal.";

                $notificationService->notifyMany(
                    $parentRecipientIds,
                    "Missed appointment",
                    $parentMessage,
                    "appointment",
                    isset($row['id']) ? (int)$row['id'] : null
                );
            }
        }
        logMsg('Triggered recalculation for no-show appointments.');
    }
} catch (Throwable $e) {
    logMsg("Error updating no-show appointments: " . $e->getMessage());
}

logMsg('Worker run finished.');
// advisory unlock will run on shutdown handler
exit(0);
