@extends('layout/portal')

@section('title')
Events & Campaigns
@endsection

@section('header')
Events & Campaigns
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/admin/event.css') }}">
@endsection

@section('content')


<c-table.controls action="{{ route('admin.event') }}" :filters="['Status' => ['upcoming', 'ongoing', 'completed', 'cancelled']]">


    <c-slot name="extrabtn">
        <c-modal id="add-event-modal" size="sm" :initOpen="flash('create') ? true : false">
            <c-slot name="trigger">
                <c-button class="add-event-btn" variant="primary">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_967_38088)">
                            <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935" stroke="#FAFAFA"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z"
                                stroke="#FAFAFA" stroke-width="1.5" />
                        </g>
                        <defs>
                            <clipPath id="clip0_967_38088">
                                <rect width="20" height="20" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <span>Add Events</span>
                </c-button>
            </c-slot>

            <c-slot name="headerPrefix">
                <svg width="25" height="25" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_967_38088)">
                        <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935" stroke="#000000"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z"
                            stroke="#000000" stroke-width="1.5" />
                    </g>
                    <defs>
                        <clipPath id="clip0_967_38088">
                            <rect width="20" height="20" fill="black" />
                        </clipPath>
                    </defs>
                </svg>
            </c-slot>
            <c-slot name="header">
                <div>Add Events</div>
            </c-slot>

            <form id="add-event-form" class="event-form" action="{{route('admin.event.create')}}" method="POST"
                novalidate>
                <c-input type="text" label="Title" name="title" value="{{ old('title') ?? '' }}"
                    error="{{ errors('title') ?? '' }}" placeholder="Enter event title" required />
                <c-textarea label="Description" name="description" value="{{ old('description') ?? '' }}"
                    error="{{ errors('description') ?? '' }}" placeholder="Enter description of the event"
                    required></c-textarea>
                <div class="event-form-double-input">
                    <c-input label="Date" type="date" name="date" value="{{ old('date') ?? '' }}"
                        error="{{ errors('date') ?? '' }}" placeholder="Select Date" required />
                    <c-input label="Time" type="time" name="time" value="{{ old('time') ?? '' }}"
                        error="{{ errors('time') ?? '' }}" placeholder="Select Time" required />
                </div>
                <c-input type="text" label="Location" name="location" value="{{ old('location') ?? '' }}"
                    error="{{ errors('location') ?? '' }}" placeholder="Enter event location" required />
                <c-input type="number" label="Max Count" name="max_count" value="{{ old('max_count') ?? '' }}"
                    error="{{ errors('max_count') ?? '' }}" placeholder="Maximum Count" required />
                <c-input type="text" label="Purpose" name="purpose" value="{{ old('purpose') ?? '' }}"
                    error="{{ errors('purpose') ?? '' }}" placeholder="Enter event purpose" />
                <c-textarea label="Additional Note" name="notes" value="{{ old('notes') ?? '' }}"
                    error="{{ errors('notes') ?? '' }}" placeholder="Enter additional details"></c-textarea>
            </form>

            <c-slot name="close">
                Close
            </c-slot>

            <c-slot name="footer">
                <c-button type="submit" form="add-event-form" variant="primary">Create Event</c-button>
            </c-slot>
        </c-modal>
    </c-slot>
</c-table.controls>

<c-table.wrapper card="1">
    <div class="table-wrapper" data-responsive="true">
        <c-table.main sticky="1" size="comfortable">
            <c-table.thead>
                <c-table.tr>
                    <c-table.th sortable="1">Title</c-table.th>
                    <c-table.th sortable="1">Date</c-table.th>
                    <c-table.th sortable="1">Time</c-table.th>
                    <c-table.th sortable="1">Location</c-table.th>
                    <c-table.th sortable="1">Visibility</c-table.th>
                    <c-table.th>Status</c-table.th>
                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($events as $key => $event)
                <c-table.tr>
                    <c-table.td class="title-event-tdata" col="title">{{ $event['title'] }}</c-table.td>
                    <c-table.td class="event-tdata" col="date">{{ $event['event_date'] }} </c-table.td>
                    <c-table.td class="event-tdata" col="time">{{ $event['event_time'] }} </c-table.td>
                    <c-table.td class="event-tdata" col="location">{{ $event['event_location'] }}</c-table.td>
                    <c-table.td class="event-tdata" col="visibility">
                        @if ($event["visible"])
                        <c-badge class="visibility-event" type="green">Visible</c-badge>
                        @else
                        <c-badge class="visibility-event" type="red">Hidden</c-badge>
                        @endif
                    </c-table.td>
                    <c-table.td class="event-tdata" col="status">
                        @if (strtolower($event['event_status']) === "completed")
                        <c-badge class="status-event" type="green">{{ ucfirst($event['event_status']) }}</c-badge>
                        @elseif (strtolower($event['event_status']) === "upcoming")
                        <c-badge class="status-event" type="purple">{{ ucfirst($event['event_status']) }}</c-badge>
                        @elseif (strtolower($event['event_status']) === "cancelled")
                        <c-badge class="status-event" type="red">{{ ucfirst($event['event_status']) }}</c-badge>
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
                                <c-modal size="md" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>View Details</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/megaphone-02.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Event Details</div>
                                    </c-slot>

                                    <c-modal.viewcard>
                                        <c-modal.viewitem icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                            title="Date" info="{{ $event['event_date'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                                            info="{{ $event['event_time'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/profile-02.svg') }}"
                                            title="Event ID" info="{{ 'E-' . $event['id'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}"
                                            title="Location" info="{{ $event['event_location'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/mother.svg') }}"
                                            title="Registered"
                                            info="{{ $event['participants_count'] . '/' . $event['max_count'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/user.svg') }}" title="Created By"
                                            info="{{ $event['admin']['name'] }}" />
                                    </c-modal.viewcard>

                                    <c-modal.viewlist title="Purpose">
                                        <c-slot name="list">
                                            <li>{{ $event['purpose'] }}</li>
                                        </c-slot>
                                    </c-modal.viewlist>

                                    <c-modal.viewlist title="Notes">
                                        <c-slot name="list">

                                            <li>{{ $event['notes'] }}</li>

                                        </c-slot>
                                    </c-modal.viewlist>

                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                </c-modal>
                                <c-modal id="edit-event-modal-{{$key}}" size="sm"
                                    :initOpen="flash('edit') == $event['id'] ? true : false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Edit Details</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                        <svg width="25" height="25" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_967_38088)">
                                                <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935"
                                                    stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z"
                                                    stroke="#000000" stroke-width="1.5" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_967_38088">
                                                    <rect width="20" height="20" fill="black" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </c-slot>
                                    <c-slot name="header">
                                        <div>Edit Event Details</div>
                                    </c-slot>

                                    <form id="edit-event-form-{{$event['id']}}" class="event-form"
                                        action="{{ route('admin.event.edit', ['id' => $event['id']]) }}" method="POST"
                                        novalidate>
                                        <c-input type="text" label="Title" name="e_title"
                                            value="{{ flash('edit') == $event['id'] ? (old('e_title') ?? '') : $event['title'] }}"
                                            error="{{ flash('edit') == $event['id'] ? (errors('e_title') ?? '') : '' }}"
                                            placeholder="Enter event title" required />
                                        <div class="event-form-double-input">
                                            <c-input label="Date" type="date" name="e_date"
                                                value="{{ flash('edit') == $event['id'] ? (old('e_date') ?? '') : $event['event_date'] }}"
                                                error="{{ flash('edit') == $event['id'] ? (errors('e_date') ?? '') : '' }}"
                                                placeholder="Select Date" required />
                                            <c-input label="Time" type="time" name="e_time"
                                                value="{{ flash('edit') == $event['id'] ? (old('e_time') ?? '') : $event['event_time'] }}"
                                                error="{{ flash('edit') == $event['id'] ? (errors('e_time') ?? '') : '' }}"
                                                placeholder="Select Time" required />
                                        </div>
                                        <c-input type="text" label="Location" name="e_location"
                                            value="{{ flash('edit') == $event['id'] ? (old('e_location') ?? '') : $event['event_location'] }}"
                                            error="{{ flash('edit') == $event['id'] ? (errors('e_location') ?? '') : '' }}"
                                            placeholder="Enter event location" required />
                                        <c-input type="number" label="Max Count" name="e_max_count"
                                            value="{{ flash('edit') == $event['id'] ? (old('e_max_count') ?? '') : $event['max_count'] }}"
                                            error="{{ flash('edit') == $event['id'] ? (errors('e_max_count') ?? '') : '' }}"
                                            placeholder="Maximum Count" required />

                                    </form>

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button type="submit" form="edit-event-form-{{$event['id']}}"
                                            variant="primary">Save
                                            Changes</c-button>
                                    </c-slot>
                                </c-modal>
                                <c-modal>
                                    <c-slot name="trigger">
                                        <c-dropdown.item>{{ $event['visible'] ? 'Hide' : 'Show' }}
                                            Event</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>{{ $event['visible'] ? 'Hide' : 'Show' }} Event Details</div>
                                    </c-slot>
                                    <form id="edit-event-visible-form-{{$event['id']}}" action="{{ route('admin.event.edit.visible', ['id' => $event['id']]) }}" method="POST"></form>
                                    @if ($event["visible"])
                                    <p>Do you want to hide <span class="event-visible-alert-highlight">Event ID E-{{
                                            $event['id'] }}</span>?</p>
                                    @else
                                    <p>Do you want to make <span class="event-visible-alert-highlight">Event ID E-{{
                                            $event['id'] }}</span> visible?</p>
                                    @endif
                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button variant="{{ $event['visible'] ? 'destructive' : 'primary'}}"
                                            type="submit" form="edit-event-visible-form-{{$event['id']}}">
                                            {{ $event['visible'] ? 'Hide Event' : 'Show Event'}}
                                        </c-button>
                                    </c-slot>
                                </c-modal>
                                <c-modal>
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Delete Event</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="header">
                                        <div>Delete event</div>
                                    </c-slot>

                                    <p>
                                        Do you want to delete <span class="delete-event-highlight">Event ID E-{{
                                            $event['id'] }}</span>?
                                    </p>

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <form id="delete-event-form-{{ $event['id'] }}"
                                        action="{{ route('admin.event.delete', ['id' => $event['id']]) }}"
                                        method="POST"></form>

                                    <c-slot name="footer">
                                        <c-button type="submit" form="delete-event-form-{{ $event['id'] }}"
                                            variant="destructive">
                                            Delete Event
                                        </c-button>
                                    </c-slot>
                                </c-modal>
                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
                @if(count($events) === 0)
                <tr>
                    <td colspan="7">
                        <div class="table-empty">No events found</div>
                    </td>
                </tr>
@endif
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>
 <c-table.pagination :links="$links" />
@endsection