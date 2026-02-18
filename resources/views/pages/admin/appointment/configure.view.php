@extends('layout/portal')

@section('title')
    Appoinments Configure
@endsection

@section('header')
    Appointments Configure
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/configure.css') }}">
@endsection

@section('content')

    <c-table.controls :filters="['status' => ['active', 'inactive']]" action="{{ route('admin.appointment.configure')}}">
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
                        <c-table.th sortable="1">Weekday</c-table.th>
                        <c-table.th>Start Time</c-table.th>
                        <c-table.th>End Time</c-table.th>
                        <c-table.th>Slot Length</c-table.th>
                        <c-table.th>Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($clinicWeeklyAvailability as $key => $availability)
                        <c-table.tr>
                            <c-table.td class="weekday-tdata" col="name">{{ $availability['weekday'] }}</c-table.td>
                            <c-table.td class="weekday-tdata" col="date">{{ $availability['start_time'] }}</c-table.td>
                            <c-table.td class="weekday-tdata" col="location">{{ $availability['end_time'] }}</c-table.td>
                            <c-table.td class="weekday-tdata" col="staff">{{ $availability['slot_length_minutes'] }} minutes</c-table.td>
                            <c-table.td class="weekday-tdata" col="status">
                                @if ($availability['active'])
                                    <c-badge class="status-weekday" type="green">Active</c-badge>
                                @else
                                    <c-badge class="status-weekday" type="red">Inactive</c-badge>                                 
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
                                        <c-modal size="md" :initOpen="flash('edit') == $availability['id'] ? true : false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Edit Details</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Edit Available Appointment day</div>
                                            </c-slot>

                                            <form id="edit-availability-{{ $key }}" class="edit-availability-form" action="{{ route('admin.appointment.configure.edit', ['id' => $availability['id']]) }}" method="POST">
                                                <c-input
                                                    type="time"
                                                    name="e_start_time"
                                                    label="Start Time"
                                                    value="{{ flash('edit') == $availability['id'] ? old('e_start_time') ?? '' : $availability['start_time'] }}"
                                                    error="{{ flash('edit') == $availability['id'] ? errors('e_start_time') ?? '' : '' }}"
                                                    required
                                                />
                                                <c-input
                                                    type="time"
                                                    name="e_end_time"
                                                    label="End Time"
                                                    value="{{ flash('edit') == $availability['id'] ? old('e_end_time') ?? '' : $availability['end_time'] }}"
                                                    error="{{ flash('edit') == $availability['id'] ? errors('e_end_time') ?? '' : '' }}"
                                                    required
                                                />
                                                <c-input
                                                    type="number"
                                                    name="e_slot_length_minutes"
                                                    label="Slot Length (minutes)"
                                                    value="{{ flash('edit') == $availability['id'] ? old('e_slot_length_minutes') ?? '' : $availability['slot_length_minutes'] }}"
                                                    error="{{ flash('edit') == $availability['id'] ? errors('e_slot_length_minutes') ?? '' : '' }}"
                                                    required
                                                />
                                            </form>
                                            
                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button form="edit-availability-{{ $key }}" type="submit" variant="primary">Save</c-button>
                                            </c-slot>
                                        </c-modal>
                                        @if ($availability['active'])
                                            <c-modal size="md" :initOpen="false">
                                                <c-slot name="trigger">
                                                    <c-dropdown.item>Disable Day</c-dropdown.item>
                                                </c-slot>

                                                <c-slot name="headerPrefix">
                                                    <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                                </c-slot>

                                                <c-slot name="header">
                                                    <div>Disable Appointment day</div>
                                                </c-slot>

                                                <p>
                                                    Do you want to disable {{ $availability['weekday'] }} from available appointment days? This will make all appointments on this day unavailable for booking.
                                                </p>
                                                
                                                <form id="disable-day-{{ $key }}" action="" method="post"></form>

                                                <c-slot name="close">
                                                    Close
                                                </c-slot>

                                                <c-slot name="footer">
                                                    <c-button form="disable-day-{{ $key }}" type="submit" variant="destructive">Disable</c-button>
                                                </c-slot>
                                            </c-modal>  
                                        @elseif (!$availability['active'])
                                            <c-modal size="md" :initOpen="false">
                                                <c-slot name="trigger">
                                                    <c-dropdown.item>Enable Day</c-dropdown.item>
                                                </c-slot>

                                                <c-slot name="headerPrefix">
                                                    <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                                </c-slot>

                                                <c-slot name="header">
                                                    <div>Enable Appointment day</div>
                                                </c-slot>

                                                <p>
                                                    Do you want to enable {{ $availability['weekday'] }} as an active day for appointments with time from {{ $availability['start_time'] }} to {{ $availability['end_time'] }} and slot length of {{ $availability['slot_length_minutes'] }} minutes?
                                                </p>
                                                
                                                <form id="enable-day-{{ $key }}" action="" method="post"></form>

                                                <c-slot name="close">
                                                    Close
                                                </c-slot>

                                                <c-slot name="footer">
                                                    <c-button form="enable-day-{{ $key }}" type="submit" variant="primary">Enable</c-button>
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

    @if(count($clinicWeeklyAvailability) === 0)
        <c-emptytable
            alt="No info"
            title="No Appointment Slots"
            description="There are currently no appointment slots available. Please check back later."
        />
    @endif
@endsection