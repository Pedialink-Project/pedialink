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
    <c-table.controls :columns='["Name","Email","Role","Status"]'>

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
                    @foreach ($users as $key => $user)
                        <c-table.tr>
                            <c-table.td col="name">{{ $user['name'] }}</c-table.td>
                            <c-table.td col="email">{{ $user['email'] }}</c-table.td>
                            <c-table.td col="role">
                                @if (strtolower($user["role"]) === "parent")
                                    <c-badge class="role-badge" type="purple">
                                        {{ ucfirst($user['role']) }}
                                    </c-badge>
                                @elseif (strtolower($user["role"]) === "doctor")
                                    <c-badge class="role-badge" type="green">
                                        {{ ucfirst($user['role']) }}
                                    </c-badge>
                                @elseif (strtolower($user["role"]) === "phm")
                                    <c-badge class="role-badge" type="blue">
                                        {{ strtoupper($user['role']) }}
                                    </c-badge>
                                @elseif (strtolower($user["role"]) === "admin")
                                    <c-badge class="role-badge" type="red">
                                        {{ ucfirst($user['role']) }}
                                    </c-badge>
                                @else
                                    <c-badge class="role-badge" type="destructive">
                                        Error
                                    </c-badge>
                                @endif
                                
                            </c-table.td>
                            <c-table.td col="price">
                                @if ($user["email_verified_at"])
                                    <c-badge type="success">Verified</c-badge>
                                @else 
                                    <c-badge type="red">Unverified</c-badge>
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

                                            @if (strtolower($user['role']) === "parent")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="P-{{ $user['id'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $user['name'] }}"
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
                                                        info="{{ ucfirst($user['role']) }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/mother.svg') }}"
                                                        title="Account Type"
                                                        info="{{ ucfirst($user['type']) }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                                        title="NIC"
                                                        info="{{ $user['nic'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/location-05.svg') }}"
                                                        title="Location"
                                                        info="{{ $user['area'] }}"
                                                    />
                                                </c-modal.viewcard>
                                            @elseif (strtolower($user['role']) === "phm")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="PHM-{{ $user['id'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $user['name'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                        title="Created On"
                                                        info="Monday, January 15, 2023"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                        title="License No"
                                                        info="{{ $user['license_no'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/security-validation.svg') }}"
                                                        title="Role"
                                                        info="Public Health Midwife"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                                        title="NIC"
                                                        info="{{ $user['nic'] }}"
                                                    />
                                                </c-modal.viewcard>
                                            @elseif (strtolower($user['role']) === "doctor")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="D-{{ $user['id'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $user['name'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                        title="Created On"
                                                        info="Monday, January 15, 2023"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                        title="License No"
                                                        info="{{ $user['license_no'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/security-validation.svg') }}"
                                                        title="Role"
                                                        info="Doctor"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                                        title="NIC"
                                                        info="{{ $user['nic'] }}"
                                                    />
                                                </c-modal.viewcard>
                                            @elseif (strtolower($user['role']) === "admin")
                                                <c-modal.viewcard>
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                        title="Profile"
                                                        info="A-{{ $user['id'] }}"
                                                    />
                                                    <c-modal.viewitem
                                                        icon="{{ asset('assets/icons/user.svg') }}"
                                                        title="Full Name"
                                                        info="{{ $user['name'] }}"
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
                                                        info="{{ ucfirst($user['type']) }} Admin"
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
                    @if(count($users) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection