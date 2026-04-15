@extends('layout/portal')

@section('title')
Appoinments
@endsection

@section('header')
    <svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.08337 10C2.08337 6.26806 2.08337 4.40208 3.24274 3.24271C4.40211 2.08334 6.26809 2.08334 10 2.08334C13.732 2.08334 15.598 2.08334 16.7573 3.24271C17.9167 4.40208 17.9167 6.26806 17.9167 10C17.9167 13.732 17.9167 15.5979 16.7573 16.7573C15.598 17.9167 13.732 17.9167 10 17.9167C6.26809 17.9167 4.40211 17.9167 3.24274 16.7573C2.08337 15.5979 2.08337 13.732 2.08337 10Z" stroke="#3A3C41" stroke-width="1.5"/>
        <path d="M9.16663 5.83334L14.1666 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 5.83334L6.66671 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 10L6.66671 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 14.1667L6.66671 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M9.16663 10L14.1666 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M9.16663 14.1667L14.1666 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
    {{ !empty($history) && $history['status'] === true ? "Appointment History - " . $history['name'] : "Recent Appointments" }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/appointment.css') }}">
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
    $actionRouteName = 'doctor.appointments' . (!empty($history) && $history['status'] === true ? '.history' : '.overview');
    $actionRoute = route($actionRouteName);

    if (!empty($history) && $history['status'] === true) {
        $actionRoute = route($actionRouteName, ['id' => $history['id'], 'type' => $history['type']]);
    }
    ?>
    <c-table.controls :filters="['status' => ['confirmed', 'pending', 'attended', 'cancelled', 'no-show']]" action="{{ $actionRoute }}">
        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Status
            </c-button>
        </c-slot>
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="0">Name</c-table.th>
                        <c-table.th>Category</c-table.th>
                        <c-table.th sortable="0">Date</c-table.th>
                        <c-table.th sortable="0">Time</c-table.th>
                        <c-table.th>Status</c-table.th>
                        <c-table.th class="table-actions">Actions</c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($appointments as $key => $appointment)
                        <c-table.tr>
                            <c-table.td class="appointment-tdata" col="name">
                                @if ($appointment['child'])
                                    {{ $appointment['child']['name'] }}
                                @elseif ($appointment['maternal'])
                                    {{ $appointment['maternal']['name'] }}
                                @else
                                    N/A
                                @endif
                            </c-table.td>
                            <c-table.td class="appointment-tdata" col="category">
                                @if ($appointment['child'])
                                    Child
                                @else 
                                    Mother                                    
                                @endif
                            </c-table.td>
                            <c-table.td class="appointment-tdata" col="date">{{ $appointment['slot_date'] }}</c-table.td>
                            <c-table.td class="appointment-tdata" col="time">
                                {{ $appointment['start_time'] }} - {{ $appointment['end_time'] }}
                            </c-table.td>
                            <c-table.td class="appointment-tdata" col="status">
                                @if (strtolower($appointment['status']) === "attended")
                                    <c-badge class="status-appointment" type="green">{{ ucfirst($appointment['status']) }}</c-badge>
                                @elseif (in_array(strtolower($appointment['status']), ["upcoming", "confirmed"]))
                                    <c-badge class="status-appointment" type="primary">{{ ucfirst($appointment['status']) }}</c-badge>
                                @elseif (in_array(strtolower($appointment['status']), ["cancelled", "no-show"]))
                                    <c-badge class="status-appointment" type="red">{{ ucfirst($appointment['status']) }}</c-badge>
                                @elseif (in_array(strtolower($appointment['status']), ["pending"]))
                                    <c-badge class="status-appointment" type="yellow">pending</c-badge>                                  
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
                                        <c-dropdown.sep />
                                        <c-modal size="md" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Details</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/profile.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Appointment Details</div>
                                            </c-slot>
                                            
                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile.svg') }}"
                                                    title="Requestor"
                                                    info="{{ $appointment['maternal'] ? $appointment['maternal']['name'] : ($appointment['child'] ? $appointment['child']['name'] : 'N/A') }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="Appointment ID"
                                                    info="AP-00{{ $appointment['id'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="Location"
                                                    info="MOH Office Clinic"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                                    title="Account Type"
                                                    info="{{ $appointment['maternal'] ? 'Maternal' : ($appointment['child'] ? 'Child' : 'N/A') }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="Requestor GS Division"
                                                    info="{{ $appointment['maternal'] ? $appointment['maternal']['division'] : ($appointment['child'] ? $appointment['child']['division'] : 'N/A') }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Date"
                                                    info="{{ $appointment['slot_date'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/clock-01.svg') }}"
                                                    title="Time"
                                                    info="{{ $appointment['start_time'] }} - {{ $appointment['end_time'] }}"
                                                />
                                            </c-modal.viewcard>

                                            <c-modal.viewlist title="Purpose of Visit">
                                                <c-slot name="list">
                                                    <li>{{ $appointment['reason'] }}</li>
                                                </c-slot>
                                            </c-modal.viewlist>

                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>                                        
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
            alt="No appointments"
            title="No Appointments"
            description="There are currently no appointments to display."
        />
    @endif

    <c-table.pagination :links="$links" />
@endsection