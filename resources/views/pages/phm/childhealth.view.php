@extends('layout/portal')

@section('title')
    Child Health Records
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/phm/child-health.css') }}">
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
    Health Records &#8594; C-000{{ $id +1 }}
@endsection

@section('content')
    <?php
    // $items = [
    //     ['Recorded at' => '2024-01-15 at 09.00 AM', 'Height' => '49.5 cm','Weight' => '3.3 kg', 'Head Circumference' =>'20 cm', 'Health Status' => 'Good'],
    //     ['Recorded at' => '2024-01-16 at 09.15 AM', 'Height' => '42.5 cm','Weight' => '2.3 kg', 'Head Circumference' =>'23 cm', 'Health Status' => 'Bad'],
    //     ['Recorded at' => '2024-01-17 at 09.28 AM', 'Height' => '48.5 cm','Weight' => '3.4 kg', 'Head Circumference' =>'24 cm', 'Health Status' => 'Good'],
    //     ['Recorded at' => '2024-01-13 at 08.00 AM', 'Height' => '43.6 cm','Weight' => '3.6 kg', 'Head Circumference' =>'20 cm', 'Health Status' => 'Bad'],
    //     ['Recorded at' => '2024-01-22 at 08.30 AM', 'Height' => '46.5 cm','Weight' => '3.4 kg', 'Head Circumference' =>'26 cm', 'Health Status' => 'Good'],
    //     ['Recorded at' => '2024-01-18 at 09.45 AM', 'Height' => '41.7 cm','Weight' => '3.7 kg', 'Head Circumference' =>'22 cm', 'Health Status' => 'Good'],
    //     ['Recorded at' => '2024-01-25 at 09.10 AM', 'Height' => '44.9 cm','Weight' => '3.9 kg', 'Head Circumference' =>'21 cm', 'Health Status' => 'Bad'],
    //     ['Recorded at' => '2024-01-12 at 09.00 AM', 'Height' => '43.5 cm','Weight' => '3.4 kg', 'Head Circumference' =>'22 cm', 'Health Status' => 'Bad'],
    //     ['Recorded at' => '2024-01-21 at 09.24 AM', 'Height' => '46.5 cm','Weight' => '3.5 kg', 'Head Circumference' =>'22 cm', 'Health Status' => 'Good'],
    //     ['Recorded at' => '2024-01-14 at 09.00 AM', 'Height' => '48.5 cm','Weight' => '3.6 kg', 'Head Circumference' =>'20 cm', 'Health Status' => 'Good'],
    //     ['Recorded at' => '2024-01-16 at 09.00 AM', 'Height' => '48.5 cm','Weight' => '2.9 kg', 'Head Circumference' =>'25 cm', 'Health Status' => 'Good'],
    //     ['Recorded at' => '2024-01-22 at 09.00 AM', 'Height' => '43.5 cm','Weight' => '3.3 kg', 'Head Circumference' =>'23 cm', 'Health Status' => 'Good'],
    // ];
    //var_dump($parentId);
    ?>

    <c-table.controls :columns='["Recorded at","Height","Weight","Head Circumference","Health Status"]'>

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
        <c-modal id="add-heath-record-modal" size="sm" :initOpen="false">
            <c-slot name="trigger">
                <c-button variant="primary">
                    Add Record
                </c-button>
            </c-slot>

            <c-slot name="headerPrefix">
                <img src="{{ asset('assets/icons/profile.svg' )}}" />
            </c-slot>

            <c-slot name="header">
                <div>Add Health Records</div>
            </c-slot>

            <form id="add-health-record-form" class="child-health-form"  action="{{ route('phm.child.health.add',['id'=>$id]) }}" method="POST">
                <c-input type="date" name="visit_date" label="Visited at:" placeholder="Enter Recorded Date"
                    error="{{ errors('visit_date') ?? '' }}" value="{{ old('visit_date')??'' }}" />
                <c-input type="text" name="height" label="Height :" placeholder="Enter Height of the Child (in cm)"
                    error="{{ errors('height') ?? '' }}" value="{{ old('height')??'' }}" />
                <c-input type="text" name="weight" label="Weight :"
                    placeholder="Enter Weight of the Child (in kg)" error="{{ errors('weight') ?? '' }}"
                    value="{{ old('weight')??'' }}" />
                <c-input type="text" name="head_circumference" label="Head Circumference:"
                    placeholder="Enter Head Circumference (in cm)" error="{{ errors('head_circumference') ?? '' }}"
                    value="{{ old('head_circumference')??'' }}" />
                <c-textarea name="notes" label="Additional Notes:"
                    placeholder="Enter any additional notes" error="{{ errors('notes') ?? '' }}"
                    value="{{ old('notes')??'' }}" />
            </form> 
            <c-slot name="close">
                Close
            </c-slot>
            <c-slot name="footer">
                <c-button type="submit" form="add-health-record-form" variant="primary">Add Record</c-button>
            </c-slot>
        </c-modal>
    </c-slot>
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1" >Recorded at</c-table.th>
                        <c-table.th sortable="1">Height(cm)</c-table.th>
                        <c-table.th sortable="1">Weight(kg)</c-table.th>
                        <c-table.th align="left" sortable="1">Head Circumference(cm)</c-table.th>
                        <c-table.th align="left">Health Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key=>$item)
                        <c-table.tr>
                            <c-table.td col="Recorded at">{{ $item['visit_date'] }}</c-table.td>
                            <c-table.td col="Height">{{ $item['height'] }}</c-table.td>
                            <c-table.td col="Weight">{{ $item['weight'] }}</c-table.td>
                            <c-table.td col="Head Circumference">{{ $item['head_circumference'] }}</c-table.td>
                            <c-table.td col="Health Status">
                                @if (strtolower($item['health_status']) === "good")
                                    <c-badge type="green">
                                        {{ $item['health_status'] }}
                                    </c-badge>
                                @elseif (strtolower($item['health_status']) === "critical")
                                    <c-badge type="purple">
                                        {{ $item['health_status'] }}
                                    </c-badge>
                                @elseif (strtolower($item['health_status']) === "bad")
                                    <c-badge type="red">
                                        {{ $item['health_status'] }}
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
                                         <c-modal id="Health-Record-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="headerPrefix">
                                                  <img src="{{ asset('assets/icons/profile.svg' )}}"/>
                                            </c-slot>

                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Record</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerSuffix">
                                                          @if (strtolower($item['health_status']) === "good")
                                                                <c-badge type="green">{{ $item['health_status'] }}</c-badge>                   
                                                          @elseif (strtolower($item['health_status']) === "critical")
                                                              <c-badge type="purple">{{ $item['health_status'] }}</c-badge>
                                                          @elseif (strtolower($item['health_status']) === "bad")
                                                              <c-badge type="red">{{ $item['health_status'] }}</c-badge>
                                                          
                                                          @endif
                                        </c-slot>

                                            <c-slot name="header">
                                                <div>Health Record</div>
                                            </c-slot>

                                            <c-modal.viewcard>
                                            <c-modal.viewitem icon="{{ asset('assets/icons/calendar-01.svg') }}"
                                                title="Recorded At" info="{{ $item['visit_date'] }}" />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Head Circumference(cm)" info="{{ $item['head_circumference'] }} " />    
                                            <!-- <c-modal.viewitem icon="{{ asset('assets/icons/ruler.svg') }}"
                                                title="BMI(kg/m2)" info="{{ $item['bmi'] }}" /> -->
                                            <!-- <c-modal.viewitem icon="{{ asset('assets/icons/blood-type.svg') }}"
                                                title="Height(cm)" info="{{ $item['height'] }} " /> -->
                                            <!-- <c-modal.viewitem icon="{{ asset('assets/icons/blood-type.svg') }}"
                                                title="Blood " info="{{ $item['blood_sugar'] }} " /> -->
                                            <c-modal.viewitem icon="{{ asset('assets/icons/body-weight.svg') }}"
                                                title="Weight(kg)" info="{{ $item['weight'] }}" />
                                            <c-modal.viewitem icon="{{asset('assets/icons/ruler.svg')}}"
                                                title="Height(cm)" info="{{ $item['height'] }} " />
                                            <c-modal.viewitem icon="{{ asset('assets/icons/ruler.svg') }}"
                                                title="BMI(kg/m2)" info="{{ $item['bmi'] }}" />    
                                            <!-- <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}"
                                                title="Head Circumference(cm)" info="{{ $item['head_circumference'] }} " />   -->
                                            <!-- <c-modal.viewitem icon="{{ asset('assets/icons/bubble-chat.svg') }}"
                                                title="Trimester" info="{{ $item['trimester'] }} " /> -->
                                            <c-modal.viewitem icon="{{ asset('assets/icons/filter.svg') }}"
                                                title="Health Status" info="{{ $item['health_status'] }} " />
                                             </c-modal.viewcard>

                                             <c-modal.viewlist title="Additional Information">
                                            <c-slot name="list">
                                            <li>{{ $item['notes'] }}</li>
                                            </c-slot>
                                        </c-modal.viewlist>
                                            
                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
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