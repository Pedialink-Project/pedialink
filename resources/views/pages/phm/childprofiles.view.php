@extends('layout/portal')

@section('title')
PHM Child Profiles
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/phm/child.css') }}">
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

<c-table.controls :columns='["ID","Name","Age","Vaccination Status","GS Devision"]'>

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
        <c-modal id="addChild" size="sm" :initOpen="flash('create') ? true : false">
            <c-slot name="trigger">
                <c-button variant="primary">
                    Add Child Profile
                </c-button>
            </c-slot>
            <c-slot name="headerPrefix">
                <img src="{{ asset('assets/icons/user-add--01.svg' )}}" />
            </c-slot>
            <c-slot name="header">
                <div>Add Child Profile</div>
            </c-slot>

            <form id="add-child-form" class="child-form" action="{{ route('phm.child.create') }}" method="POST">
                <c-input
                    type="text"
                    label="Child Full Name:"
                    name="name"
                    value="{{ old('name') ?? '' }}"
                    error="{{ errors('name') ?? '' }}"
                    placeholder="Enter Full Name"
                    required
                />
                <c-select label="GS Division" name="division" searchable="1" error="{{ errors('division') ?? '' }}" required>
                    <li class="select-item" data-value="borella">Borella</li>
                    <li class="select-item" data-value="dehiwala">Dehiwala</li>
                    <li class="select-item" data-value="morutuwa">Moratuwa</li>
                    <li class="select-item" data-value="ratmalana">Ratmalana</li>
                    <li class="select-item" data-value="wellawatta">Wellawatta</li>
                </c-select>
                <c-input type="date" label="Date of Birth:" name="dob" value="{{ old('dob') ?? '' }}" error="{{ errors('dob') ?? ''}}" required />
                <c-select label="Gender" name="gender" value="{{ old('gender') ?? '' }}" error="{{ errors('gender') ?? ''}}" required>
                    <li class="select-item" data-value="male">Male</li>
                    <li class="select-item" data-value="female">Female</li>
                </c-select>
            </form>
            <c-slot name="close">
                Close
            </c-slot>
            <c-slot name="footer">
                <c-button type="submit" form="add-child-form" variant="primary">Create a Child Profile</c-button>
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
                    <c-table.th>Gender</c-table.th>
                    <c-table.th>GS Division</c-table.th>
                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($children as $key => $child)
                    <c-table.tr>
                        <c-table.td col="id">{{ 'C-000' . $child['id'] }}</c-table.td>
                        <c-table.td col="name" class="child-col">{{ $child['name'] }}</c-table.td>
                        <c-table.td col="Age" class="child-col">{{ $child['age'] }}</c-table.td>
                        <c-table.td col="Gender">
                            @if (strtolower($child['gender']) === "male")
                                <c-badge type="green">
                                    {{ ucfirst($child['gender']) }}
                                </c-badge>
                            @elseif (strtolower($child['gender']) === "female")
                                <c-badge  type="purple">
                                    {{ ucfirst($child['gender']) }}
                                </c-badge>
                            @endif

                        </c-table.td>
                        <c-table.td col="GN Devision">{{ ucfirst($child['gs_division']) }}</c-table.td>
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
                                                info="C-000{{ $child['id'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Name"
                                                info="{{ $child['name'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                title="Total Vaccinations"
                                                info="2"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                title="Age"
                                                info="{{ $child['age'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/location-05.svg') }}"
                                                title="GS Division"
                                                info="{{ ucfirst($child['gs_division']) }}"
                                            />
                                             <c-modal.viewitem
                                                icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Gender"
                                                info="{{ ucfirst($child['gender']) }}"
                                            />
                                        </c-modal.viewcard>

                                        @if ($child['parent'])
                                            <div class="parent-link-group">
                                                <div class="parent-link-card">
                                                    <div class="name-group">
                                                        <span class="parent-title">{{ $child['parent']['name'] }}</span>
                                                        <span class="parent-type">{{ ucfirst($child['parent']['type']) }}</span>
                                                    </div>
                                                    <c-badge type="green">
                                                        Linked
                                                    </c-badge>
                                                </div>
                                            </div>
                                        @else 
                                           <div class="parent-link-group">
                                                <div class="parent-link-card">
                                                    <div class="name-group">
                                                        <span class="parent-title">None</span>
                                                        <span class="parent-type">None</span>
                                                    </div>
                                                    <c-badge type="red">
                                                        Not Linked
                                                    </c-badge>
                                                </div>
                                            </div>    
                                        @endif

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
                                    <c-modal id="edit-child-profile-{{ $key }}" size="md" :initOpen="flash('edit') === $child['id'] ? true : false">
                                        <c-slot name="trigger">
                                            <c-dropdown.item>Edit Child Profile</c-dropdown.item>
                                        </c-slot>
                                        <c-slot name="headerPrefix">
                                            <img src="{{ asset('assets/icons/baby-01.svg' )}}" />
                                        </c-slot>
                                        <c-slot name="header">
                                            <div>Edit Child Profile</div>
                                        </c-slot>

                                        <form id="edit-child-profile-form-{{ $child['id'] }}" class="child-form" action="{{ route('phm.child.edit',['id'=>$child['id']]) }}" method="POST">
                                            <c-input
                                                type="text"
                                                label="Child Full Name:"
                                                name="e_name"
                                                value="{{ flash('edit') === $child['id'] ? (old('e_name') ?? '') : $child['name'] }}"
                                                error="{{ flash('edit') === $child['id'] ? (errors('e_name') ?? '') : '' }}"
                                                placeholder="Enter Full Name"
                                                required
                                            />
                                            <c-select label="GS Division" name="e_division" searchable="1" value="{{ flash('edit') === $child['id'] ? (old('e_division') ?? '') : $child['gs_division'] }}" error="{{ flash('edit') === $child['id'] ? (errors('e_division') ?? '') : '' }}" required>
                                                <li class="select-item" data-value="borella">Borella</li>
                                                <li class="select-item" data-value="dehiwala">Dehiwala</li>
                                                <li class="select-item" data-value="morutuwa">Moratuwa</li>
                                                <li class="select-item" data-value="ratmalana">Ratmalana</li>
                                                <li class="select-item" data-value="wellawatta">Wellawatta</li>
                                            </c-select>
                                            <c-input type="date" label="Date of Birth:" name="e_dob" value="{{ flash('edit') === $child['id'] ? (old('e_dob') ?? '') : $child['date_of_birth'] }}" error="{{ flash('edit') === $child['id'] ? (errors('e_dob') ?? '') : ''}}" required />
                                            <c-select label="Gender" name="e_gender" value="{{ flash('edit') === $child['id'] ? (old('e_gender') ?? '') : $child['gender'] }}" error="{{ errors('e_gender') ?? ''}}" required>
                                                <li class="select-item" data-value="male">Male</li>
                                                <li class="select-item" data-value="female">Female</li>
                                            </c-select>
                                        </form>
                                        <c-slot name="close">
                                            Close
                                        </c-slot>
                                        <c-slot name="footer">
                                            <c-button type="submit" form="edit-child-profile-form-{{ $child['id'] }}" variant="primary">
                                                Save Changes
                                            </c-button>
                                        </c-slot>
                                    </c-modal>
                                    <c-dropdown.sep />
                                    <c-dropdown.item href="{{ route('phm.growth.monitoring.child',['id'=>$key,])}}">
                                        View Growth Records
                                    </c-dropdown.item>
                                    <c-dropdown.item href="{{ route('phm.child.health.records',['id'=>$key,])}}">
                                        View Health Records
                                    </c-dropdown.item>
                                    <c-dropdown.item href="{{ route('phm.child.vaccinations',['id'=>$key,])}}">
                                        View Vaccination Records
                                    </c-dropdown.item>
                                    <c-dropdown-sep />
                                    <c-modal>
                                        <c-slot name="trigger">
                                            @if ($child['parent'])
                                                <c-dropdown.item class="disabled-delete-btn" disabled>Delete Child Profile</c-dropdown.item>
                                            @else
                                                <c-dropdown.item>Delete Child Profile</c-dropdown.item>
                                            @endif
                                        </c-slot>
                                        <c-slot name="header">
                                            <div>Delete Child Profile</div>
                                        </c-slot>

                                        <p>Do you want to delete this child profile?</p>
                                        <form id="delete-profile-{{ $child['id'] }}" class="hidden" action="{{ route('phm.child.delete',['id'=>$child['id']]) }}" method="POST">
                                        </form>
                                        <c-slot name="close">
                                            Close
                                        </c-slot>
                                        <c-slot name="footer">
                                            <c-button type="submit" form="delete-profile-{{ $child['id'] }}" variant="destructive">
                                                Delete
                                            </c-button>
                                        </c-slot>
                                    </c-modal>
                                </c-slot>
                            </c-dropdown.main>
                        </c-table.td>
                    </c-table.tr>
                @endforeach
                @if(count($children) === 0)
                    <tr>
                        <td colspan="6">
                            <div class="table-empty">No childs found</div>
                        </td>
                    </tr>
                @endif
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>

<c-table.pagination />
@endsection