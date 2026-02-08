<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Colombo');

require __DIR__ . '/../vendor/autoload.php';

// bootstrap app (this must call QueryBuilder::init(...) in your bootstrap)
$app = require __DIR__ . '/../bootstrap/app.php';

use Library\Framework\Database\QueryBuilder;

// ----- Advisory lock to avoid overlapping runs -----
$lockKey = 1234567890;

$lockRow = QueryBuilder::rawGet('SELECT pg_try_advisory_lock(?) AS got', [$lockKey]);
$gotLock = false;
if (is_array($lockRow) && count($lockRow) > 0) {
    $val = $lockRow[0]['got'] ?? null;
    // Postgres may return 't'/'f' or boolean; normalize
    $gotLock = ($val === true || $val === 't' || $val === '1' || $val === 1);
}

if (!$gotLock) {
    // Another instance is running — exit quietly
    echo "[" . date('Y-m-d H:i:s') . "] another worker is running; exiting\n";
    exit(0);
}

// Ensure we release the lock on shutdown
register_shutdown_function(function () use ($lockKey) {
    try {
        QueryBuilder::rawGet('SELECT pg_advisory_unlock(?) AS unlocked', [$lockKey]);
    } catch (Throwable $e) {
        // ignore
    }
});

// ---------------- date window ----------------
$monthArg = $argv[1] ?? null;
if ($monthArg === null) {
    $dt = new DateTimeImmutable('first day of next month');
} else {
    // accept YYYY-MM or YYYY-MM-DD
    $try = 
        DateTimeImmutable::createFromFormat('Y-m-d', $monthArg . '-01') ?: 
        DateTimeImmutable::createFromFormat('Y-m-d', $monthArg);
    if ($try === false) {
        // fallback: try creating from the arg directly
        $try = new DateTimeImmutable($monthArg);
    }
    // normalize to first day of that month
    $dt = (new DateTimeImmutable($try->format('Y-m-01')));
}
$start = $dt->setTime(0, 0, 0);
$end   = $dt->modify('last day of this month')->setTime(23, 59, 59);

// ---------------- load active schedule -> schedule_vaccine rows ----------------
// We also want the schedule id for reference if needed
$svs = QueryBuilder::rawGet('
    SELECT s.id AS schedule_id, 
    sv.id, sv.dose_number, sv.min_age_days, sv.due_age_days, sv.min_age_gap_days
    FROM schedules s
    JOIN schedule_vaccines sv ON sv.schedule_id = s.id
    WHERE s.active = TRUE
    ORDER BY sv.dose_number ASC
');

if (empty($svs)) {
    echo "[" . date('Y-m-d H:i:s') . "] no active schedule found; nothing to do\n";
    exit(0);
}

// Normalize schedule_id and keep svs indexed as array of associative arrays
$scheduleId = (int)$svs[0]['schedule_id'];

// ---------------- fetch children ----------------
$children = QueryBuilder::rawGet(
    'SELECT id, name, date_of_birth 
    FROM children WHERE date_of_birth IS NOT NULL'
);

if (empty($children)) {
    echo "[" . date('Y-m-d H:i:s') . "] no children found; exiting\n";
    exit(0);
}

// Prepared insert for reminders (use named params in SQL)
$insertSql = "
    INSERT INTO vaccination_reminders (child_id, schedule_vaccine_id, scheduled_date, status)
    VALUES (:child_id, :sv_id, :scheduled_date, 'pending')
    ON CONFLICT (child_id, schedule_vaccine_id, scheduled_date) DO NOTHING
";

foreach ($children as $child) {
    $childId = (int)$child['id'];
    $childName = $child['name'] ?? 'unknown';
    $dobRaw = $child['date_of_birth'];
    if (empty($dobRaw)) continue;

    try {
        $dob = new DateTimeImmutable($dobRaw);
    } catch (Exception $e) {
        // invalid DOB format - skip
        continue;
    }

    // Fetch vaccinations already given for this child (map by schedule_vaccine_id)
    $givenRows = QueryBuilder::rawGet(
        'SELECT schedule_vaccine_id, administered_at 
        FROM vaccinations WHERE child_id = ?',
        [$childId]
    );
    $given = [];
    foreach ($givenRows as $r) {
        $svId = (int)$r['schedule_vaccine_id'];
        $admin = $r['administered_at'];
        if ($admin !== null) {
            $given[$svId] = $admin; // string timestamp
        } else {
            // keep as null / absent
        }
    }

    // Build previous-admin-by-dose-number map: dose_number => DateTimeImmutable
    $prevAdminByDoseNumber = [];
    foreach ($svs as $sv) {
        $svId = (int)$sv['id'];
        $doseNum = (int)$sv['dose_number'];
        if (isset($given[$svId]) && $given[$svId] !== null) {
            try {
                $prevAdminByDoseNumber[$doseNum] = new DateTimeImmutable($given[$svId]);
            } catch (Exception $e) {
                // ignore parse error
            }
        }
    }

    // For each schedule_vaccine compute scheduled date if not yet given
    foreach ($svs as $sv) {
        $svId = (int)$sv['id'];
        $doseNum = (int)$sv['dose_number'];

        // skip already given
        if (isset($given[$svId]) && $given[$svId] !== null) {
            continue;
        }

        $minAgeDays = (int)$sv['min_age_days'];
        $dueAgeDays = (int)$sv['due_age_days'];
        $minGapDays = (int)$sv['min_age_gap_days'];

        $earliest = $dob->add(new DateInterval("P{$minAgeDays}D"));
        $preferred = $dob->add(new DateInterval("P{$dueAgeDays}D"));

        $gapDate = null;
        $prevDoseNum = $doseNum - 1;
        if (isset($prevAdminByDoseNumber[$prevDoseNum])) {
            $gapDate = $prevAdminByDoseNumber[$prevDoseNum]->add(
                new DateInterval("P{$minGapDays}D")
            );
        }

        // candidate = max(earliest, preferred, gapDate)
        $candidate = $earliest;
        if ($preferred > $candidate) $candidate = $preferred;
        if ($gapDate !== null && $gapDate > $candidate) $candidate = $gapDate;

        // overdue catch-up: if candidate < today, schedule asap respecting gapDate
        $today = new DateTimeImmutable('today');
        if ($candidate < $today) {
            if ($gapDate !== null && $gapDate > $today) {
                $candidate = $gapDate;
            } else {
                $candidate = $today;
            }
        }

        // include if candidate in target month window
        if ($candidate >= $start && $candidate <= $end) {
            // insert reminder (idempotent)
            $params = [
                ':child_id' => $childId,
                ':sv_id' => $svId,
                ':scheduled_date' => $candidate->format('Y-m-d'),
            ];
            try {
                $rowCount = QueryBuilder::rawExec($insertSql, $params);
                if ($rowCount > 0) {
                    echo sprintf(
                        "[%s] Reminder queued: child=%d (%s) dose=%d scheduled=%s\n",
                        date('Y-m-d H:i:s'),
                        $childId,
                        $childName,
                        $doseNum,
                        $candidate->format('Y-m-d')
                    );
                }
            } catch (Throwable $e) {
                // log and continue
                echo "[" . 
                date('Y-m-d H:i:s') . 
                "] failed insert reminder for child {$childId} sv {$svId}: " .
                $e->getMessage() . "\n";
            }
        }
    }
}

// done
echo "[" . date('Y-m-d H:i:s') . "] worker finished\n";
exit(0);
