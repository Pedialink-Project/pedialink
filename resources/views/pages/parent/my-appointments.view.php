@extends('layout/portal')

@section('title')
Parent - Appointments
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/appointments.css') }}">
@endsection

@section('header')
<div class="title-section">
    <svg width="26" height="26" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M2.08337 10C2.08337 6.26806 2.08337 4.40208 3.24274 3.24271C4.40211 2.08334 6.26809 2.08334 10 2.08334C13.732 2.08334 15.598 2.08334 16.7573 3.24271C17.9167 4.40208 17.9167 6.26806 17.9167 10C17.9167 13.732 17.9167 15.5979 16.7573 16.7573C15.598 17.9167 13.732 17.9167 10 17.9167C6.26809 17.9167 4.40211 17.9167 3.24274 16.7573C2.08337 15.5979 2.08337 13.732 2.08337 10Z"
            stroke="#18181B" stroke-width="1.5" />
        <path d="M9.16663 5.83334L14.1666 5.83334" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
        <path d="M5.83337 5.83334L6.66671 5.83334" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
        <path d="M5.83337 10L6.66671 10" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
        <path d="M5.83337 14.1667L6.66671 14.1667" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
        <path d="M9.16663 10L14.1666 10" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
        <path d="M9.16663 14.1667L14.1666 14.1667" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
    </svg>
    <span>My Appointments</span>
</div>

@endsection

@section('content')



<c-table.controls action="{{ route('parent.appointments.child') }}" :filters="['status' => ['attended', 'pending', 'confirmed', 'cancelled','no-show']]">



</c-table.controls>

<c-table.wrapper card="1">
    <div class="table-wrapper" data-responsive="true">
        <c-table.main sticky="1" size="comfortable">
            <c-table.thead>
                <c-table.tr>
                    <c-table.th >Date</c-table.th>
                    <c-table.th >Start Time</c-table.th>
                    <c-table.th >End Time</c-table.th>
                    <c-table.th>Doctor</c-table.th>
                    <c-table.th>Status</c-table.th>

                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($appointments as $key => $appointment)
                <c-table.tr>
                    <c-table.td col="slot-date" width="200px">{{$appointment['slot_date']}} </c-table.td>
                    <c-table.td col="start-time" width="200px">{{$appointment['start_time']}}</c-table.td>
                                        <c-table.td col="end-time" width="200px">{{$appointment['end_time']}}</c-table.td>

                    <c-table.td col="doctor">{{$appointment['doctor']['name']}}</c-table.td>
                    <c-table.td col="status">
                        {{
                        $badgeType = '';

                        switch (strtolower($appointment['status'])) {
                        case 'pending':
                        $badgeType = 'yellow';
                        break;
                        case 'attended':
                        $badgeType = 'green';
                        break;
                        case 'cancelled':
                        $badgeType = 'red';
                        break;
                        case 'confirmed':
                        $badgeType = 'purple';
                        break;
                        default:
                        $badgeType = 'blue';
                        }
                        $badgeText = ucwords(str_replace('_', ' ', $appointment['status']));


                        }}
                        <c-badge type="{{ $badgeType }}">
                            {{$badgeText}}
                        </c-badge>
                    </c-table.td>
                    <c-table.td class="table-actions" align="center">
                        <c-dropdown.main>
                            <c-slot name="trigger">
                                <c-button variant="ghost" class="dropdown-trigger">
                                    <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                </c-button>
                            </c-slot>
                            <c-slot name="menu">
                                <c-modal id="view-appointmant-{{$key}}" size="md" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>View Details</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/profile.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Appointment Details</div>
                                    </c-slot>

                                    <c-slot name="headerSuffix">

                                        <c-badge type="{{ $badgeType }}">
                                            {{$badgeText}}
                                        </c-badge>
                                    </c-slot>



                                    <c-modal.viewcard>

                                        <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}"
                                            title="Appointment For" info="{{ $appointment['maternal']['name'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                            title="Date" info="{{ $appointment['slot_date'] }} " />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Start Time"
                                            info="{{ $appointment['start_time'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="End Time"
                                            info="{{ $appointment['end_time'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/doctor.svg') }}" title="Doctor"
                                            info="{{ $appointment['doctor']['name'] }}" />
                                    </c-modal.viewcard>




                                    @if($appointment['reason'])
                                    <c-modal.viewlist title="Reason for Appointment">
                                        <c-slot name="list">
                                            <li>{{ $appointment['reason'] }}</li>
                                        </c-slot>
                                    </c-modal.viewlist>
                                    @endif

                                    @if($appointment['notes'])
                                    <c-modal.viewlist title="Notes">
                                        <c-slot name="list">
                                            <li>{{ $appointment['notes'] }}</li>

                                        </c-slot>
                                    </c-modal.viewlist>
                                    @endif

                                    <c-slot name="close">
                                        Close
                                    </c-slot>


                                </c-modal>




                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
                @if(count($appointments) === 0)
                <tr>
                    <td colspan="5">
                        <c-emptytable
                            alt="No Appointments found"
                            title="No Appointments Available"
                            description="No appointments match your current search or filters. Try adjusting them to see more results." />
                    </td>
                </tr>
                @endif
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>

<c-table.pagination :links="$links" />
@endsection