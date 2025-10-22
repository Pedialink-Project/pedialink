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
    <?php
    $appointments = [
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "completed"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "completed"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "pending"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "completed"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "overdue"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "completed"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "pending"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "overdue"],
        ["name" => "Sarah Johnson", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "staff" => "Dr Smith Watson", "status" => "completed"],
    ];
    ?>

    <c-table.controls :columns='["Name","Date & Time","Location","Staff","Status"]'>
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
                        <c-table.th sortable="1">Name</c-table.th>
                        <c-table.th sortable="1">Date & Time</c-table.th>
                        <c-table.th sortable="1">Location</c-table.th>
                        <c-table.th sortable="1">Staff</c-table.th>
                        <c-table.th>Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($appointments as $key => $appointment)
                        <c-table.tr>
                            <c-table.td class="appointment-tdata" col="name">{{ $appointment['name'] }}</c-table.td>
                            <c-table.td class="appointment-tdata" col="date">{{ $appointment['date'] }}</c-table.td>
                            <c-table.td class="appointment-tdata" col="location">{{ $appointment['location'] }}</c-table.td>
                            <c-table.td class="appointment-tdata" col="staff">{{ $appointment['staff'] }}</c-table.td>
                            <c-table.td class="appointment-tdata" col="status">
                                @if (strtolower($appointment['status']) === "completed")
                                    <c-badge class="status-appointment" type="green">{{ ucfirst($appointment['status']) }}</c-badge>
                                @elseif (strtolower($appointment['status']) === "upcoming")
                                    <c-badge class="status-appointment" type="primary">{{ ucfirst($appointment['status']) }}</c-badge>
                                @elseif (strtolower($appointment['status']) === "overdue")
                                    <c-badge class="status-appointment" type="red">{{ ucfirst($appointment['status']) }}</c-badge>
                                @elseif (strtolower($appointment['status']) === "pending")
                                    <c-badge class="status-appointment" type="yellow">{{ ucfirst($appointment['status']) }}</c-badge>                                  
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
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Requestor"
                                                    info="{{ $appointment['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Staff"
                                                    info="{{ $appointment['staff'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="Appointment ID"
                                                    info="Borella"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="Location"
                                                    info="MOH Office Clinic"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                                    title="Account Type"
                                                    info="Parent"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="Requestor GS Division"
                                                    info="Borella"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Date"
                                                    info="Monday, January 15, 2023"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/clock-01.svg') }}"
                                                    title="Time"
                                                    info="10:30 AM"
                                                />
                                            </c-modal.viewcard>

                                            <c-modal.viewlist title="Purpose of Visit">
                                                <c-slot name="list">
                                                    <li>Regular Checkup</li>
                                                </c-slot>
                                            </c-modal.viewlist>

                                            <c-modal.viewlist title="Important Notes">
                                                <c-slot name="list">
                                                    <li>Bring child vaccination card</li>
                                                    <li>Valid ID for parent/guardian</li>
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
                    @if(count($appointment) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection