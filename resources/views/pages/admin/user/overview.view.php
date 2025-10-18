@extends('layout/portal')

@section('title')
    User Management - Overview
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/user-overview.css') }}">
@endsection

@section('header')
    User Management - Overview
@endsection

@section('content')
    <?php
    $items = [
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'parent', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'phm', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'doctor', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'phm', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'parent', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'admin', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'admin', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'doctor', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'admin', 'status' => 'Pending Approval',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'role' => 'phm', 'status' => 'Pending Approval',],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Category","Date & Time","Status"]'>

        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Role
            </c-button>
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Status
            </c-button>
        </c-slot>

        <c-slot name="extrabtn">
            <c-modal id="registerStaff" size="sm" :initOpen="false">
                <c-slot name="trigger">
                    <c-button variant="primary">
                        Register Doctor/PHM
                    </c-button>
                </c-slot>

                <c-slot name="header">
                    <div>Register PHM/Doctor</div>
                </c-slot>

                <form id="staff-register-form" action="">
                    <c-input type="text" label="Email:" placeholder="Enter email" />
                    <c-select label="Role:" name="role">
                        <li class="select-item" data-value="phm">Public Health Midwife</li>
                        <li class="select-item" data-value="doctor">Doctor</li>
                    </c-select>
                    <c-textarea label="Message" placeholder="Enter invitation message"></c-textarea>
                </form>

                <c-slot name="close">
                    Close
                </c-slot>

                <c-slot name="footer">
                    <c-button type="submit" form="staff-register-form" variant="primary">Create Account</c-button>
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
                        <c-table.th sortable="1">Email</c-table.th>
                        <c-table.th sortable="1">Role</c-table.th>
                        <c-table.th>Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key => $item)
                        <c-table.tr>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="email">{{ $item['email'] }}</c-table.td>
                            <c-table.td col="role">
                                @if (strtolower($item["role"]) === "parent")
                                    <c-badge class="role-badge" type="purple">
                                        {{ ucfirst($item['role']) }}
                                    </c-badge>
                                @elseif (strtolower($item["role"]) === "doctor")
                                    <c-badge class="role-badge" type="green">
                                        {{ ucfirst($item['role']) }}
                                    </c-badge>
                                @elseif (strtolower($item["role"]) === "phm")
                                    <c-badge class="role-badge" type="blue">
                                        {{ strtoupper($item['role']) }}
                                    </c-badge>
                                @elseif (strtolower($item["role"]) === "admin")
                                    <c-badge class="role-badge" type="red">
                                        {{ ucfirst($item['role']) }}
                                    </c-badge>
                                @else
                                    <c-badge class="role-badge" type="destructive">
                                        Error
                                    </c-badge>
                                @endif
                                
                            </c-table.td>
                            <c-table.td col="price">{{ $item['status'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item>Copy ID</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-modal id="view-account-{{ $key }}" size="md" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Account Info</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Account Info</div>
                                            </c-slot>

                                            @if (strtolower($item['role']) === "parent")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="P-1234"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $item['name'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                        title="Created On"
                                                        info="Monday, January 15, 2023"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                        title="Approved By"
                                                        info="A-1310"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/security-validation.svg') }}"
                                                        title="Role"
                                                        info="Parent"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/mother.svg') }}"
                                                        title="Account Type"
                                                        info="Mother"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                                        title="NIC"
                                                        info="200301243423"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/location-05.svg') }}"
                                                        title="Location"
                                                        info="Borella"
                                                    />
                                                </c-modal.viewcard>
                                            @elseif (strtolower($item['role']) === "phm")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="PHM-1234"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $item['name'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                        title="Created On"
                                                        info="Monday, January 15, 2023"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                        title="Approved By"
                                                        info="A-1310"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/security-validation.svg') }}"
                                                        title="Role"
                                                        info="Public Health Midwife"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                                        title="NIC"
                                                        info="200301243423"
                                                    />
                                                </c-modal.viewcard>
                                            @elseif (strtolower($item['role']) === "doctor")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="D-1234"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $item['name'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                        title="Created On"
                                                        info="Monday, January 15, 2023"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                        title="Approved By"
                                                        info="A-1310"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/security-validation.svg') }}"
                                                        title="Role"
                                                        info="Doctor"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                                        title="NIC"
                                                        info="200301243423"
                                                    />
                                                </c-modal.viewcard>
                                            @elseif (strtolower($item['role']) === "admin")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="A-1234"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $item['name'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                        title="Created On"
                                                        info="Monday, January 15, 2023"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                        title="Created By"
                                                        info="None (Default)"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/security-validation.svg') }}"
                                                        title="Role"
                                                        info="Admin"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                                        title="Account Type"
                                                        info="Super Admin"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/payment-success-01.svg') }}"
                                                        title="Permissions"
                                                        info="Full Access"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/delete-02.svg') }}"
                                                        title="Removable"
                                                        info="False"
                                                    />
                                                </c-modal.viewcard>
                                            @else 
                                                Error
                                            @endif                                          

                                            <c-slot name="close">
                                                Close
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