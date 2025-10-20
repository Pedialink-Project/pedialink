@extends('layout/portal')

@section('title')
Child Profiles
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/doctor/child.css') }}">
@endsection

@section('header')

<svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g clip-path="url(#clip0_474_12888)">
        <circle cx="9.99996" cy="10" r="8.33333" stroke="#141B34" stroke-width="1.5" />
        <path
            d="M11.6667 13.3333C11.1893 13.8599 10.6163 14.1667 10 14.1667C9.3838 14.1667 8.81075 13.8599 8.33337 13.3333"
            stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
        <path
            d="M7.50004 9.58333C7.26135 9.32006 6.97483 9.16666 6.66671 9.16666C6.35859 9.16666 6.07206 9.32006 5.83337 9.58333"
            stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
        <path
            d="M14.1667 9.58333C13.928 9.32006 13.6415 9.16666 13.3333 9.16666C13.0252 9.16666 12.7387 9.32006 12.5 9.58333"
            stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
        <path
            d="M10 1.66667C8.61929 1.66667 7.5 2.78596 7.5 4.16667C7.5 5.54738 8.61929 6.66667 10 6.66667C10.6403 6.66667 11.2244 6.42596 11.6667 6.03009"
            stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" />
    </g>
    <defs>
        <clipPath id="clip0_474_12888">
            <rect width="20" height="20" fill="white" />
        </clipPath>
    </defs>
</svg>
<span>Child Profiles - Overview
</span>

@endsection

@section('content')
<?php
$items = [
    ['id' => 'C-123', 'name' => 'Sarah Peter', 'Age' => '4 months', 'Vaccination Status' => 'Completed', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'D-123', 'name' => 'John Peter', 'Age' => '7 months', 'Vaccination Status' => 'Overdue', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'B-123', 'name' => 'Daniel Parker', 'Age' => '5 months', 'Vaccination Status' => 'Completed', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'C-124', 'name' => 'Emily Stone', 'Age' => '6 months', 'Vaccination Status' => 'Pending', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'F-125', 'name' => 'Michael Lee', 'Age' => '8 months', 'Vaccination Status' => 'Completed', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'J-126', 'name' => 'Olivia Brown', 'Age' => '3 months', 'Vaccination Status' => 'Completed', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'L-127', 'name' => 'Liam Smith', 'Age' => '9 months', 'Vaccination Status' => 'Overdue', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'T-128', 'name' => 'Sophia Green', 'Age' => '2 months', 'Vaccination Status' => 'Completed', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'K-129', 'name' => 'Noah White', 'Age' => '10 months', 'Vaccination Status' => 'Pending', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'A-130', 'name' => 'Ava Black', 'Age' => '5 months', 'Vaccination Status' => 'Completed', 'assigned_phm' => 'Sarah Peters'],
    ['id' => 'L-131', 'name' => 'Mason Gray', 'Age' => '7 months', 'Vaccination Status' => 'Completed', 'assigned_phm' => 'Sarah Peters'],
];
?>

<c-table.controls :columns='["ID","Name","Age","Vaccination Status","GN Devision"]'>

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

    <c-slot name="extrabtn">
        <c-modal id="addChild" size="sm" :initOpen="false">
            <c-slot name="trigger">
                <c-button class="child-request-access-btn" variant="primary">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1044_31547)">
                            <path d="M10.417 18.3337H5.49272C4.2049 18.3337 3.18058 17.707 2.26088 16.8308C0.378129 15.0371 3.46933 13.6037 4.6483 12.9016C6.39897 11.8592 8.44769 11.4769 10.417 11.7546C11.1316 11.8554 11.8275 12.0431 12.5003 12.3177" stroke="#FAFAFA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.75 5.41699C13.75 7.48806 12.0711 9.16699 10 9.16699C7.92893 9.16699 6.25 7.48806 6.25 5.41699C6.25 3.34592 7.92893 1.66699 10 1.66699C12.0711 1.66699 13.75 3.34592 13.75 5.41699Z" stroke="#FAFAFA" stroke-width="1.5"/>
                            <path d="M15.4167 18.3333L15.4167 12.5M12.5 15.4167H18.3333" stroke="#FAFAFA" stroke-width="1.5" stroke-linecap="round"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_1044_31547">
                                <rect width="20" height="20" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                    <span>Request Access</span>
                </c-button>
            </c-slot>
            <c-slot name="headerPrefix">
                <img src="{{ asset('assets/icons/user-add--01.svg' )}}" />
            </c-slot>
            <c-slot name="header">
                <div>Request Child Profile Access</div>
            </c-slot>

            <form id="request-child-form" class="child-form" action="">
                <c-select label="Child Profile" name="options" searchable="1" placeholder="Select Child" required>
                    <li class="select-item" data-value="option1">Baby Sara</li>
                    <li class="select-item" data-value="option2">Baby Nancy</li>
                </c-select>
                <c-textarea label="Reason" placeholder="Enter reason for request" required></c-textarea>
            </form>
            <c-slot name="close">
                Close
            </c-slot>
            <c-slot name="footer">
                <c-button type="submit" form="request-child-form" variant="primary">Request Access</c-button>
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
                    <c-table.th sortable="1">Age</c-table.th>
                    <c-table.th>Vaccination Status</c-table.th>
                    <c-table.th>Assigned PHM</c-table.th>
                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($items as $key => $item)
                    <c-table.tr>
                        <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                        <c-table.td col="name" class="child-col">{{ $item['name'] }}</c-table.td>
                        <c-table.td col="Age" class="child-col">{{ $item['Age'] }}</c-table.td>
                        <c-table.td col="Vaccination Status">
                            @if (strtolower($item['Vaccination Status']) === "completed")
                                <c-badge class="status-vaccination" type="green">
                                    {{ ucfirst($item['Vaccination Status']) }}
                                </c-badge>
                            @elseif (strtolower($item['Vaccination Status']) === "upcoming")
                                <c-badge class="status-vaccination" type="purple">
                                    {{ ucfirst($item['Vaccination Status']) }}
                                </c-badge>
                            @elseif (strtolower($item['Vaccination Status']) === "overdue")
                                <c-badge class="status-vaccination" type="red">
                                    {{ ucfirst($item['Vaccination Status']) }}
                                </c-badge>
                            @elseif (strtolower($item['Vaccination Status']) === "pending")
                                <c-badge class="status-vaccination" type="yellow">
                                    {{ ucfirst($item['Vaccination Status']) }}
                                </c-badge>
                            @endif

                        </c-table.td>
                        <c-table.td col="assigned_phm">{{ $item['assigned_phm'] }}</c-table.td>
                        <c-table.td class="table-actions" align="center">
                            <c-dropdown.main>
                                <c-slot name="trigger">
                                    <c-button variant="ghost" class="dropdown-trigger">
                                        <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                    </c-button>
                                </c-slot>
                                <c-slot name="menu">
                                    <c-dropdown.item>Copy Child ID</c-dropdown.item>
                                    <c-dropdown.sep />
                                    <c-modal id="View-Child-{{ $key }}" size="md" :initOpen="false">
                                        <c-slot name="headerPrefix">
                                            <img src="{{ asset('assets/icons/baby-01.svg' )}}" />
                                        </c-slot>
                                        <c-slot name="trigger">
                                            <c-dropdown.item>View Child Profile</c-dropdown.item>
                                        </c-slot>

                                        <c-slot name="headerSuffix">
                                            <c-badge type="green">Good</c-badge>
                                        </c-slot>

                                        <c-slot name="header">
                                            <div>Child Profile Details</div>
                                        </c-slot>

                                        <c-modal.viewcard>
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/profile.svg') }}"
                                                title="Child ID"
                                                info="{{ $item['id'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Name"
                                                info="{{ $item['name'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                title="Total Vaccinations"
                                                info="2"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                title="Age"
                                                info="{{ $item['Age'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/location-05.svg') }}"
                                                title="GS Division"
                                                info="Borella"
                                            />
                                             <c-modal.viewitem
                                                icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                title="Assigned PHM"
                                                info="{{ $item['assigned_phm'] }}"
                                            />
                                        </c-modal.viewcard>

                                        <div class="parent-link-group">
                                            <div class="parent-link-card">
                                                <div class="name-group">
                                                    <span class="parent-title">Nicole Sanders</span>
                                                    <span class="parent-type">Mother</span>
                                                </div>
                                                <c-badge type="green">
                                                    Linked
                                                </c-badge>
                                            </div>
                                            <div class="parent-link-card">
                                                <div class="name-group">
                                                    <span class="parent-title">John Michael</span>
                                                    <span class="parent-type">Father</span>
                                                </div>
                                                <c-badge type="green">
                                                    Linked
                                                </c-badge>
                                            </div>
                                        </div>

                                        <c-modal.viewlist title="Medical Records">
                                            <c-slot name="list">
                                                <li>Height: 49.5 cm</li>
                                                <li>Weight: 3.4 kg</li>
                                                <li>BMI Value: 3.5</li>
                                            </c-slot>
                                        </c-modal.viewlist>

                                        <c-modal.viewlist title="Recent Vaccinations">
                                            <c-slot name="list">
                                                <li>BCG - Dose 1 at 13th of July 2023</li>
                                                <li>BCG - Dose 2 at 28th of September 2023</li>
                                            </c-slot>
                                        </c-modal.viewlist>

                                        <c-modal.viewlist title="Other Information">
                                            <c-slot name="list">
                                                <li>Nutrition facts: Lorem Ipsum</li>
                                                <li>Lorem Ipsum</li>
                                            </c-slot>
                                        </c-modal.viewlist>

                                        <c-slot name="close">
                                            Close
                                        </c-slot>

                                        <c-slot name="footer">
                                            <c-button variant="primary">
                                                <img src="{{ asset('assets/icons/download-04.svg')}}" />
                                                Download documents
                                            </c-button>
                                        </c-slot>
                                    </c-modal>                                    
                                    <c-dropdown.item href="{{ route('doctor.child.health', ['id' => $key])}}">
                                        View Health Records
                                    </c-dropdown.item>
                                    <c-dropdown.item href="{{ route('doctor.child.vaccination', ['id' => $key]) }}">
                                        View Vaccination Records
                                    </c-dropdown.item>
                                </c-slot>
                            </c-dropdown.main>
                        </c-table.td>
                    </c-table.tr>
                @endforeach
                @if(count($items) === 0)
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