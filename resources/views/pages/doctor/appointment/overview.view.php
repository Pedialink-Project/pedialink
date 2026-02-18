@extends('layout/portal')

@section('title')
    Appointments
@endsection

@section('header')
    <svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.08337 10C2.08337 6.26806 2.08337 4.40208 3.24274 3.24271C4.40211 2.08334 6.26809 2.08334 10 2.08334C13.732 2.08334 15.598 2.08334 16.7573 3.24271C17.9167 4.40208 17.9167 6.26806 17.9167 10C17.9167 13.732 17.9167 15.5979 16.7573 16.7573C15.598 17.9167 13.732 17.9167 10 17.9167C6.26809 17.9167 4.40211 17.9167 3.24274 16.7573C2.08337 15.5979 2.08337 13.732 2.08337 10Z" stroke="#3A3C41" stroke-width="1.5"/>
        <path d="M9.16663 5.83334L14.1666 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 5.83334L6.66671 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 10L6.66671 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 14.1667L6.66671 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M9.16663 10L14.1666 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M9.16663 14.1667L14.1666 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
   Appointments Details
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/doctor/appointment.css') }}">
@endsection

@section('content')
    <?php
    $items = [
        ['id' => 'P-1345', 'name' => 'Nancy Drew', 'Age' => '28', 'Category' => 'Mother', 'Date & Time' =>'2023-01-19', 'Status' => 'Upcoming'],
        ['id' => 'P-1345', 'name' => 'John peter', 'Age' => '32', 'Category' => 'Mother', 'Date & Time' =>'2023-02-17', 'Status' => 'Pending'],
        ['id' => 'C-1345', 'name' => 'Femke Bol', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-16', 'Status' => 'Completed'],
        ['id' => 'C-1345', 'name' => 'Daniel Parker', 'Age' => '1', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'overdue'],
        ['id' => 'P-1345', 'name' => 'Alice Smith', 'Age' => '31', 'Category' => 'Mother', 'Date & Time' =>'2023-03-01', 'Status' => 'Completed'],
        ['id' => 'C-1345', 'name' => 'Bob Johnson', 'Age' => '4', 'Category' => 'Baby', 'Date & Time' =>'2023-03-05', 'Status' => 'Pending'],
        ['id' => 'C-1345', 'name' => 'Charlie Lee', 'Age' => '5', 'Category' => 'Baby', 'Date & Time' =>'2023-03-10', 'Status' => 'Upcoming'],
        ['id' => 'P-1345', 'name' => 'Diana King', 'Age' => '6', 'Category' => 'Baby', 'Date & Time' =>'2023-03-15', 'Status' => 'Overdue'],
        ['id' => 'P-1345', 'name' => 'Ethan Clark', 'Age' => '27', 'Category' => 'Mother', 'Date & Time' =>'2023-03-20', 'Status' => 'Completed'],
        ['id' => 'P-1345', 'name' => 'Fiona Adams', 'Age' => '38', 'Category' => 'Mother', 'Date & Time' =>'2023-03-25', 'Status' => 'Pending'],
        ['id' => 'P-1345', 'name' => 'George Baker', 'Age' => '9', 'Category' => 'Baby', 'Date & Time' =>'2023-03-30', 'Status' => 'Upcoming'],
        ['id' => 'P-1345', 'name' => 'Hannah Evans', 'Age' => '10', 'Category' => 'Baby', 'Date & Time' =>'2023-04-04', 'Status' => 'Overdue'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Category","Status"]'>

        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Category
            </c-button>
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
                        <c-table.th sortable="1" width="200px">ID</c-table.th>
                        <c-table.th sortable="1" width="220px">Name</c-table.th>
                        <c-table.th sortable="1" width="200px">Age (yrs)</c-table.th>
                        <c-table.th sortable="1" width="220px">Category</c-table.th>
                        <c-table.th align="left" sortable="1" width="220px">Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key => $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="Age">{{ $item['Age'] }}</c-table.td>
                            <c-table.td col="Category">{{ $item['Category'] }}</c-table.td>
                            <c-table.td col="Status">
                                @if (strtolower($item["Status"]) === "completed")
                                    <c-badge type="yellow">{{ $item['Status']}}</c-badge>
                                @elseif (strtolower($item["Status"]) === "pending")
                                    <c-badge type="green">{{ $item['Status']}}</c-badge>
                                @elseif (strtolower($item["Status"]) === "overdue")
                                    <c-badge type="red">{{ $item['Status']}}</c-badge>
                                @elseif (strtolower($item["Status"]) === "upcoming")
                                    <c-badge type="purple">{{ $item['Status']}}</c-badge>
                                    
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
                                        <c-dropdown.item><b>Actions</c-dropdown.item>
                                        <c-modal id="View-appointment-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Appointment</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerSuffix">
                                                @if (strtolower($item["Status"]) === "completed")
                                                    <c-badge type="yellow">{{ $item['Status']}}</c-badge>
                                                @elseif (strtolower($item["Status"]) === "pending")
                                                    <c-badge type="green">{{ $item['Status']}}</c-badge>
                                                @elseif (strtolower($item["Status"]) === "overdue")
                                                    <c-badge type="red">{{ $item['Status']}}</c-badge>
                                                @elseif (strtolower($item["Status"]) === "upcoming")
                                                    <c-badge type="purple">{{ $item['Status']}}</c-badge>
                                                @endif    
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Appointment Details</div>
                                            </c-slot>

                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Record ID"
                                                    info="12000"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Name"
                                                    info="{{ $item['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Age"
                                                    info="2 Years"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Category"
                                                    info="{{ $item['Category'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Date & Time"
                                                    info="{{ $item['Date & Time'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Age"
                                                    info="{{ $item['Age'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Vaccination Status"
                                                    info="{{ $item['Status'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="Type of Vaccine"
                                                    info="B.C.G."
                                                />
                                            </c-modal.viewcard>
                                            
                                            <c-modal.viewlist title="Additional Information">
                                                <c-slot name="list">
                                                    <li>Nutrition Facts: Good</li>
                                                    <li>Lorem Ipsum</li>
                                                </c-slot>
                                            </c-modal.viewlist>
                                            
                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        <c-modal size="sm">
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
                                                        title="Requester Name" info="{{ $item['name'] }}" />
                                                    <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}"
                                                        title="Date" info="{{ $item['Date & Time'] }} " />
                                                    <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}"
                                                        title="Time" info="09:00 AM" />
                                                    <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}"
                                                        title="Location" info="RHU Centre" />
                                                </c-modal.viewcard>
                                            </c-card>

                                            <form
                                                action=""
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
                                                <c-button type="submit" form="cancel-appointment-form" variant="destructive">
                                                    Cancel Appointment
                                                </c-button>
                                            </c-slot>
                                        </c-modal>
                                    </c-slot>
                                </c-dropdown.main>
                            </c-table.td>
                        </c-table.tr>
                    @endforeach

                    @if(count($items) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection