@extends('layout/portal')

@section('title')
Parent - Child Appointments
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
    <span>Children Appointments</span>
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
                    <c-table.th>Name</c-table.th>
                    <c-table.th>Date</c-table.th>
                    <c-table.th>Start Time</c-table.th>
                    <c-table.th>End Time</c-table.th>
                    <c-table.th>Doctor</c-table.th>
                    <c-table.th>Status</c-table.th>

                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($appointments as $key => $appointment)
                <c-table.tr>
                    <c-table.td col="name">{{$appointment['child']['name']}}</c-table.td>
                    <c-table.td col="slot-date" width="200px">{{$appointment['slot_date']}} </c-table.td>
                    <c-table.td col="start-time" width="200px">{{$appointment['start_time']}}</c-table.td>
                    <c-table.td col="end-time" width="200px">{{$appointment['end_time']}}</c-table.td>
                    <c-table.td col="doctor">
                        @if($appointment['doctor'])
                        {{'Dr. '.$appointment['doctor']['name']}}
                        @else
                        Not Assigned
                        @endif
                    </c-table.td>
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
                                            title="Appointment For" info="{{ $appointment['child']['name'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                            title="Date" info="{{ $appointment['slot_date'] }} " />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Start Time"
                                            info="{{ $appointment['start_time'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="End Time"
                                            info="{{ $appointment['end_time'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/doctor.svg') }}" title="Doctor"
                                            info="{{'Dr. '.$appointment['doctor']['name']}}" />
                                    </c-modal.viewcard>




                                    @if( $appointment['status'] != 'cancelled')
                                    <c-modal.viewlist title="Purpose of Visit">
                                        <c-slot name="list">
                                            <li>{{ $appointment['reason'] }}</li>
                                        </c-slot>
                                    </c-modal.viewlist>
                                    @else
                                    <c-modal.viewlist title="Reason for Cancellation">
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
                                @if($appointment['status'] == 'confirmed' || $appointment['status'] == 'pending')
                                <c-modal id="cancel-appointment-{{$key}}" size="md" :initOpen="flash('cancelAppointment') == $appointment['id'] ? true : false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Cancel Appointment</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.43484 8.56878C6.44624 5.00966 7.45193 3.2301 8.83197 2.77202C9.59117 2.52 10.409 2.52 11.1682 2.77202C12.5482 3.2301 13.5539 5.00966 15.5653 8.56878C17.5767 12.1279 18.5824 13.9075 18.2807 15.3575C18.1148 16.1552 17.7059 16.8787 17.1126 17.4244C16.0343 18.4163 14.0229 18.4163 10.0001 18.4163C5.97729 18.4163 3.96589 18.4163 2.88755 17.4244C2.29431 16.8787 1.88541 16.1552 1.71943 15.3575C1.41774 13.9075 2.42344 12.1279 4.43484 8.56878Z"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" />
                                            <path
                                                d="M4.43484 8.56878C6.44624 5.00966 7.45193 3.2301 8.83197 2.77202C9.59117 2.52 10.409 2.52 11.1682 2.77202C12.5482 3.2301 13.5539 5.00966 15.5653 8.56878C17.5767 12.1279 18.5824 13.9075 18.2807 15.3575C18.1148 16.1552 17.7059 16.8787 17.1126 17.4244C16.0343 18.4163 14.0229 18.4163 10.0001 18.4163C5.97729 18.4163 3.96589 18.4163 2.88755 17.4244C2.29431 16.8787 1.88541 16.1552 1.71943 15.3575C1.41774 13.9075 2.42344 12.1279 4.43484 8.56878Z"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" />
                                            <path
                                                d="M10.2017 14.6667V11.3333C10.2017 10.9405 10.2017 10.7441 10.0797 10.622C9.95766 10.5 9.76125 10.5 9.36841 10.5"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10.2017 14.6667V11.3333C10.2017 10.9405 10.2017 10.7441 10.0797 10.622C9.95766 10.5 9.76125 10.5 9.36841 10.5"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M9.99325 8H10.0007" stroke="#DC2626" stroke-opacity="0.9" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9.99325 8H10.0007" stroke="#DC2626" stroke-opacity="0.9" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </c-slot>

                                    <c-slot name="header">
                                        <span class="cancel">Cancel Appointment</span>

                                    </c-slot>



                                    <div class="msg">
                                        Are you sure you want to cancel the appointment for <strong>{{ $appointment['child']['name'] }}</strong>? This action cannot be undone.
                                    </div>



                                    <form id="cancel-appointment-form-{{ $key }}" action="{{route('parent.appointment.child.cancel', ['id' => $appointment['id']])}}" method="POST" novalidate>
                                        <c-input type="text" name="reason" label="Reason for Cancellation" placeholder="Enter your reason" value="{{ old('reason') ?? '' }}"
                                            error="{{ errors('reason') ?? '' }}"
                                            required />
                                    </form>

                                    <c-slot name="close">
                                        Close
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button variant="destructive" type="submit" form="cancel-appointment-form-{{$key}}">
                                            Cancel Appointment
                                        </c-button>


                                    </c-slot>
                                </c-modal>

                                @endif
                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
                @if(count($appointments) === 0)
                <tr>
                    <td colspan="6">
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