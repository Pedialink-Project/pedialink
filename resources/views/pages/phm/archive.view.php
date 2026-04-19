@extends('layout/portal')

@section('title')
Archived Child Profiles
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
<span>Archived Child Profiles
</span>

@endsection

@section('content')

<c-table.controls :columns='["ID","Name","Age","Status","GN Division"]'>

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
                    <c-table.th sortable="1">ID</c-table.th>
                    <c-table.th sortable="1">Name</c-table.th>
                    <c-table.th sortable="1">Age</c-table.th>
                    <c-table.th align="left" sortable="1">Status</c-table.th>
                    <c-table.th align="left">Archive Reason</c-table.th>
                    <c-table.th align="left">Archived Date</c-table.th>
                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($children as $key => $child)
                    <c-table.tr>
                        <c-table.td col="id">{{ 'C-000' . $child['id'] + 1 }}</c-table.td>
                        <c-table.td col="name" class="child-col">{{ $child['name'] }}</c-table.td>
                        <c-table.td col="Age" class="child-col">{{ $child['age'] }}</c-table.td>
                        <c-table.td col="Status">
                            Archived
                        </c-table.td>
                        <c-table.td col="archive_reason">
                            {{ $child['archive_reason'] ? ucwords(str_replace('_', ' ', $child['archive_reason'])) : 'N/A' }}
                        </c-table.td>
                        <c-table.td col="archived_at">{{ $child['archived_at'] ? date('Y-m-d', strtotime($child['archived_at'])) : 'N/A' }}</c-table.td>
                        <c-table.td class="table-actions" align="center">
                            <c-dropdown.main>
                                <c-slot name="trigger">
                                    <c-button variant="ghost" class="dropdown-trigger">
                                        <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                    </c-button>
                                </c-slot>
                                <c-slot name="menu">
                                    <c-modal id="View-Archived-Child-{{ $key }}" size="md" :initOpen="false">
                                        <c-slot name="headerPrefix">
                                            <img src="{{ asset('assets/icons/baby-01.svg' )}}" />
                                        </c-slot>
                                        <c-slot name="trigger">
                                            <c-dropdown.item>View Profile</c-dropdown.item>
                                        </c-slot>

                                        <c-slot name="headerSuffix">
                                            @if($child['is_deceased'])
                                                <c-badge type="red">Deceased</c-badge>
                                            @else
                                                <c-badge type="purple">Archived</c-badge>
                                            @endif
                                        </c-slot>

                                        <c-slot name="header">
                                            <div>Archived Child Profile Details</div>
                                        </c-slot>

                                        <c-modal.viewcard>
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/profile.svg') }}"
                                                title="Child ID"
                                                info="{{ 'C-000' . ($child['id'] + 1) }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Name"
                                                info="{{ $child['name'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                title="Age"
                                                info="{{ $child['age'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/calendar-01.svg') }}"
                                                title="Date of Birth"
                                                info="{{ $child['date_of_birth'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/location-05.svg') }}"
                                                title="Area"
                                                info="{{ $child['area'] }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                title="Archived Date"
                                                info="{{ $child['archived_at'] ? date('Y-m-d', strtotime($child['archived_at'])) : 'N/A' }}"
                                            />
                                            <c-modal.viewitem
                                                icon="{{ asset('assets/icons/document-validation.svg') }}"
                                                title="Archive Reason"
                                                info="{{ $child['archive_reason'] ? ucwords(str_replace('_', ' ', $child['archive_reason'])) : 'N/A' }}"
                                            />
                                        </c-modal.viewcard>

                                        @if($child['parent'])
                                            <div class="parent-link-group">
                                                <div class="parent-link-card">
                                                    <div class="name-group">
                                                        <span class="parent-title">{{ $child['parent']['name'] }}</span>
                                                        <span class="parent-type">{{ $child['parent']['type'] }}</span>
                                                    </div>
                                                    <c-badge type="yellow">
                                                        Linked
                                                    </c-badge>
                                                </div>
                                            </div>
                                        @endif

                                        <c-slot name="close">
                                            Close
                                        </c-slot>
                                    </c-modal>
                                    <c-dropdown.sep />
                                    <c-dropdown.item href="{{ route('phm.child.health',['id'=>$child['id'],])}}">
                                        View Health Records
                                    </c-dropdown.item>
                                    <c-dropdown.sep/>
                                    <c-modal>
                                    <c-slot name="trigger">
                                        @if($child['is_deceased'])
                                            <c-dropdown.item class="disabled-delete-btn" disabled>Restore Child Profile</c-dropdown.item>
                                        @else
                                            <c-dropdown.item>Restore Child Profile</c-dropdown.item>
                                        @endif
                                    </c-slot>
                                    <c-slot name="header">
                                        <div>Restore Child Profile</div>
                                    </c-slot>

                                    @if($child['is_deceased'])
                                        <p>This child is marked as deceased and cannot be restored as an active profile.</p>
                                    @else
                                        <p>Do you want to restore this child profile?</p>
                                    @endif
                                    <form id="restore-profile-{{ $child['id'] }}" class="hidden"
                                        action="{{ route('phm.child.restore',['id'=>$child['id']]) }}" method="POST">
                                    </form>
                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                    <c-slot name="footer">
                                        <c-button type="submit" form="restore-profile-{{ $child['id'] }}"
                                            variant="destructive">
                                            Restore Profile
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
                        <td colspan="7">
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

<c-table.pagination />
@endsection
