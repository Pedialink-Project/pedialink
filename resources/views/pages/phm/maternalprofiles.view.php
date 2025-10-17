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
        ['id' => 'P-1345', 'name' => 'Nancy Drew','Age' => '28 yrs', 'Address' => 'No 1, Main Street, Colombo', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'F-7213', 'name' => 'Femke Bol','Age' => '22 yrs', 'Address' => 'No 2, Lake Road, Kandy', 'Type' =>'Antenatal', 'Stage' => 'Second Trimester'],
        ['id' => 'S-3456', 'name' => 'Sophia Devs','Age' => '32 yrs', 'Address' => 'No 3, Park Avenue, Galle', 'Type' =>'Antenatal', 'Stage' => 'Third Trimester'],
        ['id' => 'S-6543', 'name' => 'Sarah Peter','Age' => '23 yrs', 'Address' => 'No 4, Beach Road, Negombo', 'Type' =>'Postnatal', 'Stage' => 'First Trimester'],
        ['id' => 'S-2345', 'name' => 'Shelly Ann','Age' => '29 yrs', 'Address' => 'No 5, Temple Lane, Matara', 'Type' =>'Antenatal', 'Stage' => 'Second Trimester'],
        ['id' => 'E-4321', 'name' => 'Elain Thompson','Age' => '19 yrs', 'Address' => 'No 6, Hill Street, Jaffna', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'J-1235', 'name' => 'Jesica Colns','Age' => '25 yrs', 'Address' => 'No 7, River Road, Kurunegala', 'Type' =>'Postnatal', 'Stage' => 'Second Trimester'],
        ['id' => 'S-4325', 'name' => 'Shacarri Richardson','Age' => '22 yrs', 'Address' => 'No 8, Market Street, Anuradhapura', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'S-4567', 'name' => 'Sherika Jackson','Age' => '36 yrs', 'Address' => 'No 9, Garden Road, Badulla', 'Type' =>'Postnatal', 'Stage' => 'First Trimester'],
        ['id' => 'J-1345', 'name' => 'Julia Ann','Age' => '21 yrs', 'Address' => 'No 10, College Avenue, Trincomalee', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'S-2346', 'name' => 'Shiffan Hassan','Age' => '28 yrs', 'Address' => 'No 11, Station Road, Batticaloa', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
        ['id' => 'F-7213', 'name' => 'Femke Bol','Age' => '22 yrs', 'Address' => 'No 12, Circular Road, Polonnaruwa', 'Type' =>'Antenatal', 'Stage' => 'First Trimester'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Address","Type","Stage"]'>

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
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1" width="160px">ID</c-table.th>
                        <c-table.th sortable="1" width="210px">Name</c-table.th>
                        <c-table.th sortable="1" width="200px">Age</c-table.th>
                        <c-table.th sortable="1" width="270px">Address</c-table.th>
                        <c-table.th align="left" sortable="1" width="220px">Type</c-table.th>
                        <c-table.th align="left" sortable="1">Stage</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key => $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="Age">{{ $item['Age'] }}</c-table.td>
                            <c-table.td col="Address">{{ $item['Address'] }}</c-table.td>
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
                                        <c-modal id="View-maternal-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Maternal Profile</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerSuffix">
                                                    <c-badge type="success">Good</c-badge>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                  <img src="{{ asset('assets/icons/profile.svg' )}}"/>
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Maternal Profile Details</div>
                                            </c-slot>

                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                                    title="Name"
                                                    info="{{ $item['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Age"
                                                    info="{{ $item['Age'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="Address"
                                                    info="{{ $item['Address'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                                    title="GS Devision"
                                                    info="Matara"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="NIC Number"
                                                    info="2300567890V"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/filter.svg') }}"
                                                    title="Type"
                                                    info="{{ $item['Type'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Pregnancy Stage"
                                                    info="{{ $item['Stage'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Pregnancy Duration"
                                                    info="5 weeks and 2 days"
                                                />
                                            </c-modal.viewcard>
                                             <h4>Medical Records</h4>
                                             <ul>
                                              <li>Height:160cm</li>
                                              <li>Weight:67kg</li>
                                              <li>Blood Group: O+</li>
                                              <li>Blood Sugar:110 mg/dL</li>
                                              <li>Blood Presure:120 mmHg</li>
                                              <li>Width of Belly: 32 cm</li>
                                            </ul>  
                                            <h4>Additional Information</h4>
                                             <ul>
                                              <li>Nutrition Facts: Good</li>
                                              <li>Allergies: None</li>
                                            </ul>  
                                            
                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        </c-slot>
                                        <c-dropdown.sep />
                                        <c-dropdown.item href="{{ route('phm.maternal.health',['id'=>$key,])}}">View Health Records</c-dropdown.item>
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