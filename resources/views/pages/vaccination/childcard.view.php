@extends('layout/portal')

@section('title')
Vaccination Card
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/common/vaccination-card.css') }}">
@endsection

@section('back')
	<c-link href="{{ $backUrl ?? 'javascript:history.back()' }}">
		<c-slot name="icon">
			<svg width="25px" height="25px" viewBox="0 0 1024 1024" fill="#000000" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
				<path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill="" />
			</svg>
		</c-slot>
		Go Back
	</c-link>
@endsection

@section('header')
<?php
$childName = trim((string) ($name ?? 'Vaccination Card'));
$nameParts = preg_split('/\s+/', $childName) ?: [];
$initials = '';

foreach (array_slice($nameParts, 0, 2) as $part) {
	$initials .= function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1);
}

$initials = strtoupper($initials !== '' ? $initials : 'C');
?>
<div class="vaccination-page-heading">
	<span class="vaccination-page-heading__eyebrow">Immunization Card</span>
	<span class="vaccination-page-heading__title">Vaccination Card</span>
	<span class="vaccination-page-heading__subtitle">A complete overview of this child's vaccination history and upcoming doses.</span>
</div>
@endsection

@section('content')
<?php
$records = $vaccinations ?? [];
$groupedRecords = [];
$statusTotals = [
	'complete' => 0,
	'pending' => 0,
	'overdue' => 0,
];

foreach ($records as $record) {
	$status = strtolower((string) ($record['status'] ?? 'pending'));
	if (!isset($statusTotals[$status])) {
		$statusTotals[$status] = 0;
	}

	$statusTotals[$status]++;

	$scheduledDateKey = !empty($record['scheduled_date'])
		? (new DateTimeImmutable((string) $record['scheduled_date']))->format('Y-m-d')
		: 'unscheduled';

	$groupName = $scheduledDateKey === 'unscheduled'
		? 'Unscheduled'
		: (new DateTimeImmutable((string) $record['scheduled_date']))->format('F j, Y');

	$groupedRecords[$scheduledDateKey]['name'] = $groupName;
	$groupedRecords[$scheduledDateKey]['items'][] = $record;
}

foreach ($groupedRecords as &$group) {
	$items = $group['items'] ?? [];
	usort($items, function ($left, $right) {
		$leftDate = !empty($left['scheduled_date']) ? strtotime((string) $left['scheduled_date']) : 0;
		$rightDate = !empty($right['scheduled_date']) ? strtotime((string) $right['scheduled_date']) : 0;

		if ($leftDate === $rightDate) {
			return strcmp((string) ($left['vaccine']['code'] ?? ''), (string) ($right['vaccine']['code'] ?? ''));
		}

		return $leftDate <=> $rightDate;
	});
	$group['items'] = $items;
}
unset($items);
unset($group);

uksort($groupedRecords, function ($left, $right) use ($groupedRecords) {
	$leftItems = $groupedRecords[$left]['items'] ?? [];
	$rightItems = $groupedRecords[$right]['items'] ?? [];

	$leftDate = 0;
	foreach ($leftItems as $item) {
		if (!empty($item['scheduled_date'])) {
			$leftDate = strtotime((string) $item['scheduled_date']);
			break;
		}
	}

	$rightDate = 0;
	foreach ($rightItems as $item) {
		if (!empty($item['scheduled_date'])) {
			$rightDate = strtotime((string) $item['scheduled_date']);
			break;
		}
	}

	if ($leftDate === $rightDate) {
		return strcasecmp($left, $right);
	}

	return $leftDate <=> $rightDate;
});

$timelineGroups = [];
foreach ($groupedRecords as $groupKey => $group) {
	$items = $group['items'] ?? [];
	$groupName = $group['name'] ?? 'Unscheduled';
	$groupComplete = 0;
	$groupPending = 0;
	$groupOverdue = 0;

	foreach ($items as $item) {
		$itemStatus = strtolower((string) ($item['status'] ?? 'pending'));
		if ($itemStatus === 'complete') {
			$groupComplete++;
		} elseif ($itemStatus === 'pending') {
			$groupPending++;
		} elseif ($itemStatus === 'overdue') {
			$groupOverdue++;
		}
	}

	$groupCount = count($items);
	$groupBadgeType = 'purple';
	$groupStatusLabel = 'Upcoming';

	if ($groupOverdue > 0) {
		$groupBadgeType = 'destructive';
		$groupStatusLabel = 'Action needed';
	} elseif ($groupComplete === $groupCount && $groupCount > 0) {
		$groupBadgeType = 'green';
		$groupStatusLabel = 'Completed';
	} elseif ($groupPending > 0) {
		$groupBadgeType = 'purple';
		$groupStatusLabel = 'Scheduled';
	}

	$timelineGroups[] = [
		'name' => $groupName,
		'items' => $items,
		'count' => $groupCount,
		'badgeType' => $groupBadgeType,
		'statusLabel' => $groupStatusLabel,
	];
}

$totalRecords = count($records);
$completedRecords = $statusTotals['complete'] ?? 0;
$pendingRecords = $statusTotals['pending'] ?? 0;
$overdueRecords = $statusTotals['overdue'] ?? 0;
?>

<div class="vaccination-card-page">
	<section class="vaccination-hero">
		<div class="vaccination-hero__identity">
			<div class="vaccination-avatar">{{ $initials }}</div>
			<div class="vaccination-hero__copy">
				<span class="vaccination-hero__label">Child vaccination card</span>
				<h2 class="vaccination-hero__name">{{ ucwords($childName) }}</h2>
				<p class="vaccination-hero__description">
					Vaccination details of child {{ ucfirst($childName) }}
				</p>
			</div>
		</div>

		<div class="vaccination-hero__stats">
			<div class="vaccination-stat">
				<span class="vaccination-stat__label">Total</span>
				<strong class="vaccination-stat__value">{{ $totalRecords }}</strong>
			</div>
			<div class="vaccination-stat">
				<span class="vaccination-stat__label">Completed</span>
				<strong class="vaccination-stat__value vaccination-stat__value--success">{{ $completedRecords }}</strong>
			</div>
			<div class="vaccination-stat">
				<span class="vaccination-stat__label">Pending</span>
				<strong class="vaccination-stat__value vaccination-stat__value--pending">{{ $pendingRecords }}</strong>
			</div>
			<div class="vaccination-stat">
				<span class="vaccination-stat__label">Overdue</span>
				<strong class="vaccination-stat__value vaccination-stat__value--danger">{{ $overdueRecords }}</strong>
			</div>
		</div>
	</section>

	@if ($totalRecords === 0)
		<div class="vaccination-empty-state">
			<div class="vaccination-empty-state__icon">⚕</div>
			<h3>No vaccination records</h3>
			<p>There are no vaccination records available for this child yet.</p>
		</div>
	@else
		<section class="vaccination-timeline">
			<div class="vaccination-timeline__rail"></div>

			@foreach ($timelineGroups as $group)

				<article class="vaccination-milestone">
					<div class="vaccination-milestone__marker"></div>
					<div class="vaccination-milestone__panel">
						<div class="vaccination-milestone__header">
							<div>
								<h3 class="vaccination-milestone__title">{{ $group['name'] }}</h3>
								<p class="vaccination-milestone__subtitle">{{ $group['count'] }} vaccine{{ $group['count'] === 1 ? '' : 's' }} in this milestone</p>
							</div>

							<c-badge type="{{ $group['badgeType'] }}">{{ $group['statusLabel'] }}</c-badge>
						</div>

						<div class="vaccination-grid">
							@foreach ($group['items'] as $item)
								<?php
								$status = strtolower((string) ($item['status'] ?? 'pending'));
								$badgeType = 'purple';

								if ($status === 'complete') {
									$badgeType = 'green';
								} elseif ($status === 'overdue') {
									$badgeType = 'destructive';
								}

								$vaccineCode = $item['vaccine']['code'] ?? 'Vaccine';
								$vaccineName = $item['vaccine']['name'] ?? 'Unnamed vaccine';
								$doseNumber = $item['schedule_vaccine']['dose_number'] ?? 'N/A';
								$scheduleInfo = $item['schedule_vaccine']['additional_information'] ?? 'No additional information available.';
								$recordedAge = $item['recorded_age'] ?? 'N/A';
								$scheduledDate = $item['scheduled_date'] ?? 'N/A';
								$administeredTime = $item['administered_at'] ?? 'N/A';
								?>

								<div class="vaccination-tile vaccination-tile--{{ $status }}">
									<div class="vaccination-tile__top">
										<div>
											<div class="vaccination-tile__code">{{ $vaccineCode }}</div>
											<h4 class="vaccination-tile__title">{{ $vaccineName }}</h4>
										</div>
										<c-badge type="{{ $badgeType }}">{{ ucfirst($status) }}</c-badge>
									</div>

									<div class="vaccination-tile__details">
										<div class="vaccination-detail">
											<span class="vaccination-detail__label">Dose</span>
											<strong class="vaccination-detail__value">{{ $doseNumber }}</strong>
										</div>

										<div class="vaccination-detail">
											<span class="vaccination-detail__label">Recorded age</span>
											<strong class="vaccination-detail__value">{{ $recordedAge }}</strong>
										</div>

										<div class="vaccination-detail">
											<span class="vaccination-detail__label">Scheduled date</span>
											<strong class="vaccination-detail__value">{{ $scheduledDate }}</strong>
										</div>

										@if ($status === 'complete')
											<div class="vaccination-detail vaccination-detail--wide">
												<span class="vaccination-detail__label">Administered time</span>
												<strong class="vaccination-detail__value">{{ $administeredTime }}</strong>
											</div>
										@endif

										<div class="vaccination-detail vaccination-detail--wide">
											<span class="vaccination-detail__label">Additional information</span>
											<p class="vaccination-detail__text">{{ $scheduleInfo }}</p>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</article>
			@endforeach
		</section>
	@endif
</div>
@endsection
