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
    <?php
    $events = [
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => true, "status" => "completed"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => true, "status" => "completed"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => true, "status" => "completed"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => true, "status" => "completed"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => false, "status" => "upcoming"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => true, "status" => "completed"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => true, "status" => "upcoming"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => false, "status" => "cancelled"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => true, "status" => "upcoming"],
        ["title" => "Nutrition Workshop for New Parents", "date" => "2024-01-15 at 09.00 AM", "location" => "MOH Office Clinic", "visibility" => false, "status" => "cancelled"],
    ];
    ?>

    <c-table.controls :columns='["Title","Date & Time","Location","Visibility","Status"]'>
        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Status
            </c-button>
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Visibility
            </c-button>
        </c-slot>

        <c-slot name="extrabtn">
            <c-modal id="add-event-modal" size="sm" :initOpen="false">
                <c-slot name="trigger">
                    <c-button class="add-event-btn" variant="primary">
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
                        <span>Add Events</span>
                    </c-button>
                </c-slot>

                <c-slot name="headerPrefix">
                    <svg width="25" height="25" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_967_38088)">
                            <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z" stroke="#000000" stroke-width="1.5"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_967_38088">
                                <rect width="20" height="20" fill="black"/>
                            </clipPath>
                        </defs>
                    </svg>
                </c-slot>
                <c-slot name="header">
                    <div>Add Events</div>
                </c-slot>

                <form id="add-event" class="event-form" action="">
                    <c-input type="text" label="Title" placeholder="Enter event title" required />
                    <c-textarea label="Description" name="description" placeholder="Enter description of the event" required></c-textarea>
                    <div class="event-form-double-input">
                        <c-input label="Date" type="date" placeholder="Select Date" required />
                        <c-input label="Time" type="time" placeholder="Select Time" required />
                    </div>
                    <c-input type="number" label="Max Count" placeholder="Maximum Count" required />
                    <c-textarea label="Additional Note" name="additional" placeholder="Enter additional details"></c-textarea>
                </form>

                <c-slot name="close">
                    Close
                </c-slot>

                <c-slot name="footer">
                    <c-button type="submit" form="add-event" variant="primary">Create Event</c-button>
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
                        <c-table.th sortable="1">Date & Time</c-table.th>
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
                            <c-table.td class="event-tdata" col="date">{{ $event['date'] }}</c-table.td>
                            <c-table.td class="event-tdata" col="location">{{ $event['location'] }}</c-table.td>
                            <c-table.td class="event-tdata" col="visibility">
                                @if ($event["visibility"])
                                    <c-badge class="visibility-event" type="success">Visible</c-badge>
                                @else 
                                    <c-badge class="visibility-event" type="red">Hidden</c-badge>
                                @endif
                            </c-table.td>
                            <c-table.td class="event-tdata" col="status">
                                @if (strtolower($event['status']) === "completed")
                                    <c-badge class="status-event" type="success">{{ ucfirst($event['status']) }}</c-badge>
                                @elseif (strtolower($event['status']) === "upcoming")
                                    <c-badge class="status-event" type="primary">{{ ucfirst($event['status']) }}</c-badge>
                                @elseif (strtolower($event['status']) === "cancelled")
                                    <c-badge class="status-event" type="red">{{ ucfirst($event['status']) }}</c-badge>                                  
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
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Event ID"
                                                    info="EVE001"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="Location"
                                                    info="MOH Office Clinic"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                                    title="Registered"
                                                    info="45/200"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Created By"
                                                    info="A-1234"
                                                />
                                            </c-modal.viewcard>

                                            <div class="event-additional-content">
                                                <h4>Title</h4>
                                                <p>
                                                    Polio Vaccination Drive
                                                </p>
                                            </div>

                                            <div class="event-additional-content">
                                                <h4>Purpose of Visit</h4>
                                                <p>
                                                    Join us for a comprehensive polio vaccination drive targeting children under 5
                                                    years. This initiative is part of our ongoing effort to maintain polio-free
                                                    status in our community. Qualified healthcare professionals will administer the
                                                    vaccines following WHO guidelines.
                                                </p>
                                            </div>

                                            <div class="event-additional-content">
                                                <h4>Important Notes</h4>
                                                <ul>
                                                    <li>Bring child vaccination card</li>
                                                    <li>Valid ID for parent/guardian</li>
                                                    <li>Free entry</li>
                                                </ul>
                                            </div>

                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        <c-modal size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Edit Details</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <svg width="25" height="25" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_967_38088)">
                                                        <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z" stroke="#000000" stroke-width="1.5"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_967_38088">
                                                            <rect width="20" height="20" fill="black"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </c-slot>
                                            <c-slot name="header">
                                                <div>Edit Event Details</div>
                                            </c-slot>

                                            <form id="edit-event" class="event-form" action="">
                                                <c-input type="text" label="Title" placeholder="Enter event title" value="Polio Vaccination Drive" required />
                                                <c-textarea label="Description" name="description" placeholder="Enter description of the event" required>Ipsum Lorem</c-textarea>
                                                <div class="event-form-double-input">
                                                    <c-input label="Date" type="date" placeholder="Select Date" value="2025-10-17" required />
                                                    <c-input label="Time" type="time" placeholder="Select Time" value="11:30" required />
                                                </div>
                                                <c-input type="number" label="Max Count" placeholder="Maximum Count" value="300" required />
                                                <c-textarea label="Additional Note" name="additional" placeholder="Enter additional details"></c-textarea>
                                            </form>

                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" form="edit-event" variant="primary">Save Changes</c-button>
                                            </c-slot>
                                        </c-modal>
                                        <c-modal>
                                            <c-slot name="trigger">
                                                <c-dropdown.item>{{ $event['visibility'] ? 'Hide' : 'Show' }} Event</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>{{ $event['visibility'] ? 'Hide' : 'Show' }} Event Details</div>
                                            </c-slot>
                                            @if ($event["visibility"])
                                                <p>Do you want to hide <span class="event-visible-alert-highlight">Event No 123</span>?</p>
                                            @else 
                                                <p>Do you want to make <span class="event-visible-alert-highlight">Event No 123</span> visible?</p>   
                                            @endif
                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button variant="{{ $event['visibility'] ? 'destructive' : 'primary'}}" type="submit">
                                                    {{ $event['visibility'] ? 'Hide Event' : 'Show Event'}}
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
                                                Do you want to delete <span class="delete-event-highlight">Event No 123</span>?
                                            </p>

                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" variant="destructive">
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
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection