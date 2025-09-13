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
    <span>Appointments</span>
</div>

@endsection

@section('content')
<?php

$appointments = [
    [
        'id' => 'APT001',
        'name' => 'John Doe',
        'date' => '2024-07-15',
        'time' => '10:00 AM',
        'location' => 'City Clinic',
        'doctor' => 'Dr. Smith',
        'status' => 'Upcoming',
        'purpose' => 'Regular Checkup',
        'notes' => [
            'Bring previous medical records.',
            'Fasting required for blood test.'
        ]
    ],
    [
        'id' => 'APT002',
        'name' => 'Jane Doe',
        'date' => '2024-06-20',
        'time' => '02:00 PM',
        'location' => 'Downtown Hospital',
        'doctor' => 'Dr. Adams',
        'status' => 'Completed',
        'purpose' => 'Dental Cleaning',
        'notes' => [
            'No special preparation needed.'
        ]
    ],
    [
        'id' => 'APT003',
        'name' => 'Sam Doe',
        'date' => '2024-08-05',
        'time' => '11:30 AM',
        'location' => 'HealthCare Center',
        'doctor' => 'Dr. Lee',
        'status' => 'Pending',
        'purpose' => 'Eye Examination',
        'notes' => [
            'Avoid wearing contact lenses on the day of the appointment.'
        ]
    ],
    [
        'id' => 'APT004',
        'name' => 'Sam Doe',
        'date' => '2024-08-05',
        'time' => '11:30 AM',
        'location' => 'MOH Center',
        'doctor' => 'Dr. Lee',
        'status' => 'Overdue',
        'purpose' => 'Eye Examination',
        'notes' => [
            'Avoid wearing contact lenses on the day of the appointment.'
        ]
    ],


];

?>

<c-table.controls :columns='["Child","Date & Time ","Location","Doctor","Status"]'>
    <c-slot name="filter">
        <c-button variant="outline">
            <img src="{{ asset('assets/icons/filter.svg') }}" />
            Name
        </c-button>
        <c-button variant="outline">
            <img src="{{ asset('assets/icons/filter.svg') }}" />
            Doctor
        </c-button>
    </c-slot>

    <c-slot name="extrabtn">
        <c-modal id="requestAppointment" :initOpen="false">
            <c-slot name="trigger">
                <c-link type="primary">
                    <c-slot name="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.08337 10C2.08337 6.26806 2.08337 4.40208 3.24274 3.24271C4.40211 2.08334 6.26809 2.08334 10 2.08334C13.732 2.08334 15.598 2.08334 16.7573 3.24271C17.9167 4.40208 17.9167 6.26806 17.9167 10C17.9167 13.732 17.9167 15.5979 16.7573 16.7573C15.598 17.9167 13.732 17.9167 10 17.9167C6.26809 17.9167 4.40211 17.9167 3.24274 16.7573C2.08337 15.5979 2.08337 13.732 2.08337 10Z"
                                stroke="#FAFAFA" stroke-width="1.5" />
                            <path d="M9.16663 5.83334L14.1666 5.83334" stroke="#FAFAFA" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M5.83337 5.83334L6.66671 5.83334" stroke="#FAFAFA" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M5.83337 10L6.66671 10" stroke="#FAFAFA" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M5.83337 14.1667L6.66671 14.1667" stroke="#FAFAFA" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M9.16663 10L14.1666 10" stroke="#FAFAFA" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M9.16663 14.1667L14.1666 14.1667" stroke="#FAFAFA" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg> </c-slot>
                    Requset Appointment </c-link>
            </c-slot>

            <c-slot name="headerPrefix">
                <img src="{{ asset('assets/icons/profile.svg' )}}" />
            </c-slot>

            <c-slot name="header">
                Request Appointment
            </c-slot>

            <form id="request-appointment-form" action="">
                <c-select label="Appointment For" name="requester" placeholder="Select Child or Parent" required>
                    <li class="select-item" data-value="P001">John - Parent</li>
                    <li class="select-item" data-value="C001">Sara - Child</li>
                    <li class="select-item" data-value="C002">Doe - Child</li>
                </c-select>
                <c-select label="Doctor Preference" name="doctor" multiple="1" searchable="1">
                    <li class="select-item" data-value="D001">Dr.Smith</li>
                    <li class="select-item" data-value="D002">Dr.John</li>
                    <li class="select-item" data-value="D003">Dr.Alex</li>
                </c-select>
                <c-input type="date" label="Preferred Date" name="date" placeholder="Select Date" required />
                <c-select label="Preferred Time" name="time" multiple="1" searchable="1" required>
                    <li class="select-item" data-value=" ">09.00 AM</li>
                    <li class="select-item" data-value=" ">10.00 AM</li>
                    <li class="select-item" data-value=" ">11.00 AM</li>
                    <li class="select-item" data-value=" ">12.00 PM</li>
                </c-select>
                <c-input type="text" name="purpose" label="Appointment Purpose"
                    placeholder="Eanter your visit purpose" />
                <c-input type="text" name="notes" label="Additional Notes"
                    placeholder="Any additional notes or others" />




            </form>

            <c-slot name="close">
                Cancel
            </c-slot>

            <c-slot name="footer">
                <c-button type="submit" form="request-appointment-form" variant="primary">Submit Request</c-button>
            </c-slot>

        </c-modal>

    </c-slot>
</c-table.controls>

<c-table.wrapper card="1">
    <div class="table-wrapper" data-responsive="true">
        <c-table.main sticky="1" size="comfortable">
            <c-table.thead>
                <c-table.tr>
                    <c-table.th sortable="1">Name</c-table.th>
                    <c-table.th sortable="1">Date & Time</c-table.th>
                    <c-table.th sortable="1">Location</c-table.th>
                    <c-table.th>Doctor</c-table.th>
                    <c-table.th>Status</c-table.th>

                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($appointments as $appointmnet)
                <c-table.tr>
                    <c-table.td col="name">{{$appointmnet['name']}}</c-table.td>
                    <c-table.td col="date-time" width="200px">{{$appointmnet['date']}} at
                        {{$appointmnet['time']}}</c-table.td>
                    <c-table.td col="location" width="200px">{{$appointmnet['location']}}</c-table.td>
                    <c-table.td col="doctor">{{$appointmnet['doctor']}}</c-table.td>
                    <c-table.td col="status">
                        {{
                        $badgeType = '';
                        if(strtolower($appointmnet['status']) == 'completed') {
                        $badgeType = 'green';
                        } elseif (strtolower($appointmnet['status']) == 'upcoming') {
                        $badgeType = 'purple';
                        } elseif (strtolower($appointmnet['status']) == 'pending') {
                        $badgeType = 'yellow';
                        }
                        else {
                        $badgeType = 'red';
                        }

                        }}
                        <c-badge type="{{ $badgeType }}">
                            {{$appointmnet['status']}}
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

                                    <c-slot name="headerSuffix">

                                        <c-badge type="{{ $badgeType }}">
                                            {{$appointmnet['status']}}
                                        </c-badge>
                                    </c-slot>



                                    <c-modal.viewcard>
                                        <c-modal.viewitem icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Appointment ID" info="{{ $appointmnet['id'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}"
                                            title="Requester Name" info="{{ $appointmnet['name'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                            title="Date" info="{{ $appointmnet['date'] }} " />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                                            info="{{ $appointmnet['time'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}"
                                            title="Location" info="{{ $appointmnet['location'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/doctor.svg') }}" title="Doctor"
                                            info="{{ $appointmnet['doctor'] }}" />



                                    </c-modal.viewcard>



                                    <c-slot name="footer">
                                        <c-button varient="primary">
                                            Cancel Appointment
                                        </c-button>

                                        <c-button varient="primary">
                                            Reschedule Appointment
                                        </c-button>
                                    </c-slot>
                                </c-modal>
                                <c-dropdown.sep />
                                <c-modal size="md" :initOpen="true">
                                    <c-slot name="trigger">
                                        <c-dropdown.item> Reschedule Appointment </c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/profile.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Reschedule Appointment</div>
                                    </c-slot>

                                    <form id="reschedule-appointment-form" action="">
                                        <c-input type="date" label="New Date" name="date" placeholder="Select Date"
                                            required />
                                        <c-select label="New Time" name="time" multiple="1" searchable="1" required>
                                            <li class="select-item" data-value=" ">09.00 AM</li>
                                            <li class="select-item" data-value=" ">10.00 AM</li>
                                            <li class="select-item" data-value=" ">11.00 AM</li>
                                            <li class="select-item" data-value=" ">12.00 PM</li>
                                        </c-select>
                                        <c-input type="text" name="purpose" label="Reason for Rescheduling"
                                            placeholder="Eanter your reason" required />
                                        <c-input type="text" name="notes" label="Additional Notes"
                                            placeholder="Any additional notes or others" />
                                    </form>

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button type="submit" from="reschedule-appointment-form" varient="primary">
                                            Reschedule Appointment
                                        </c-button>
                                    </c-slot>
                                </c-modal>

                                <c-dropdown.item>
                                    Cancel Appointment
                                </c-dropdown.item>

                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
                @if(count($appointmnet) === 0)
                <tr>
                    <td colspan="6">
                        <div class="table-empty">No items found</div>
                    </td>
                </tr>
                @endif
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>

<c-table.pagination />
@endsection