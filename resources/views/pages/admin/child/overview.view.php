@extends('layout/portal')

@section('title')
    Child Profiles - Overview
@endsection

@section('header')
    Child Profiles - Overview
@endsection

@section('content')
    <?php
    $children = [
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
        ['id' => 1234, 'name' => 'Nancy Jekkins',  'age' => 2, 'assigned_phm' => 'Sarah Jones'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Assigned PHM"]'>

    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1">ID</c-table.th>
                        <c-table.th sortable="1">Name</c-table.th>
                        <c-table.th sortable="1" width="200px">Age</c-table.th>
                        <c-table.th>Assigned PHM</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($children as $key => $child)
                        <c-table.tr>
                            <c-table.td col="id">C-{{ $child['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $child['name'] }}</c-table.td>
                            <c-table.td col="age" width="200px">{{ $child['age'] }} months</c-table.td>
                            <c-table.td col="assigned_phm">{{ $child['assigned_phm'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item>Copy Child Profile ID</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-modal size="md" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Child Profile</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Profile Details</div>
                                            </c-slot>
                                            
                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Profile"
                                                    info="P-1234"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Full Name"
                                                    info="{{ $child['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Created On"
                                                    info="Monday, January 15, 2023"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                    title="Created By"
                                                    info="PHM-1310"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                    title="Account Type"
                                                    info="Baby"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="GS Division"
                                                    info="Borella"
                                                />
                                            </c-modal.viewcard>

                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        <c-dropdown.item href="{{ route('admin.child.access.control', ['id'=>$child['id']]) }}">
                                            Edit Access Control
                                        </c-dropdown.item>
                                        
                                    </c-slot>
                                </c-dropdown.main>
                            </c-table.td>
                        </c-table.tr>
                    @endforeach
                    @if(count($children) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection