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

<c-table.controls action="{{ route('phm.child.profiles') }}" :filters="['access_status' => ['accepted', 'pending', 'not_requested', 'rejected'],'linked_status' => ['linked', 'not_linked']]">

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
                <c-input type="text" label="Child Full Name:" name="name" value="{{ old('name') ?? '' }}"
                    error="{{ errors('name') ?? '' }}" placeholder="Enter Full Name" required />
                <c-select label="Area" name="area" searchable="1" error="{{ errors('area') ?? '' }}" value="{{ old('area') ?? '' }}"
                    error="{{ errors('area') ?? '' }}" placeholder="Select Area">
                    @foreach ($areas as $area)
                    <li class="select-item" data-value="{{ $area['id'] }}">{{ $area['name'] }}</li>
                    @endforeach
                </c-select>
                <c-input type="date" label="Date of Birth:" name="date_of_birth" value="{{ old('date_of_birth') ?? '' }}"
                    error="{{ errors('date_of_birth') ?? ''}}" required />
                <c-input type="text" label="Birth Certificate No:" name="birth_certificate" value="{{ old('birth_certificate') ?? '' }}"
                    error="{{ errors('birth_certificate') ?? ''}}" placeholder="Enter Birth Certificate No" />
                <c-input type="text" label="Parent NIC:" name="parent_nic" value="{{ old('parent_nic') ?? '' }}"
                    error="{{ errors('parent_nic') ?? ''}}" placeholder="Enter Parent NIC" />
                <c-select label="Gender" name="gender" value="{{ old('gender') ?? '' }}"
                    error="{{ errors('gender') ?? ''}}" placeholder="Select Gender">
                    <li class="select-item" data-value="m">Male</li>
                    <li class="select-item" data-value="f">Female</li>
                </c-select>
                <c-select label="Blood Type" name="blood_type" value="{{ old('blood_type') ?? '' }}"
                    error="{{ errors('blood_type') ?? ''}}" placeholder="Select Blood Type">
                    @foreach(config('data.bloodTypes') as $bloodType)
                    <li class="select-item" data-value="{{ $bloodType }}">{{ $bloodType }}</li>
                    @endforeach
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
                    <c-table.th>ID</c-table.th>
                    <c-table.th>Name</c-table.th>
                    <c-table.th>Age</c-table.th>
                    <c-table.th>Gender</c-table.th>
                    <c-table.th>Area</c-table.th>
                    <c-table.th>Parent Link Status</c-table.th>
                    <c-table.th>Access</c-table.th>
                    <c-table.th class="table-actions">Actions</c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($children as $key => $child)

                <?php
                if (strtolower($child['gender']) === "m")
                    $gender = "Male";
                elseif (strtolower($child['gender']) === "f")
                    $gender = "Female";
               

               
                $selectedAreaId = null;

                foreach ($areas as $area) {
                if ($area['name'] === $child['area']) {
                $selectedAreaId = $area['id'];
                break;
                }
                }
                ?>

                <c-table.tr>
                    <c-table.td col="id">{{ 'C-00' . $child['id'] }}</c-table.td>
                    <c-table.td col="name" class="child-col">{{ $child['name'] }}</c-table.td>
                    <c-table.td col="Age" class="child-col">{{ $child['age'] }}</c-table.td>
                    <c-table.td col="Gender">
                        @if (strtolower($child['gender']) === "m")
                        <c-badge type="green">
                            Male
                        </c-badge>
                        @elseif (strtolower($child['gender']) === "f")
                        <c-badge type="purple">
                            Female
                        </c-badge>
                        @endif
                    </c-table.td>
                    <c-table.td col="area">{{ ucfirst($child['area']) }}</c-table.td>
                    <c-table.td col="linked_status"> @if (strtolower($child['linked_status']) === "linked")
                        <c-badge class="status-event" type="green">{{ ucfirst($child['linked_status']) }}</c-badge>
                        @elseif (strtolower($child['linked_status']) === "unlinked")
                        <c-badge class="status-event" type="red">{{ ucfirst($child['linked_status']) }}</c-badge>
                        @endif
                    </c-table.td>
                    <c-table.td col="access_status"> @if (strtolower($child['access_status']) === "accepted")
                        <c-badge class="status-event" type="green">{{ ucfirst($child['access_status']) }}</c-badge>
                        @elseif (strtolower($child['access_status']) === "pending")
                        <c-badge class="status-event" type="yellow">{{ ucfirst($child['access_status']) }}</c-badge>
                        @elseif (strtolower($child['access_status']) === "not_requested")
                        <c-badge class="status-event" type="purple">Not Requested</c-badge>
                        @elseif (strtolower($child['access_status']) === "rejected")
                        <c-badge class="status-event" type="red">{{ ucfirst($child['access_status'])}}
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
                                <c-dropdown.sep />
                                <c-modal id="View-Child-{{ $key }}" size="md" :initOpen="false">
                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/baby-01.svg' )}}" />
                                    </c-slot>
                                    <c-slot name="trigger">
                                        <c-dropdown.item>View Child Profile</c-dropdown.item>
                                    </c-slot>



                                    <c-slot name="header">
                                        <div>Child Profile Details</div>
                                    </c-slot>

                                    <c-modal.viewcard>
                                        <c-modal.viewitem icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Child ID" info="C-000{{ $child['id'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}" title="Name"
                                            info="{{ $child['name'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                            title="Age" info="{{ $child['age'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}"
                                            title="Area" info="{{ ucfirst($child['area']) }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}" title="Gender"
                                            info="{{$gender}} " />
                                        @if ($child['is_created'] )
                                        <c-modal.viewitem icon="{{ asset('assets/icons/blood-type.svg') }}"
                                            title="Blood Type" info="{{ $child['blood_type'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Birth Certificate No" info="{{ $child['birth_certificate'] }}" />
                                        <c-modal.viewitem icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Parent NIC" info="{{ $child['parent_nic'] }}" />
                                        @endif

                                    </c-modal.viewcard>

                                    @if ($child['linked_status'] === "linked")
                                    <div class=" parent-link-group">
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
                                                <span class="parent-title">No Parent Linked</span>
                                            </div>
                                            <c-badge type="red">
                                                Not Linked
                                            </c-badge>
                                        </div>
                                    </div>
                                    @endif

                                    @if ($child['access_status'] === "accepted")


                                    <c-modal.viewlist title="Latest Medical Records">
                                        @if($child['record'])
                                        <c-slot name="list">
                                            <li>Height:{{ $child['record']['height'] }}cm</li>
                                            <li>Weight: {{ $child['record']['weight'] }}kg</li>
                                            <li>BMI Value: {{ $child['record']['bmi'] }}</li>
                                            <li>Head circumference: {{ $child['record']['head_circumference'] }}cm</li>
                                        </c-slot>
                                        @else
                                        <c-slot name="list">
                                            <li>No medical records found.</li>
                                        </c-slot>
                                        @endif
                                    </c-modal.viewlist>

                                    <c-modal.viewlist title="Recent Vaccinations">
                                        <c-slot name="list">
                                            <li>BCG - Dose 1 at 13th of July 2023</li>
                                            <li>BCG - Dose 2 at 28th of September 2023</li>
                                        </c-slot>
                                    </c-modal.viewlist>


                                    @endif

                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                </c-modal>
                                @if ($child['is_created'])
                                <c-modal id="edit-child-profile-{{ $key }}" size="md"
                                    :initOpen="flash('edit') === $child['id'] ? true : false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Edit Child Profile</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/baby-01.svg' )}}" />
                                    </c-slot>
                                    <c-slot name="header">
                                        <div>Edit Child Profile</div>
                                    </c-slot>

                                    <form id="edit-child-profile-form-{{ $child['id'] }}" class="child-form"
                                        action="{{ route('phm.child.edit',['id'=>$child['id']]) }}" method="POST">
                                        <c-input type="text" label="Child Full Name:" name="e_name"
                                            value="{{ flash('edit') === $child['id'] ? (old('e_name') ?? '') : $child['name'] }}"
                                            error="{{ flash('edit') === $child['id'] ? (errors('e_name') ?? '') : '' }}"
                                            placeholder="Enter Full Name" required />
                                        <c-select label="Area" name="e_area" searchable="1"
                                            value="{{ flash('edit') === $child['id'] ? (old('e_area') ?? '') : $selectedAreaId }}"
                                            error="{{ flash('edit') === $child['id'] ? (errors('e_area') ?? '') : '' }}"
                                            required>
                                            @foreach ($areas as $area)
                                            <li class="select-item" data-value="{{ $area['id'] }}">{{ $area['name'] }}</li>
                                            @endforeach

                                        </c-select>
                                        <c-input type="date" label="Date of Birth:" name="e_date_of_birth"
                                            value="{{ flash('edit') === $child['id'] ? (old('e_date_of_birth') ?? '') : $child['date_of_birth'] }}"
                                            error="{{ flash('edit') === $child['id'] ? (errors('e_date_of_birth') ?? '') : ''}}"
                                            required />

                                        <c-select label="Gender" name="e_gender"
                                            value="{{ flash('edit') === $child['id'] 
                                             ? (old('e_gender') ?? ($child['gender']==='m' ? 'Male' : 'Female'))
                                             : ($child['gender'])  }}"
                                            error="{{ errors('e_gender') ?? ''}}">
                                            <li class="select-item" data-value="m">Male</li>
                                            <li class="select-item" data-value="f">Female</li>
                                        </c-select>
                                        <c-select label="Blood Type" name="e_blood_type"
                                            value="{{ flash('edit') === $child['id'] ? (old('e_blood_type') ?? '') : $child['blood_type'] }}"
                                            error="{{ errors('e_blood_type') ?? ''}}">
                                            @foreach(config('data.bloodTypes') as $bloodType)
                                            <li class="select-item" data-value="{{ $bloodType }}">{{ $bloodType }}</li>
                                            @endforeach
                                        </c-select>
                                    </form>
                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                    <c-slot name="footer">
                                        <c-button type="submit" form="edit-child-profile-form-{{ $child['id'] }}"
                                            variant="primary">
                                            Save Changes
                                        </c-button>
                                    </c-slot>
                                </c-modal>
                                @endif
                                <c-dropdown.sep />
                                @if ($child['access_status'] === "accepted")
                                <c-dropdown.item href="{{ route('phm.growth.monitoring.child',['id'=>$key,])}}">
                                    View Growth Records
                                </c-dropdown.item>
                                <c-dropdown.item href="{{ route('phm.child.health.records',['id'=>$key,])}}">
                                    View Health Records
                                </c-dropdown.item>
                                <c-dropdown.item href="{{ route('phm.child.vaccinations',['id'=>$key,])}}">
                                    View Vaccination Records
                                </c-dropdown.item>
                                @endif
                                <c-dropdown-sep />
                                @if ($child['is_created'])
                                <c-modal>
                                    <c-slot name="trigger">
                                        @if ($child['parent'])
                                        <c-dropdown.item class="disabled-delete-btn" disabled>Delete Child
                                            Profile</c-dropdown.item>
                                        @else
                                        <c-dropdown.item>Delete Child Profile</c-dropdown.item>
                                        @endif
                                    </c-slot>
                                    <c-slot name="header">
                                        <div>Delete Child Profile</div>
                                    </c-slot>

                                    <p>Do you want to delete this child profile?</p>
                                    <form id="delete-profile-{{ $child['id'] }}" class="hidden"
                                        action="{{ route('phm.child.delete',['id'=>$child['id']]) }}" method="POST">
                                    </form>
                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                    <c-slot name="footer">
                                        <c-button type="submit" form="delete-profile-{{ $child['id'] }}"
                                            variant="destructive">
                                            Delete
                                        </c-button>
                                    </c-slot>
                                </c-modal>
                                @endif
                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach
                @if(count($children) === 0)
                <tr>
                    <td colspan="8">
                        <c-emptytable
                            alt="No children found"
                            title="No Child Profiles Available"
                            description="No child profiles match your current search or filters. Try adjusting them to see more results." />
                    </td>
                </tr>
                @endif
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>

<c-table.pagination :links="$links" />
@endsection