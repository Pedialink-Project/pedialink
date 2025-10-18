@extends('layout/portal')

@section('title')
    Vaccination Schedule
@endsection

@section('header')
    Vaccination Schedule
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/schedule.css') }}">
@endsection

@section('content')
    <?php
    $schedules = [
        ["id" => "0001", "name" => "Sri Lanka National EPI v1", "version" => "2015_v0001", "enabled" => true, "effective_from" => "2020-05-10"],
        ["id" => "0001", "name" => "Sri Lanka National EPI v2", "version" => "2015_v0001", "enabled" => false, "effective_from" => "2020-05-10"],
        ["id" => "0001", "name" => "Sri Lanka National EPI v1", "version" => "2015_v0001", "enabled" => false, "effective_from" => "2020-05-10"],
        ["id" => "0001", "name" => "Sri Lanka National EPI v1", "version" => "2015_v0001", "enabled" => false, "effective_from" => "2020-05-10"],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Version","Status","Effective From"]'>
        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Status
            </c-button>
        </c-slot>
        <c-slot name="extrabtn">
            <c-modal id="add-schedule-modal" size="sm" :initOpen="false">
                <c-slot name="trigger">
                    <c-button class="add-schedule-btn" variant="primary">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_967_38088)">
                                <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935" stroke="#FAFAFA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z" stroke="#FAFAFA" stroke-width="1.5"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_967_38088">
                                    <rect width="20" height="20" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                        <span>New Schedule</span>
                    </c-button>
                </c-slot>

                <c-slot name="headerPrefix">
                    <svg width="25" height="26" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 2.1665V3.83317M5 2.1665V3.83317" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.5 7.1665H17.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.08337 10.7027C2.08337 7.07161 2.08337 5.25607 3.12681 4.12803C4.17024 3 5.84962 3 9.20837 3H10.7917C14.1505 3 15.8298 3 16.8733 4.12803C17.9167 5.25607 17.9167 7.07161 17.9167 10.7027V11.1306C17.9167 14.7617 17.9167 16.5773 16.8733 17.7053C15.8298 18.8333 14.1505 18.8333 10.7917 18.8333H9.20837C5.84962 18.8333 4.17024 18.8333 3.12681 17.7053C2.08337 16.5773 2.08337 14.7617 2.08337 11.1306V10.7027Z" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.5 13H12.5M10 10.5L10 15.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                </c-slot>
                <c-slot name="header">
                    <div>Add New Schedule</div>
                </c-slot>

                <form id="add-schedule" class="schedule-form" action="">
                    <c-input type="text" label="Schedule Name" placeholder="Enter schedule name" required />
                    <c-input type="text" label="Schedule Version Number" placeholder="Enter schedule verison number" required />
                    <c-input type="date" label="Effective From" placeholder="Select Date" required />
                </form>

                <c-slot name="close">
                    Cancel
                </c-slot>

                <c-slot name="footer">
                    <c-button type="submit" form="add-schedule" variant="primary">Create Schedule</c-button>
                </c-slot>
            </c-modal>
        </c-slot>
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1">ID</c-table.th>
                        <c-table.th sortable="1">Name</c-table.th>
                        <c-table.th sortable="1">Version</c-table.th>
                        <c-table.th>Status</c-table.th>
                        <c-table.th sortable="1">Effective From</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($schedules as $key => $schedule)
                        <c-table.tr>
                            <c-table.td col="id">{{ $schedule['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $schedule['name'] }}</c-table.td>
                            <c-table.td col="version">{{ $schedule['version'] }}</c-table.td>
                            <c-table.td col="status">
                                @if ($schedule["enabled"])
                                    <c-badge type="green">
                                        Enabled
                                    </c-badge>
                                @else 
                                    <c-badge type="red">
                                        Disabled
                                    </c-badge>
                                @endif
                            </c-table.td>
                            <c-table.td col="effective_from">{{ $schedule['effective_from'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item>Copy Schedule ID</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-modal size="md" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Edit Schedule</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <svg width="25" height="26" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M15 2.1665V3.83317M5 2.1665V3.83317" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M2.5 7.1665H17.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M2.08337 10.7027C2.08337 7.07161 2.08337 5.25607 3.12681 4.12803C4.17024 3 5.84962 3 9.20837 3H10.7917C14.1505 3 15.8298 3 16.8733 4.12803C17.9167 5.25607 17.9167 7.07161 17.9167 10.7027V11.1306C17.9167 14.7617 17.9167 16.5773 16.8733 17.7053C15.8298 18.8333 14.1505 18.8333 10.7917 18.8333H9.20837C5.84962 18.8333 4.17024 18.8333 3.12681 17.7053C2.08337 16.5773 2.08337 14.7617 2.08337 11.1306V10.7027Z" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7.5 13H12.5M10 10.5L10 15.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>

                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Edit Vaccine</div>
                                            </c-slot>

                                            <form id="edit-schedule" class="schedule-form" action="">
                                                <c-input type="text" label="Schedule Name" placeholder="Enter schedule name" value="{{ $schedule['name'] }}" required />
                                                <c-input type="text" label="Schedule Version Number" placeholder="Enter schedule verison number" value="{{ $schedule['version'] }}" required />
                                                <c-input type="date" label="Effective From" placeholder="Select Date" value="{{ $schedule['effective_from'] }}" required />
                                            </form>
                                            
                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" form="edit-schedule" variant="primary">Save Changes</c-button>
                                            </c-slot>
                                        </c-modal>
                                        <c-modal>
                                            <c-slot name="trigger">
                                                <c-dropdown.item>{{ !$schedule["enabled"] ? "Enable" : "Disable"}} Schedule</c-dropdown.item>
                                            </c-slot>
                                            <c-slot name="header">
                                                {{ !$schedule["enabled"] ? "Enable" : "Disable"}} Schedule
                                            </c-slot>

                                            @if ($schedule["enabled"])
                                                <p>
                                                    <span class="schedule-warning-highlight">Warning:</span> Disabling a schedule must only be done during emergencies or maintenance mode. Prefer enabling a different schedule.
                                                </p>
                                                <p>
                                                    Do you want to disable <span class="delete-schedule-highlight">Schedule ID: {{ $schedule["id"] }}</span>?
                                                </p>
                                            @else 
                                                <p>
                                                    <span class="schedule-warning-highlight">Warning:</span> Enabling this schedule will disable previously enabled schedule!
                                                </p>
                                                <p>
                                                    Do you want to enable <span class="delete-schedule-highlight">Schedule ID: {{ $schedule["id"] }}</span>?
                                                </p>
                                            @endif

                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" variant="{{ !$schedule['enabled'] ? 'success' : 'destructive' }}">
                                                    {{ !$schedule["enabled"] ? "Enable" : "Disable"}} Schedule
                                                </c-button>
                                            </c-slot>
                                        </c-modal>  
                                        <c-dropdown.item href="{{ route('admin.vaccination.schedule.manage', ['schedule_id' => 1])}}">Manage Schedule</c-dropdown.item>
                                        <c-modal>
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Delete Schedule</c-dropdown.item>
                                            </c-slot>
                                            <c-slot name="header">
                                                <div>Delete Schedule</div>
                                            </c-slot>

                                            @if ($schedule["enabled"])
                                                <p>
                                                    <span class="schedule-warning-highlight">Warning:</span> You cannot delete a currently enabled schedule!
                                                </p>
                                            @else 
                                                <p>
                                                    <span class="schedule-warning-highlight">Warning:</span> Deleting a schedule that was previously used by the system is blocked. Prefer disabling the schedule instead!
                                                </p>

                                                <p>
                                                    Do you want to delete <span class="delete-schedule-highlight">Schedule ID: {{ $schedule["id"] }}</span>?
                                                </p>
                                            @endif

                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" variant="destructive">
                                                    Delete Schedule
                                                </c-button>
                                            </c-slot>
                                        </c-modal>                                    
                                    </c-slot>
                                </c-dropdown.main>
                            </c-table.td>
                        </c-table.tr>
                    @endforeach
                    @if(count($schedules) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection