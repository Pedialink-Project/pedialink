@extends('layout/portal')

@section('title')
    Appoinments
@endsection

@section('header')
    Appointments
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/appointment.css') }}">
@endsection

@section('content')
    <c-table.controls :filters="['status' => ['confirmed', 'pending', 'attended', 'cancelled', 'no-show']]" action="{{ route('admin.appointment.overview')}}">
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
                        <c-table.th sortable="0">Date</c-table.th>
                        <c-table.th sortable="0">Time</c-table.th>
                        <c-table.th sortable="0">Doctor</c-table.th>
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
                            <c-table.td class="appointment-tdata" col="date">{{ $appointment['slot_date'] }}</c-table.td>
                            <c-table.td class="appointment-tdata" col="time">
                                {{ $appointment['start_time'] }} - {{ $appointment['end_time'] }}
                            </c-table.td>
                            <c-table.td class="appointment-tdata" col="doctor">
                                {{ $appointment['doctor'] ? $appointment['doctor']['name'] : 'N/A' }}
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
                                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Appointment Details</div>
                                            </c-slot>
                                            
                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                                    title="Requestor"
                                                    info="{{ $appointment['maternal'] ? $appointment['maternal']['name'] : ($appointment['child'] ? $appointment['child']['name'] : 'N/A') }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Doctor"
                                                    info="{{ $appointment['doctor'] ? $appointment['doctor']['name'] : 'N/A' }}"
                                                />
                                                   <c-modal.viewitem
                                                       icon="{{ asset('assets/icons/student-card.svg') }}"
                                                       title="Appointment ID"
                                                       info="{{ display_entity_id('appointment', $appointment['id']) }}"
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