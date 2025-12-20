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
    <c-table.controls action="{{ route('admin.user.admin') }}" :filters="['type' => ['super', 'data', 'user']]">

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
            <c-modal id="registerStaff" size="sm" :initOpen="flash('create') ? true : false">
                <c-slot name="trigger">
                    <c-button variant="primary">
                        Register Admin Role
                    </c-button>
                </c-slot>

                <c-slot name="header">
                    <div>Register Admin</div>
                </c-slot>

                <form id="admin-register-form" class="admin-form" action="{{ route('admin.user.admin.create') }}" method="POST">
                    <c-input
                        type="text"
                        label="Name"
                        name="name"
                        placeholder="Enter name"
                        value="{{ old('name') ?? '' }}"
                        error="{{ errors('name') ?? '' }}"
                        required />
                    <c-input
                        type="email"
                        label="Email"
                        name="email"
                        placeholder="Enter email"
                        value="{{ old('email') ?? '' }}"
                        error="{{ errors('email') ?? '' }}"
                        required />
                    <c-select label="Role" name="type" error="{{ errors('type') ?? '' }}" required>
                        <li class="select-item" data-value="super">Super Admin</li>
                        <li class="select-item" data-value="data">Data Admin</li>
                        <li class="select-item" data-value="user">User Admin</li>
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
                        <c-table.th sortable="1">ID</c-table.th>
                        <c-table.th sortable="1">Name</c-table.th>
                        <c-table.th sortable="1">Email</c-table.th>
                        <c-table.th sortable="1">Admin Type</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($admins as $key => $admin)
                        <c-table.tr>
                            <c-table.td col="id">A-{{ $admin['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $admin['name'] }}</c-table.td>
                            <c-table.td col="email">{{ $admin['email'] }}</c-table.td>
                            <c-table.td col="role">
                                @if (strtolower($admin["type"]) === "super")
                                    <c-badge class="role-badge" type="green">
                                        {{ ucfirst($admin['type']) }}
                                    </c-badge>
                                @elseif (strtolower($admin["type"]) === "data")
                                    <c-badge class="role-badge" type="purple">
                                        {{ ucfirst($admin['type']) }}
                                    </c-badge>
                                @elseif (strtolower($admin["type"]) === "user")
                                    <c-badge class="role-badge" type="blue">
                                        {{ ucfirst($admin['type']) }}
                                    </c-badge>
                                @else
                                    <c-badge class="role-badge" type="destructive">
                                        Error
                                    </c-badge>
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

                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Profile"
                                                    info="A-{{ $admin['id'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Full Name"
                                                    info="{{ $admin['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/security-validation.svg') }}"
                                                    title="Role"
                                                    info="Admin"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="Account Type"
                                                    info="{{ ucfirst($admin['type']) }} Admin"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/payment-success-01.svg') }}"
                                                    title="Permissions"
                                                    info="{{ $admin['type'] === 'super' ? 'Full' : 'Partial'}} Access"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/delete-02.svg') }}"
                                                    title="Removable"
                                                    info="{{ $admin['type'] !== 'super' ? 'True' : 'False' }}"
                                                />
                                            </c-modal.viewcard>                                        

                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        
                                        <c-modal id="edit-account-{{ $key }}" size="sm" :initOpen="flash('edit') === $admin['id'] ? true : false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Edit Account</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/edit-01.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Edit Account</div>
                                            </c-slot>

                                            <form id="admin-edit-form-{{ $admin['id'] }}" class="admin-form" action="{{ route('admin.user.admin.edit', ['id' => $admin['id']]) }}" method="POST">
                                                <c-input
                                                    type="text"
                                                    name="e_name"
                                                    label="Name"
                                                    placeholder="Enter name"
                                                    value="{{ flash('edit') === $admin['id'] ? (old('e_name') ?? '') : $admin['name'] }}"
                                                    error="{{ flash('edit') === $admin['id'] ? (errors('e_name') ?? '') : '' }}"
                                                />
                                                <c-input
                                                    type="email"
                                                    name="e_email"
                                                    label="Email"
                                                    placeholder="Enter email"
                                                    value="{{ flash('edit') === $admin['id'] ? (old('e_email') ?? '') : $admin['email'] }}"
                                                    error="{{ flash('edit') === $admin['id'] ? (errors('e_email') ?? '') : '' }}"
                                                />
                                                <c-select label="Role:" name="e_type" value="{{ $admin['type'] }}" error="{{ flash('edit') === $admin['id'] ? (errors('e_type') ?? '') : '' }}" >
                                                    <li class="select-item" data-value="super">Super Admin</li>
                                                    <li class="select-item" data-value="data">Data Admin</li>
                                                    <li class="select-item" data-value="user">User Admin</li>
                                                </c-select>
                                            </form>

                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>                                     

                                            <c-slot name="footer">
                                                <c-button type="submit" form="admin-edit-form-{{ $admin['id'] }}" variant="primary">Save Changes</c-button>
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
                                                Do you want to delete <span class="admin-type">{{ ucfirst($admin['type']) }} Admin</span> account of user <span class="admin-id">A-{{ $admin['id'] }}</span>?
                                            </p>

                                            <form id="delete-form-{{ $admin['id'] }}" action="{{ route('admin.user.admin.delete', ['id' => $admin['id']]) }}" class="hidden" method="POST"></form>
                                            
                                            <form id="admin-delete-form" action="" class="hidden"></form>
                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" form="delete-form-{{ $admin['id'] }}" variant="destructive">Delete Account</c-button>

                                            </c-slot>
                                        </c-modal>
                                    </c-slot>
                                </c-dropdown.main>
                            </c-table.td>
                        </c-table.tr>
                    @endforeach
                    @if(count($admin) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection