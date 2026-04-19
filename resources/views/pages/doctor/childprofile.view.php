@extends('layout/portal')

@section('title')
Doctor Child Profiles
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




<c-table.controls action="{{ route('doctor.child.profiles') }}" :filters="['area' => $areaFilters]">
</c-table.controls>

<c-table.wrapper card="1">
    <div class="table-wrapper" data-responsive="true">
        <c-table.main sticky="1" size="comfortable">
            <c-table.thead>
                <c-table.tr>
                    <c-table.th sortable="0">ID</c-table.th>
                    <c-table.th sortable="0">Name</c-table.th>
                    <c-table.th sortable="0">Age</c-table.th>
                    <c-table.th>Area</c-table.th>
                    <c-table.th>Assigned PHM</c-table.th>
                    <c-table.th class="table-actions">Actions</c-table.th>
                </c-table.tr>
            </c-table.thead>



            <c-table.tbody>

            @foreach ($children as $key => $child)
                <c-table.tr>
                    <c-table.td col="id">{{ display_entity_id('child', $child['id']) }}</c-table.td>
                    <c-table.td col="name" class="child-col">{{ $child['name'] }}</c-table.td>
                    <c-table.td col="age" class="child-col">{{ $child['age'] }}</c-table.td>
                    <c-table.td col="area">{{ $child['area'] ?? '-' }}</c-table.td>

                    <c-table.td col="assigned_phm">{{ $child['phm']['name'] }}</c-table.td>
                    <c-table.td class="table-actions" align="center">
                        <c-dropdown.main>
                            <c-slot name="trigger">
                                <c-button variant="ghost" class="dropdown-trigger">
                                    <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                </c-button>
                            </c-slot>
                            <c-slot name="menu">
                                <c-modal id="View-Child-{{ $key }}" size="md" :initOpen="false">
                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/baby-01.svg' )}}" />
                                    </c-slot>
                                    <c-slot name="trigger">
                                        <c-dropdown.item>View Child Profile</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="headerSuffix">
                                        @if($child['record'])
                                        @if (strtolower($child['record']['health_status']) === "good")
                                        <c-badge type="green">
                                            {{ ucwords(str_replace('_', ' ', $child['record']['health_status'])) }}
                                        </c-badge>
                                        @elseif (strtolower($child['record']['health_status']) === "at_risk")
                                        <c-badge type="yellow">
                                            {{ ucwords(str_replace('_', ' ', $child['record']['health_status'])) }}
                                        </c-badge>
                                        @elseif (strtolower($child['record']['health_status']) === "critical")
                                        <c-badge type="red">
                                            {{ ucwords(str_replace('_', ' ', $child['record']['health_status'])) }}
                                        </c-badge>
                                        @endif
                                        @endif
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Child Profile Details</div>
                                    </c-slot>

                                    <c-modal.viewcard>
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Child ID"
                                            info="{{ display_entity_id('child', $child['id']) }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/baby-01.svg') }}"
                                            title="Name"
                                            info="{{ $child['name'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/vaccine.svg') }}"
                                            title="Blood Type"
                                            info="{{$child['blood_type']}}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                            title="Age"
                                            info="{{ $child['age'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/location-05.svg') }}"
                                            title="GS Division"
                                            info="{{$child['area']}}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                            title="Assigned PHM"
                                            info="{{ $child['phm']['name'] }}" />
                                    </c-modal.viewcard>

                                    @if(!empty($child['parents']))

                                    <div class="parent-link-group">
                                        @foreach($child['parents'] as $parent)
                                        <div class="parent-link-card">
                                            <div class="name-group">
                                                <span class="parent-title">{{$parent['name']}}</span>
                                                <span class="parent-type">{{ucfirst($parent['type'])}}</span>
                                            </div>

                                        </div>
                                        @endforeach
                                    </div>
                                    @else
