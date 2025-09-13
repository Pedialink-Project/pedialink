@extends('layout/portal')

@section('title')
Parent - Appointments
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/appointments.css') }}">
@endsection

@section('header')

Appointments


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
        'notes'=>[
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
        'notes'=>[
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
        'notes'=>[
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
        'notes'=>[
            'Avoid wearing contact lenses on the day of the appointment.'
        ]
    ],

    
];

?>

<c-table.controls :columns='["Child","Date & Time ","Location","Doctor","Status"]'>

    <c-slot name="extrabtn">
        <c-link type="primary">
            <c-slot name="icon">
                <img src="{{ asset('assets/icons/profile.svg') }}" alt="">
            </c-slot>
            Requset Appointment </c-link>

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
                    <c-table.td col="date-time" width="200px">{{$appointmnet['date']}} at {{$appointmnet['time']}}</c-table.td>
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
                                <c-dropdown.item>View Details</c-dropdown.item>
                                <c-dropdown.sep />

                                <c-dropdown.item>
                                    Reschedule Appointment
                                </c-dropdown.item>
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