@extends('layout/portal')

@section('title')
    PHM  Maternal Profiles
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/phm/childprofiles.css') }}">
@endsection

@section('header')
   Maternal Profiles - Overview
@endsection

@section('content')
    <?php
    $items = [
        ['id' => 'P-1345', 'name' => 'Nancy Drew','Age' => '28 yrs', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'F-7213', 'name' => 'Femke Bol','Age' => '22 yrs', 'Type' =>'Antenatal', 'Stage' => 'Second Trimester'],
        ['id' => 'S-3456', 'name' => 'Sophia Devs','Age' => '32 yrs', 'Type' =>'Antenatal', 'Stage' => 'Third Trimester'],
        ['id' => 'S-6543', 'name' => 'Sarah Peter','Age' => '23 yrs', 'Type' =>'Postnatal', 'Stage' => 'First Trimester'],
        ['id' => 'S-2345', 'name' => 'Shelly Ann','Age' => '29 yrs', 'Type' =>'Antenatal', 'Stage' => 'Second Trimester'],
        ['id' => 'E-4321', 'name' => 'Elain Thompson','Age' => '19 yrs', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'J-1235', 'name' => 'Jesica Colns','Age' => '25 yrs', 'Type' =>'Postnatal', 'Stage' => 'Second Trimester'],
        ['id' => 'S-4325', 'name' => 'Shacarri Richardson','Age' => '22 yrs', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'S-4567', 'name' => 'Sherika Jackson','Age' => '36 yrs', 'Type' =>'Postnatal', 'Stage' => 'First Trimester'],
        ['id' => 'J-1345', 'name' => 'Julia Ann','Age' => '21 yrs', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'S-2346', 'name' => 'Shiffan Hassan','Age' => '28 yrs', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'F-7213', 'name' => 'Femke Bol','Age' => '22 yrs', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Type","Stage"]'>

        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Type
            </c-button>
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Stage
            </c-button>
        </c-slot>

        <c-slot name="extrabtn">
            <c-button variant="primary" >
                Add Maternal Profile
            </c-button>
        </c-slot>
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1" width="200px">ID</c-table.th>
                        <c-table.th sortable="1" width="220px">Name</c-table.th>
                        <c-table.th sortable="1" width="220px">Age</c-table.th>
                        <c-table.th align="left" sortable="1" width="220px">Type</c-table.th>
                        <c-table.th align="left" sortable="1">Stage</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="Age">{{ $item['Age'] }}</c-table.td>
                            <c-table.td col="Type">{{ $item['Type'] }}</c-table.td>
                            <c-table.td col="Stage">{{ $item['Stage'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item>Copy Mother ID</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-dropdown.item>View Maternal Profile</c-dropdown.item>
                                        <c-dropdown.item>View Health Records</c-dropdown.item>
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