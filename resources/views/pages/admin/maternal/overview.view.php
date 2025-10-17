@extends('layout/portal')

@section('title')
    Maternal Profiles - Overview
@endsection

@section('header')
    Maternal Profiles - Overview
@endsection

@section('content')
    <?php
    $maternal = [
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],
        ['id' => 1234, 'name' => 'Nancy Williams',  'age' => 35, 'status' => 'antenatal'],

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
                        <c-table.th>Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($maternal as $key => $mother)
                        <c-table.tr>
                            <c-table.td col="id">C-{{ $mother['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $mother['name'] }}</c-table.td>
                            <c-table.td col="age">{{ $mother['age'] }} years</c-table.td>
                            <c-table.td col="age">{{ $mother['status'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item>Copy Maternal Profile ID</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-modal size="md" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Maternal Profile</c-dropdown.item>
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
                                                    info="{{ $mother['name'] }}"
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
                                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                                    title="Account Type"
                                                    info="Mother"
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
                                    </c-slot>
                                </c-dropdown.main>
                            </c-table.td>
                        </c-table.tr>
                    @endforeach
                    @if(count($maternal) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection