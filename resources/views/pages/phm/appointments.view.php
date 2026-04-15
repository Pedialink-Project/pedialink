@extends('layout/portal')

@section('title')
PHM Appointments
@endsection

@section('header')
<svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M2.08337 10C2.08337 6.26806 2.08337 4.40208 3.24274 3.24271C4.40211 2.08334 6.26809 2.08334 10 2.08334C13.732 2.08334 15.598 2.08334 16.7573 3.24271C17.9167 4.40208 17.9167 6.26806 17.9167 10C17.9167 13.732 17.9167 15.5979 16.7573 16.7573C15.598 17.9167 13.732 17.9167 10 17.9167C6.26809 17.9167 4.40211 17.9167 3.24274 16.7573C2.08337 15.5979 2.08337 13.732 2.08337 10Z" stroke="#3A3C41" stroke-width="1.5" />
    <path d="M9.16663 5.83334L14.1666 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
    <path d="M5.83337 5.83334L6.66671 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
    <path d="M5.83337 10L6.66671 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
    <path d="M5.83337 14.1667L6.66671 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
    <path d="M9.16663 10L14.1666 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
    <path d="M9.16663 14.1667L14.1666 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
</svg>
{{ !empty($history) && $history['status'] === true ? "Appointment History of  " . $history['name'] . ' (C-00' . $history['id'] . ')' : "Recent Appointments" }}
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/phm/appointment.css') }}">
@endsection

@if(!empty($history) && $history['status'] === true)
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
@endif

@section('content')
<?php
$actionRouteName = 'phm.appointments' . (!empty($history) && $history['status'] === true ? '.history' : '');
$actionRoute = route($actionRouteName);

if (!empty($history) && $history['status'] === true) {
    $actionRoute = route($actionRouteName, ['id' => $history['id'], 'type' => $history['type']]);
}
?>
<c-table.controls :filters="['status' => ['confirmed', 'pending', 'attended', 'cancelled', 'no-show']]" action="{{ $actionRoute }}">

</c-table.controls>

<c-table.wrapper card="1">
    <div class="table-wrapper" data-responsive="true">
        <c-table.main sticky="1" size="comfortable">
            <c-table.thead>
                <c-table.tr>
                    <c-table.th sortable="0">ID</c-table.th>
                    <c-table.th sortable="0">Name</c-table.th>
                    <c-table.th sortable="0">Category</c-table.th>
                    <c-table.th align="left" sortable="0">Date</c-table.th>
                    <c-table.th align="left" sortable="0">Time</c-table.th>
                    <c-table.th align="left" sortable="0">Status</c-table.th>
                    <c-table.th class="table-actions">Actions</c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($appointments as $key => $appointment)
                <c-table.tr>
                    <c-table.td col="id">AP-00{{ $appointment['id'] }}</c-table.td>
                    <c-table.td col="name">
                        @if ($appointment['child'])
                        {{ $appointment['child']['name'] }}
                        @elseif ($appointment['maternal'])
                        {{ $appointment['maternal']['name'] }}
                        @else
                        N/A
                        @endif
                    </c-table.td>
                    <c-table.td col="Category">
                        @if ($appointment['child'])
                        Child
                        @elseif ($appointment['maternal'])
                        Maternal
                        @else
                        N/A
                        @endif
                    </c-table.td>
                    <c-table.td col="Date">
                        {{ $appointment['slot_date'] }}
                    </c-table.td>
                    <c-table.td col="Time">
                        {{ $appointment['start_time'] }} - {{ $appointment['end_time'] }}
                    </c-table.td>
                    <c-table.td col="Status">
                        @if (strtolower($appointment["status"]) === "confirmed")
                        <c-badge type="primary">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                        @elseif (strtolower($appointment["status"]) === "pending")
                        <c-badge type="yellow">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                        @elseif (strtolower($appointment["status"]) === "attended")
                        <c-badge type="green">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                        @elseif (strtolower($appointment["status"]) === "cancelled")
                        <c-badge type="destructive">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                        @elseif (strtolower($appointment["status"]) === "no-show")
                        <c-badge type="red">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                        @endif
                    </c-table.td>
                    <c-table.td class="table-actions" align="center">
                        <c-dropdown.main>
                            <c-slot name="trigger">
                                <c-button variant="ghost" class="dropdown-trigger">
                                    <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                </c-button>
                            </c-slot>
                            <c-slot name="menu">
                                <c-modal id="View-appointment-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>View Appointment</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerSuffix">
                                        @if (strtolower($appointment["status"]) === "confirmed")
                                        <c-badge type="primary">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                                        @elseif (strtolower($appointment["status"]) === "pending")
                                        <c-badge type="yellow">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                                        @elseif (strtolower($appointment["status"]) === "attended")
                                        <c-badge type="green">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                                        @elseif (strtolower($appointment["status"]) === "cancelled")
                                        <c-badge type="destructive">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                                        @elseif (strtolower($appointment["status"]) === "no-show")
                                        <c-badge type="red">{{ ucwords(str_replace('-', ' ',$appointment['status']))}}</c-badge>
                                        @endif
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Appointment Details</div>
                                    </c-slot>

                                    <c-modal.viewcard>
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/profile-02.svg') }}"
                                            title="Appointment ID"
                                            info="AP-00{{ $appointment['id'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/user.svg') }}"
                                            title="Name"
                                            info="{{ $appointment['child']['name'] ?? ($appointment['maternal']['name'] ?? 'N/A') }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                            title="Age"
                                            info="{{ $appointment['child'] ? $appointment['child']['age'] : ($appointment['maternal'] ? $appointment['maternal']['age'] : 'N/A') }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/user.svg') }}"
                                            title="Category"
                                            info="{{ $appointment['child'] ? 'Child' : ($appointment['maternal'] ? 'Maternal' : 'N/A') }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                            title="Date"
                                            info="{{ $appointment['slot_date'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                            title="Time"
                                            info="{{ $appointment['start_time'] }} - {{ $appointment['end_time'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/user.svg') }}"
                                            title="Doctor"
                                            info="{{ $appointment['doctor'] ? $appointment['doctor']['name'] : 'N/A' }}" />
                                    </c-modal.viewcard>

                                    @if ($appointment['status'] !== 'cancelled')

                                    <h4 class="view-heading-appointment">Purpose of visit</h4>
                                    @else
                                    <h4 class="view-heading-appointment">Reason for cancellation</h4>
                                    @endif

                                    <ul class="view-list-appointment">
                                        <li>{{ $appointment['reason'] }}</li>
                                    </ul>

                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                </c-modal>
                                <c-dropdown.sep />

                                @if ($appointment['status'] === 'pending' || $appointment['status'] === 'confirmed')
                                <c-modal id="attend-appointment-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Mark as Attended</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/user-add--01.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        Mark Appointment as Attended
                                    </c-slot>

                                    <p>Are you sure you want to mark this appointment as attended?</p>

                                    <form id="attend-appointment-form-{{ $key }}" action="{{ route('phm.appointments.attend', ['id' => $appointment['id']]) }}" method="POST">
                                    </form>
                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                    <c-slot name="footer">
                                        <c-button form="attend-appointment-form-{{ $key }}" type="submit" variant="primary">Mark as Attended</c-button>
                                    </c-slot>
                                </c-modal>

                                <c-modal id="cancel-appointment-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Cancel Appointment</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/user-add--01.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        Cancel Appointment
                                    </c-slot>

                                    <form id="cancel-appointment-form-{{ $key }}" action="{{ route('phm.appointments.cancel', ['id' => $appointment['id']]) }}" method="POST">
                                        <c-textarea label="Reason for Cancellation:" name="reason" placeholder="Enter your reason..." rows="3" required></c-textarea>
                                    </form>
                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                    <c-slot name="footer">
                                        <c-button form="cancel-appointment-form-{{ $key }}" type="submit" variant="destructive">Cancel Appointment</c-button>
                                    </c-slot>
                                </c-modal>
                                @endif
                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>

@if(count($appointments) === 0)
<c-emptytable
    alt="Empty"
    title="No Appointments Found"
    description="You currently have no appointments. Please check back later" />
@endif

<c-table.pagination :links="$links" />
@endsection