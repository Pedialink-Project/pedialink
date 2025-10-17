@extends('layout/portal')

@section('title')
    User Management - Admin
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/user-admin.css')}}">
@endsection

@section('header')
    User Management - Admin
@endsection

@section('content')
    <?php
    $items = [
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'super', 'status' => 'Active',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'super', 'status' => 'Active',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'data', 'status' => 'Active',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'user', 'status' => 'Active',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'user', 'status' => 'Inactive',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'super', 'status' => 'Active',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'data', 'status' => 'Active',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'data', 'status' => 'Inactive',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'super', 'status' => 'Active',],
        ['name' => 'mani', 'email' => 'mani@gmail.com', 'type' => 'user', 'status' => 'Active',],        
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Category","Date & Time","Status"]'>

        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Type
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
                        Register Admin Role
                    </c-button>
                </c-slot>

                <c-slot name="header">
                    <div>Register Admin</div>
                </c-slot>

                <form id="admin-register-form" action="">
                    <c-input type="text" label="Email:" placeholder="Enter email" />
                    <c-select label="Role:" name="role">
                        <li class="select-item" data-value="super">Super Admin</li>
                        <li class="select-item" data-value="data">Data Admin</li>
                        <li class="select-item" data-value="user">User Admin</li>
                    </c-select>
                    <c-select label="Permissions:" name="permissions" multiple="1" searchable="1">
                        <li class="select-item" data-value="child">Child</li>
                        <li class="select-item" data-value="maternal">Maternal</li>
                        <li class="select-item" data-value="events">Events</li>
                        <li class="select-item" data-value="appointments">Appointments</li>
                    </c-select>
                </form>

                <c-slot name="close">
                    Close
                </c-slot>

                <c-slot name="footer">
                    <c-button type="submit" form="admin-register-form" variant="primary">Create Account</c-button>
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
                        <c-table.th sortable="1">Admin Type</c-table.th>
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
                                @if (strtolower($item["type"]) === "super")
                                    <c-badge class="role-badge" type="green">
                                        {{ ucfirst($item['type']) }}
                                    </c-badge>
                                @elseif (strtolower($item["type"]) === "data")
                                    <c-badge class="role-badge" type="purple">
                                        {{ ucfirst($item['type']) }}
                                    </c-badge>
                                @elseif (strtolower($item["type"]) === "user")
                                    <c-badge class="role-badge" type="blue">
                                        {{ ucfirst($item['type']) }}
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
                                                    info="{{ ucfirst($item['type']) }} Admin"
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

                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        
                                        <c-modal id="edit-account-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Edit Account</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/edit-01.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Edit Account</div>
                                            </c-slot>

                                            <form id="admin-edit-form" action="">
                                                <c-input type="email" name="email" label="Email:" placeholder="Enter email" value="{{ $item['email'] }}" />
                                                <c-select label="Role:" name="role" value="{{ $item['type'] }}">
                                                    <li class="select-item" data-value="super">Super Admin</li>
                                                    <li class="select-item" data-value="data">Data Admin</li>
                                                    <li class="select-item" data-value="user">User Admin</li>
                                                </c-select>
                                                <c-select label="Permissions:" name="permissions" value="child,maternal" multiple="1" searchable="1">
                                                    <li class="select-item" data-value="child">Child</li>
                                                    <li class="select-item" data-value="maternal">Maternal</li>
                                                    <li class="select-item" data-value="events">Events</li>
                                                    <li class="select-item" data-value="appointments">Appointments</li>
                                                </c-select>
                                            </form>

                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>                                     

                                            <c-slot name="footer">
                                                <c-button type="submit" for="admin-edit-form" variant="primary">Save Changes</c-button>
                                            </c-slot>
                                        </c-modal>

                                        <c-modal id="delete-account-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Delete Account</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/Trash.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Delete Account</div>
                                            </c-slot>

                                            <p class="delete-content">
                                                Do you want to delete <span class="admin-type">{{ ucfirst($item['type']) }} Admin</span> account of user <span class="admin-id">A-1023</span>?
                                            </p>
                                            
                                            <form id="admin-delete-form" action="" class="hidden"></form>
                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" form="admin-delete-from" variant="primary">Delete Account</c-button>

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