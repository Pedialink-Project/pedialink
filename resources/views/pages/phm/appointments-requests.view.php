@extends('layout/portal')

@section('title')
    PHM  Appointments
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
   Appointment requests
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/phm/appointment.css') }}">
@endsection

@section('content')
    <?php
    $items = [
        ['id' => 'P-1345', 'name' => 'Nancy Drew', 'Age' => '28', 'Category' => 'Mother', 'Date & Time' =>'2023-01-19', 'Status' => 'conformed'],
        ['id' => 'D-1345', 'name' => 'John peter', 'Age' => '32', 'Category' => 'Mother', 'Date & Time' =>'2023-02-17', 'Status' => 'requested'],
        ['id' => 'S-1345', 'name' => 'Femke Bol', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-16', 'Status' => 'reshedulled'],
        ['id' => 'T-1345', 'name' => 'Daniel Parker', 'Age' => '1', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'reshudule_requested'],
        ['id' => 'R-1345', 'name' => 'Alice Smith', 'Age' => '31', 'Category' => 'Mother', 'Date & Time' =>'2023-03-01', 'Status' => 'canceled'],
        ['id' => 'I-1345', 'name' => 'Bob Johnson', 'Age' => '4', 'Category' => 'Baby', 'Date & Time' =>'2023-03-05', 'Status' => 'cancel_requested'],
        ['id' => 'N-1345', 'name' => 'Charlie Lee', 'Age' => '5', 'Category' => 'Baby', 'Date & Time' =>'2023-03-10', 'Status' => 'conformed'],
        ['id' => 'P-1345', 'name' => 'Diana King', 'Age' => '6', 'Category' => 'Baby', 'Date & Time' =>'2023-03-15', 'Status' => 'requested'],
        ['id' => 'W-1345', 'name' => 'Ethan Clark', 'Age' => '27', 'Category' => 'Mother', 'Date & Time' =>'2023-03-20', 'Status' => 'canceled'],
        ['id' => 'A-1345', 'name' => 'Fiona Adams', 'Age' => '38', 'Category' => 'Mother', 'Date & Time' =>'2023-03-25', 'Status' => 'reshedulled'],
        ['id' => 'A-1345', 'name' => 'George Baker', 'Age' => '9', 'Category' => 'Baby', 'Date & Time' =>'2023-03-30', 'Status' => 'conformed'],
        ['id' => 'D-1345', 'name' => 'Hannah Evans', 'Age' => '10', 'Category' => 'Baby', 'Date & Time' =>'2023-04-04', 'Status' => 'requested'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Category","Date & Time","Status"]'>

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
                        <c-table.th sortable="1" width="200px">Age(yrs)</c-table.th>
                        <c-table.th sortable="1" width="220px">Category</c-table.th>
                        <c-table.th align="left" sortable="1" width="200px">Date & Time</c-table.th>
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
                            <c-table.td col="Date & Time">{{ $item['Date & Time'] }}</c-table.td>
                            <c-table.td col="Status">
                                @if (strtolower($item["Status"]) === "conformed")
                                    <c-badge type="green">{{ $item['Status']}}</c-badge>
                                @elseif (strtolower($item["Status"]) === "requested")
                                    <c-badge type="purple">{{ $item['Status']}}</c-badge>
                                @elseif (strtolower($item["Status"]) === "canceled")
                                    <c-badge type="red">{{ $item['Status']}}</c-badge>
                                @else
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
                                            
                                            <h4>Additional Information</h4>
                                            <ul>
                                                <li>Nutrition Facts: Good</li>
                                                <li>Lorem Ipsum</li>
                                            </ul>  
                                            
                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        <c-dropdown.sep />
                                         <c-modal id="approve-appointment-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Approve Appointment</c-dropdown.item>
                                            </c-slot>
                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/user-add--01.svg' )}}"/>
                                            </c-slot>
        
                                            <c-slot name="header">
                                                Approve Appointment
                                            </c-slot>

                                            <form id="admin-register-form" action="">
                                                <c-textarea label="Additional Notes:" placeholder="Any additional notes or others" rows="4"></c-textarea>
                                            </form>
                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                            <c-slot name="footer">
                                                <c-button type="submit" variant="primary">Approve Appointment</c-button>
                                            </c-slot>
                                        </c-modal>
                                       
                                        <c-modal id="cancel-appointment-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Cancel Appointment</c-dropdown.item>
                                            </c-slot>
                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/user-add--01.svg' )}}"/>
                                            </c-slot>
        
                                            <c-slot name="header">
                                                Cancel Appointment
                                            </c-slot>

                                            <form id="admin-register-form" action="">
                                                <c-textarea label="Reason for Cancellation:" placeholder="Enter your reason..." rows="3"></c-textarea>
                                                <c-textarea label="Additional Notes:" placeholder="Any additional notes or others" rows="4"></c-textarea>
                                            </form>
                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                            <c-slot name="footer">
                                                <c-button type="submit" variant="destructive">Cancel Appointment</c-button>
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