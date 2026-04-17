<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Colombo');

require __DIR__ . '/../vendor/autoload.php';

use App\Services\NotificationService;
use App\Services\VaccinationSchedulerService;
use Library\Framework\Database\QueryBuilder;

$app = require __DIR__ . '/../bootstrap/app.php';

const LOCK_KEY = 876543211;
const LOG_PREFIX = '[worker vaccination.php] ';

function logMsg(string $message): void
{
	echo date('Y-m-d H:i:s') . ' ' . LOG_PREFIX . $message . PHP_EOL;
}

function processVaccinationStage(
	string $stageCode,
	string $targetDate,
	int $maxNotificationCount,
	int $setNotificationCount,
	NotificationService $notificationService
): void {
	$stageLabel = $stageCode === 'stage1' ? 'stage-1 (D-7)' : 'stage-2 (D-1)';
	$parentTitle = $stageCode === 'stage1'
		? 'Vaccination reminder (in 7 days)'
		: 'Vaccination reminder (tomorrow)';
	$phmTitle = $stageCode === 'stage1'
		? 'Upcoming vaccination clinic summary'
		: 'Tomorrow vaccination clinic summary';

	logMsg("{$stageLabel}: scanning reminders for {$targetDate}");

	$params = [
		':target_date' => $targetDate,
		':max_count' => $maxNotificationCount,
	];

	$parentSql = "
		SELECT
			pc.parent_id AS recipient_id,
			vr.child_id,
			c.name AS child_name,
			vr.scheduled_date,
			COUNT(*)::int AS vaccine_count
		FROM vaccination_reminders vr
		INNER JOIN children c ON c.id = vr.child_id
		INNER JOIN parent_children pc ON pc.child_id = vr.child_id
		WHERE vr.scheduled_date = :target_date
		  AND COALESCE(vr.notification_count, 0) < :max_count
		  AND NOT EXISTS (
			  SELECT 1
			  FROM vaccinations v
			  WHERE v.child_id = vr.child_id
				AND v.schedule_vaccine_id = vr.schedule_vaccine_id
		  )
		GROUP BY pc.parent_id, vr.child_id, c.name, vr.scheduled_date
		ORDER BY pc.parent_id, vr.child_id
	";

	$parentGroups = QueryBuilder::rawGet($parentSql, $params);
	$parentSent = 0;

	foreach ($parentGroups as $row) {
		$recipientId = (int)($row['recipient_id'] ?? 0);
		if ($recipientId <= 0) {
			continue;
		}

		$childName = (string)($row['child_name'] ?? 'your child');
		$scheduledDate = (string)($row['scheduled_date'] ?? $targetDate);
		$vaccineCount = (int)($row['vaccine_count'] ?? 0);
		$dateLabel = date('M j, Y', strtotime($scheduledDate));

		$message = $vaccineCount === 1
			? "{$childName} has 1 scheduled vaccination on {$dateLabel}."
			: "{$childName} has {$vaccineCount} scheduled vaccinations on {$dateLabel}.";

		$notificationService->notify(
			$recipientId,
			$parentTitle,
			$message,
			'vaccination'
		);
		$parentSent++;
	}

	$phmSql = "
		SELECT
			c.phm_id AS recipient_id,
			vr.scheduled_date,
			COUNT(DISTINCT vr.child_id)::int AS child_count
		FROM vaccination_reminders vr
		INNER JOIN children c ON c.id = vr.child_id
		WHERE c.phm_id IS NOT NULL
		  AND vr.scheduled_date = :target_date
		  AND COALESCE(vr.notification_count, 0) < :max_count
		  AND NOT EXISTS (
			  SELECT 1
			  FROM vaccinations v
			  WHERE v.child_id = vr.child_id
				AND v.schedule_vaccine_id = vr.schedule_vaccine_id
		  )
		GROUP BY c.phm_id, vr.scheduled_date
		ORDER BY c.phm_id
	";

	$phmGroups = QueryBuilder::rawGet($phmSql, $params);
	$phmSent = 0;

	foreach ($phmGroups as $row) {
		$recipientId = (int)($row['recipient_id'] ?? 0);
		if ($recipientId <= 0) {
			continue;
		}

		$scheduledDate = (string)($row['scheduled_date'] ?? $targetDate);
		$childCount = (int)($row['child_count'] ?? 0);
		$dateLabel = date('M j, Y', strtotime($scheduledDate));

		$message = $childCount === 1
			? "You have 1 child with scheduled vaccinations on {$dateLabel}."
			: "You have {$childCount} children with scheduled vaccinations on {$dateLabel}.";

		$notificationService->notify(
			$recipientId,
			$phmTitle,
			$message,
			'vaccination'
		);
		$phmSent++;
	}

	$updatedCount = QueryBuilder::rawExec(
		"
		UPDATE vaccination_reminders vr
		SET notification_count = :set_count
		WHERE vr.scheduled_date = :target_date
		  AND COALESCE(vr.notification_count, 0) < :set_count
		  AND NOT EXISTS (
			  SELECT 1
			  FROM vaccinations v
			  WHERE v.child_id = vr.child_id
				AND v.schedule_vaccine_id = vr.schedule_vaccine_id
		  )
		",
		[
			':target_date' => $targetDate,
			':set_count' => $setNotificationCount,
		]
	);

	logMsg("{$stageLabel}: parent_notifs={$parentSent}, phm_notifs={$phmSent}, reminders_updated={$updatedCount}");
}

$lockRow = QueryBuilder::rawGet('SELECT pg_try_advisory_lock(?) AS got', [LOCK_KEY]);
$gotLock = false;
if (is_array($lockRow) && count($lockRow) > 0) {
	$value = $lockRow[0]['got'] ?? null;
	$gotLock = ($value === true || $value === 't' || $value === '1' || $value === 1);
}

if (!$gotLock) {
	logMsg('Another vaccination worker is already running — exiting.');
	exit(0);
}

register_shutdown_function(function () {
	try {
		QueryBuilder::rawGet('SELECT pg_advisory_unlock(?) AS unlocked', [LOCK_KEY]);
	} catch (Throwable $e) {
	}
});

logMsg('Lock acquired — run started.');

$schedulerService = new VaccinationSchedulerService();

try {
	$overdueRows = QueryBuilder::rawGet(
		"
		SELECT DISTINCT vr.child_id
		FROM vaccination_reminders vr
		WHERE vr.scheduled_date < :today
		  AND NOT EXISTS (
			  SELECT 1
			  FROM vaccinations v
			  WHERE v.child_id = vr.child_id
				AND v.schedule_vaccine_id = vr.schedule_vaccine_id
		  )
		",
		[':today' => (new DateTimeImmutable('today'))->format('Y-m-d')]
	);

	$reassignedChildren = 0;
	foreach ($overdueRows as $row) {
		$childId = (int)($row['child_id'] ?? 0);
		if ($childId <= 0) {
			continue;
		}

		$schedulerService->recalculateForChild($childId);
		$reassignedChildren++;
	}

	logMsg("overdue reassignment completed: children_processed={$reassignedChildren}");
} catch (Throwable $e) {
	logMsg('overdue reassignment failed: ' . $e->getMessage());
}

$today = new DateTimeImmutable('today');
$stage1Date = $today->modify('+7 days')->format('Y-m-d');
$stage2Date = $today->modify('+1 day')->format('Y-m-d');
$notificationService = new NotificationService();

try {
	processVaccinationStage('stage1', $stage1Date, 1, 1, $notificationService);
} catch (Throwable $e) {
	logMsg('stage-1 (D-7) failed: ' . $e->getMessage());
}

try {
	processVaccinationStage('stage2', $stage2Date, 2, 2, $notificationService);
} catch (Throwable $e) {
	logMsg('stage-2 (D-1) failed: ' . $e->getMessage());
}

logMsg('Vaccination worker finished.');
exit(0);
