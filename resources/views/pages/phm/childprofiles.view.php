@extends('layout/portal')

@section('title')
    PHM  Child Profiles
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/phm/childprofiles.css') }}">
@endsection

@section('header')
   Child Profiles - Overview
@endsection

@section('content')
    <?php
    $items = [
        ['id' => 'C-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'D-123', 'name' => 'John peter',   'Age' => '7 months', 'Vaccination Status' => 'Over due'],
        ['id' => 'B-123', 'name' => 'Daniel Parker',  'Age' => '5 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'C-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'F-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'J-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'L-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'T-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'K-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'A-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
        ['id' => 'L-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Vaccination Status"]'>

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
            <c-button variant="primary" >
                Add Child Profile
            </c-button>
        </c-slot>
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1" width="230px">ID</c-table.th>
                        <c-table.th sortable="1" width="300px">Name</c-table.th>
                        <c-table.th sortable="1" width="250px">Age</c-table.th>
                        <c-table.th align="left">Vaccination Status</c-table.th>
                       <!-- <c-table.th align="center">Stock</c-table.th>-->
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="Age">{{ $item['Age'] }}</c-table.td>
                            <c-table.td col="Vaccination Status">{{ $item['Vaccination Status'] }}</c-table.td>
                            <!--<c-table.td col="stock" align="center">{{ $item['stock'] }}</c-table.td>-->
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
                                        <c-dropdown.item>View Child Profile</c-dropdown.item>
                                        <c-dropdown.item>Edit Child Profile</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-dropdown.item>View Growth Records</c-dropdown.item>
                                        <c-dropdown.item>View Health Records</c-dropdown.item>
                                        <c-dropdown.item>View Vaccination Records</c-dropdown.item>
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