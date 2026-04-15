@extends('layout/portal')

@section('title')
Parent - Vaccination
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/vaccination.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/common/vaccination-card.css') }}">
@endsection



@section('content')

<div class="vaccination-card-page">
    <section class="vaccination-hero">
        <div class="vaccination-hero__identity">
            <div class="vaccination-avatar">
                <img src="{{asset('assets/icons/vaccine.svg')}}" alt="">
            </div>
            <div class="vaccination-hero__copy">
                <h2 class="vaccination-hero__name">Children Vaccinations</h2>
                <p class="vaccination-hero__description">
                    A complete overview of all your children's vaccination history and upcoming doses.
                </p>
            </div>
        </div>

        <div class="vaccination-hero__stats-wrapper">
            <c-select class="parent-vaccination-child-select" placeholder="Filter by child">
                @if (!empty($childrenList))
                @foreach ($childrenList as $child)
                <li class="select-item" data-value="{{ $child['id'] }}">
                    {{ $child['name'] }}
                </li>
                @endforeach
                <li class="select-item" data-value="all-children">
                    All Children
                </li>
                @else
                <li class="select-item disabled">
                    No children available
                </li>
                @endif
            </c-select>

            <div class="vaccination-hero__stats">
                <div class="vaccination-stat">
                    <span class="vaccination-stat__label">Total</span>
                    <strong class="vaccination-stat__value">{{ $totalRecords }}</strong>
                </div>
                <div class="vaccination-stat">
                    <span class="vaccination-stat__label">Completed</span>
                    <strong class="vaccination-stat__value vaccination-stat__value--success">{{ $statusTotals['complete'] ?? 0 }}</strong>
                </div>
                <div class="vaccination-stat">
                    <span class="vaccination-stat__label">Pending</span>
                    <strong class="vaccination-stat__value vaccination-stat__value--pending">{{ $statusTotals['pending'] ?? 0 }}</strong>
                </div>
                <div class="vaccination-stat">
                    <span class="vaccination-stat__label">Overdue</span>
                    <strong class="vaccination-stat__value vaccination-stat__value--danger">{{ $statusTotals['overdue'] ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </section>

    @if ($totalRecords === 0)
    <div class="vaccination-empty-state">
        <div class="vaccination-empty-state__icon">⚕</div>
        <h3>No vaccination records</h3>
        <p>There are no vaccination records available for your children yet.</p>
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
                    $badgeType = 'yellow';

                    if ($status === 'complete') {
                        $badgeType = 'green';
                    } elseif ($status === 'overdue') {
                        $badgeType = 'red';
                    }

                    $vaccineCode = $item['vaccine']['code'] ?? 'Vaccine';
                    $vaccineName = $item['vaccine']['name'] ?? 'Unnamed vaccine';
                    $doseNumber = $item['sheduled_vaccine']['dose_number'] ?? 'N/A';
                    $scheduleInfo = $item['sheduled_vaccine']['additional_information'] ?? 'No additional information available.';
                    $recordedAge = $item['recorded_age'] ?? 'N/A';
                    $scheduledDate = $item['scheduled_date'] ?? 'N/A';
                    $administeredTime = $item['administered_at'] ?? 'N/A';
                    $childName = $item['child']['name'] ?? 'Unknown Child';
                    $childId = $item['child']['id'] ?? '';
                    ?>

                    <div class="vaccination-tile vaccination-tile--{{ $status }}" data-child-id="{{ $childId }}">
                        <div class="vaccination-tile__top">
                            <div>
                                <div class="vaccination-tile__code">{{ $vaccineCode }}</div>
                                <h4 class="vaccination-tile__title">{{ $vaccineName }}</h4>
                            </div>
                            <c-badge type="{{ $badgeType }}">{{ ucfirst($status) }}</c-badge>
                        </div>

                        <div class="vaccination-tile__details">
                            <div class="vaccination-detail">
                                <span class="vaccination-detail__label">Child</span>
                                <strong class="vaccination-detail__value">{{ $childName }}</strong>
                            </div>

                            <div class="vaccination-detail">
                                <span class="vaccination-detail__label">Dose</span>
                                <strong class="vaccination-detail__value">{{ $doseNumber }}</strong>
                            </div>

                            <div class="vaccination-detail">
                                <span class="vaccination-detail__label">Scheduled date</span>
                                <strong class="vaccination-detail__value">{{ $scheduledDate }}</strong>
                            </div>

                            @if ($status === 'complete')
                            <div class="vaccination-detail">
                                <span class="vaccination-detail__label">Administered time</span>
                                <strong class="vaccination-detail__value">{{ $administeredTime }}</strong>
                            </div>
                            @endif

                            <div class="vaccination-detail vaccination-detail--wide">
                                <span class="vaccination-detail__label">Additional information</span>
                                <strong class="vaccination-detail__value">{{ $scheduleInfo }}</strong>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('.parent-vaccination-child-select');
        if (!select) return;

        var items = select.querySelectorAll('.select-item[data-value]');
        var tiles = document.querySelectorAll('.vaccination-tile[data-child-id]');

        function applyFilter(value) {
            tiles.forEach(function(tile) {
                var childId = tile.getAttribute('data-child-id');
                if (!value || value === 'all-children') {
                    tile.style.display = '';
                } else {
                    tile.style.display = childId === value ? '' : 'none';
                }
            });

            var milestones = document.querySelectorAll('.vaccination-milestone');
            milestones.forEach(function(milestone) {
                var hasVisible = Array.from(milestone.querySelectorAll('.vaccination-tile[data-child-id]')).some(function(tile) {
                    return tile.style.display !== 'none';
                });
                milestone.style.display = hasVisible ? '' : 'none';
            });
        }

        items.forEach(function(item) {
            item.addEventListener('click', function() {
                var value = item.getAttribute('data-value');
                applyFilter(value);
            });
        });
    });
</script>