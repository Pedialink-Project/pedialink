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

// $appointments = [
//     [
//         'id' => 'APP-001',
//         'patient_name' => 'Keeththi Perera',
//         'date' => '2025-10-21',
//         'time' => '09:00',
//         'datetime' => '2025-10-21 09:00',
//         'location' => 'City Hospital, Colombo',
//         'staff_name' => 'Dr. Sarah Fernando',
//         'status' => 'Upcoming',
//         'purpose' => 'Routine Checkup',
//         'notes' => ['Bring previous medical reports', 'Fasting required']
//     ],
//     [
//         'id' => 'APP-002',
//         'patient_name' => 'Nimali Jayawardena',
//         'date' => '2025-10-22',
//         'time' => '11:00',
//         'datetime' => '2025-10-22 11:00',
//         'location' => 'Green Valley Clinic, Kandy',
//         'staff_name' => 'Dr. Michael Silva',
//         'status' => 'Pending',
//         'purpose' => 'Vaccination',
//         'notes' => ['Child should not have fever', 'Bring vaccination card']
//     ],
//     [
//         'id' => 'APP-003',
//         'patient_name' => 'Arjun Perera',
//         'date' => '2025-10-19',
//         'time' => '10:00',
//         'datetime' => '2025-10-19 10:00',
//         'location' => 'City Hospital, Colombo',
//         'staff_name' => 'Dr. Priya Kumar',
//         'status' => 'Completed',
//         'purpose' => 'Follow-up Consultation',
//         'notes' => ['Follow-up in 2 weeks if needed']
//     ],
//     [
//         'id' => 'APP-004',
//         'patient_name' => 'Samantha Wijesinghe',
//         'date' => '2025-10-25',
//         'time' => '12:00',
//         'datetime' => '2025-10-25 12:00',
//         'location' => 'Sunrise Clinic, Galle',
//         'staff_name' => 'Dr. Anura Perera',
//         'status' => 'Cancelled',
//         'purpose' => 'Dental Checkup',
//         'notes' => ['Patient unavailable on original date']
//     ],
//     [
//         'id' => 'APP-005',
//         'patient_name' => 'Dilan Fernando',
//         'date' => '2025-10-23',
//         'time' => '09:00',
//         'datetime' => '2025-10-23 09:00',
//         'location' => 'City Hospital, Colombo',
//         'staff_name' => 'Dr. Sarah Fernando',
//         'status' => 'Upcoming',
//         'purpose' => 'Pediatric Consultation',
//         'notes' => ['Bring growth chart']
//     ],
// ];

?>


<c-table.controls :columns='["Child","Date & Time ","Location","Doctor","Status"]'>
    <c-slot name="filter">
        <c-button variant="outline">
            <img src="{{ asset('assets/icons/filter.svg') }}" />
            Name
        </c-button>
        <c-button variant="outline">
            <img src="{{ asset('assets/icons/filter.svg') }}" />
            Staff
        </c-button>
    </c-slot>

    <c-slot name="extrabtn">
        <c-modal id="requestAppointment" :initOpen="flash('create') ? true : false">
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

            <form id="request-appointment-form" action="{{ route('parent.appointment.request') }}" method="POST">
                <c-select label="Appointment For" name="patient" placeholder="Select Child or Parent" error="{{ errors('patient') ?? '' }}" value="{{ old('patient') ?? '' }}" required>
                    <li class="select-item" data-value="1">Keeththi</li>
                    <li class="select-item" data-value="2">Alex Hales</li>
                </c-select>
                
                <c-select label="Staff Preference" name="staff" searchable="1" error="{{ errors('staff') ?? '' }}" value="{{ old('staff')??''}}" required>
                    <li class="select-item" data-value="2">Sarah</li>
                    <li class="select-item" data-value="1">Nimal</li>
                </c-select>

                <c-input type="date" label="Preferred Date" name="date" placeholder="Select Date"
                    error="{{ errors('date') ?? '' }}" value="{{ old('date')??'' }}" />
                <c-select label="Preferred Time" name="time" error="{{ errors('time') ?? '' }}" value="{{ old('time')??'' }}" required>
                    <li class="select-item" data-value="09:00">09.00 AM</li>
                    <li class="select-item" data-value="10:00">10.00 AM</li>
                    <li class="select-item" data-value="11:00">11.00 AM</li>
                    <li class="select-item" data-value="12:00">12.00 PM</li>
                </c-select>

                <c-textarea name="purpose" label="Appointment Purpose" placeholder="Enter your visit purpose" value="{{ old('purpose')??'' }}" />
                <c-textarea name="notes" label="Additional Notes" placeholder="Any additional notes or others" value="{{ old('notes')??'' }}" />




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
                @foreach ($appointments as $key => $appointment)
                <c-table.tr>
                    <c-table.td col="name">{{$appointment['patient_name']}}</c-table.td>
                    <c-table.td col="date-time" width="200px">{{$appointment['date']}} at
                        {{$appointment['time']}}</c-table.td>
                    <c-table.td col="location" width="200px">{{$appointment['location']}}</c-table.td>
                    <c-table.td col="doctor">{{$appointment['staff_name']}}</c-table.td>
                    <c-table.td col="status">
                        {{
                        $badgeType = '';

                        switch (strtolower($appointment['status'])) {
                        case 'pending':
                        $badgeType = 'yellow';
                        break;
                        case 'confirmed':
                        $badgeType = 'green';
                        break;
                        case 'cancelled':
                        case 'cancel_requested':
                        $badgeType = 'red';
                        break;
                        case 'reschedule_requested':
                        $badgeType = 'blue';
                        break;
                        case 'rescheduled':
                        $badgeType = 'purple';
                        break;
                        default:
                        $badgeType = 'primary';
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
                                        <c-modal.viewitem icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Appointment ID" info="{{ $appointment['id'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}"
                                            title="Requester Name" info="{{ $appointment['patient_name'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                            title="Date" info="{{ $appointment['date'] }} " />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                                            info="{{ $appointment['time'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}"
                                            title="Location" info="{{ $appointment['location'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/doctor.svg') }}" title="Doctor"
                                            info="{{ $appointment['staff_name'] }}" />
                                    </c-modal.viewcard>




                                    @if($appointment['purpose'])
                                    <c-modal.viewlist title="Purpose">
                                        <c-slot name="list">
                                            <li>{{ $appointment['purpose'] }}</li>
                                        </c-slot>
                                    </c-modal.viewlist>
                                    @endif

                                    @if($appointment['notes'])
                                    <c-modal.viewlist title="Notes">
                                        <c-slot name="list">
                                            @foreach($appointment['notes'] as $note)
                                            <li>{{ $note->note }}</li>
                                            @endforeach

                                        </c-slot>
                                    </c-modal.viewlist>
                                    @endif

                                    <c-slot name="close">
                                        Close
                                    </c-slot>

                                      
                                </c-modal>
                                <c-dropdown.sep />
                                <c-modal id="reschedule-appointmant-{{$key}}" size="md" :initOpen="flash('reschedule') === $appointment['id'] ? true : false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item> Reschedule Appointment </c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/profile.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Reschedule Appointment</div>
                                    </c-slot>

                                    <c-card class="info-card">
                                        <c-slot name="header">
                                            <span class="title">
                                                Current Appointment
                                            </span>
                                        </c-slot>
                                        <c-modal.viewcard>

                                            <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Requester Name" info="{{ $appointment['patient_name'] }}" />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                                title="Date" info="{{ $appointment['date'] }} " />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}"
                                                title="Time" info="{{ $appointment['time'] }}" />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}"
                                                title="Location" info="{{ $appointment['location'] }}" />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/doctor.svg') }}"
                                                title="Doctor" info="{{ $appointment['staff_name'] }}" />



                                        </c-modal.viewcard>


                                    </c-card>

                                    <form id="reschedule-appointment-form-{{$appointment['id']}}" action="{{ route('parent.appointment.reschedule', ['id' => $appointment['id']]) }}"
                                        method="POST">
                                        <c-input type="date" label="New Date" name="date" placeholder="Select Date"
                                            value="{{$appointment['date']}}" required />
                                        <c-select label="New Time" name="time" value="{{$appointment['time']}}" required>
                                            <li class="select-item" data-value="09:00">09.00 AM</li>
                                            <li class="select-item" data-value="10:00">10.00 AM</li>
                                            <li class="select-item" data-value="11:00">11.00 AM</li>
                                            <li class="select-item" data-value="12:00">12.00 PM</li>
                                        </c-select>
                                        <c-textarea name="reason" label="Reason for Rescheduling"
                                            placeholder="Enter your reason" required />
                                        <c-textarea name="notes" label="Additional Notes"
                                            placeholder="Any additional notes or others" />
                                    </form>

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button type="submit" form="reschedule-appointment-form-{{$appointment['id']}}" variant="primary">
                                            Submit Request
                                        </c-button>
                                    </c-slot>
                                </c-modal>

                                <c-modal id="cancel-appointmant-{{$key}}" size="sm" :initOpen="flash('cancel') === $appointment['id'] ? true : false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item> Cancel Appointment </c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.43484 8.56878C6.44624 5.00966 7.45193 3.2301 8.83197 2.77202C9.59117 2.52 10.409 2.52 11.1682 2.77202C12.5482 3.2301 13.5539 5.00966 15.5653 8.56878C17.5767 12.1279 18.5824 13.9075 18.2807 15.3575C18.1148 16.1552 17.7059 16.8787 17.1126 17.4244C16.0343 18.4163 14.0229 18.4163 10.0001 18.4163C5.97729 18.4163 3.96589 18.4163 2.88755 17.4244C2.29431 16.8787 1.88541 16.1552 1.71943 15.3575C1.41774 13.9075 2.42344 12.1279 4.43484 8.56878Z"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" />
                                            <path
                                                d="M4.43484 8.56878C6.44624 5.00966 7.45193 3.2301 8.83197 2.77202C9.59117 2.52 10.409 2.52 11.1682 2.77202C12.5482 3.2301 13.5539 5.00966 15.5653 8.56878C17.5767 12.1279 18.5824 13.9075 18.2807 15.3575C18.1148 16.1552 17.7059 16.8787 17.1126 17.4244C16.0343 18.4163 14.0229 18.4163 10.0001 18.4163C5.97729 18.4163 3.96589 18.4163 2.88755 17.4244C2.29431 16.8787 1.88541 16.1552 1.71943 15.3575C1.41774 13.9075 2.42344 12.1279 4.43484 8.56878Z"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" />
                                            <path
                                                d="M10.2017 14.6667V11.3333C10.2017 10.9405 10.2017 10.7441 10.0797 10.622C9.95766 10.5 9.76125 10.5 9.36841 10.5"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M10.2017 14.6667V11.3333C10.2017 10.9405 10.2017 10.7441 10.0797 10.622C9.95766 10.5 9.76125 10.5 9.36841 10.5"
                                                stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9.99325 8H10.0007" stroke="#DC2626" stroke-opacity="0.9"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9.99325 8H10.0007" stroke="#DC2626" stroke-opacity="0.9"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </c-slot>

                                    <c-slot name="header">
                                        <span class="cancel">Cancel Appointment</span>

                                    </c-slot>
                                    <div class="msg">
                                        Are you sure you want to cancel this appointment? This action cannot be undone.
                                    </div>


                                    <c-card class="info-card">
                                        <c-slot name="header">
                                            <span class="title ">
                                                Current Appointment
                                            </span>
                                        </c-slot>
                                        <c-modal.viewcard>

                                            <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Requester Name" info="{{ $appointment['patient_name'] }}" />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                                title="Date" info="{{ $appointment['date'] }} " />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}"
                                                title="Time" info="{{ $appointment['time'] }}" />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}"
                                                title="Location" info="{{ $appointment['location'] }}" />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/doctor.svg') }}"
                                                title="Doctor" info="{{ $appointment['staff_name'] }}" />



                                        </c-modal.viewcard>


                                    </c-card>

                                    <form id="cancel-appointment-form-{{$appointment['id']}}"
                                        action="{{ route('parent.appointment.cancel', ['id' => $appointment['id']]) }}"
                                        method="POST">
                                        <c-textarea name="reason" label="Reason for Cancellation"
                                            placeholder="Enter your reason" required />
                                        <c-textarea name="notes" label="Additional Notes"
                                            placeholder="Any additional notes or others" />
                                    </form>

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button type="submit" form="cancel-appointment-form-{{$appointment['id']}}" variant="destructive">
                                            Cancel Appointment
                                        </c-button>



                                    </c-slot>
                                </c-modal>


                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
                @if(count($appointments) === 0)
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