@extends('layout/portal')

@section('title')
    PHM  Child Health
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/phm/childprofiles.css') }}">
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
    Health Records View
@endsection

@section('content')
    <?php
    $items = [
        ['Recorded at' => '2024-01-15 at 09.00 AM', 'Height' => '49.5 cm','Weight' => '3.3 kg', 'Head Circumference' =>'20 cm', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-16 at 09.15 AM', 'Height' => '42.5 cm','Weight' => '2.3 kg', 'Head Circumference' =>'23 cm', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-17 at 09.28 AM', 'Height' => '48.5 cm','Weight' => '3.4 kg', 'Head Circumference' =>'24 cm', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-13 at 08.00 AM', 'Height' => '43.6 cm','Weight' => '3.6 kg', 'Head Circumference' =>'20 cm', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-22 at 08.30 AM', 'Height' => '46.5 cm','Weight' => '3.4 kg', 'Head Circumference' =>'26 cm', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-18 at 09.45 AM', 'Height' => '41.7 cm','Weight' => '3.7 kg', 'Head Circumference' =>'22 cm', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-25 at 09.10 AM', 'Height' => '44.9 cm','Weight' => '3.9 kg', 'Head Circumference' =>'21 cm', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-12 at 09.00 AM', 'Height' => '43.5 cm','Weight' => '3.4 kg', 'Head Circumference' =>'22 cm', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-21 at 09.24 AM', 'Height' => '46.5 cm','Weight' => '3.5 kg', 'Head Circumference' =>'22 cm', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-14 at 09.00 AM', 'Height' => '48.5 cm','Weight' => '3.6 kg', 'Head Circumference' =>'20 cm', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-16 at 09.00 AM', 'Height' => '48.5 cm','Weight' => '2.9 kg', 'Head Circumference' =>'25 cm', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-22 at 09.00 AM', 'Height' => '43.5 cm','Weight' => '3.3 kg', 'Head Circumference' =>'23 cm', 'Health Status' => 'Good'],
    ];
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
            <c-modal id="add-health-record-modal" size="sm" :initOpen="false">
                <c-slot name="trigger">
                    <c-button variant="primary">
                        Add Record
                    </c-button>
                </c-slot>

                <c-slot name="headerPrefix">
                    <img src="{{ asset('assets/icons/profile-02.svg' )}}"/>
                </c-slot>

                <c-slot name="header">
                    <div>Add Health Records</div>
                </c-slot>

                <form id="add-health-record-form" action="">
                    <c-input type="text" label="Height:" placeholder="Enter Height of the Child (in cm)" required /><br>
                    <c-input type="text" label="Weight:" placeholder="Enter Weight of the Child (in kg)" required /><br>
                    <c-input type="text" label="Head Circumference:" placeholder="Enter Head Circumference of the Child (in cm)" required /><br>
                    <c-select label="Status:" name="permissions" multiple="1" searchable="1">
                        <li class="select-item" data-value="child">Good</li>
                        <li class="select-item" data-value="maternal">Bad</li>
                    </c-select><br>
                    <c-textarea label="Additional Notes:" placeholder="Enter any additional notes here..." rows="4"></c-textarea>
                </form>
                <c-slot name="close">
                        Close
                </c-slot>
                <c-slot name="footer">
                    <c-button type="submit" form="admin-register-form" variant="primary">Add Record</c-button>
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
                        <c-table.th sortable="1">Height</c-table.th>
                        <c-table.th sortable="1">Weight</c-table.th>
                        <c-table.th align="left" sortable="1">Head Circumference</c-table.th>
                        <c-table.th align="left">Health Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key=>$item)

                                  <?php
$badgeType = '';
if (strtolower($item['Health Status']) === "good")
    $badgeType = 'green';
elseif (strtolower($item['Health Status']) === "critical")
    $badgeType = 'purple';
elseif (strtolower($item['Health Status']) === "bad")
    $badgeType = 'red';

?>
                        <c-table.tr>
                            <c-table.td col="Recorded at">{{ $item['Recorded at'] }}</c-table.td>
                            <c-table.td col="Height">{{ $item['Height'] }}</c-table.td>
                            <c-table.td col="Weight">{{ $item['Weight'] }}</c-table.td>
                            <c-table.td col="Head Circumference">{{ $item['Head Circumference'] }}</c-table.td>
                            <c-table.td col="Health Status">
                                <c-badge type="{{ $badgeType }}">
                                    {{ $item['Health Status'] }}
                                </c-badge>
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
                                                    <c-badge type="success">{{$item['Health Status']}}</c-badge>
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Health Record</div>
                                            </c-slot>

                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile.svg') }}"
                                                    title="Record ID"
                                                    info="REC001"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/ruler.svg') }}"
                                                    title="Height"
                                                    info="{{ $item['Height'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Total Vaccinations"
                                                    info="2"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/body-weight.svg') }}"
                                                    title="Weight"
                                                    info="{{ $item['Weight'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Recorded At"
                                                    info="{{ $item['Recorded at'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/ruler.svg') }}"
                                                    title="Head Circumference"
                                                    info="{{ $item['Head Circumference'] }} "
                                                />
                                            </c-modal.viewcard>
                                            
                                            <h4>Additional Information</h4>
                                             <ul>
                                              <li>Nutrition Facts: Good</li>
                                              <li>Lorem Ipsum</li>
                                            </ul>  
                                            
                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        </c-slot>
                                        <c-dropdown.sep />
                                    <c-modal id="edit-health-record-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                     <c-dropdown.item>Edit Health Records</c-dropdown.item>
                                    </c-slot>
                                     <c-slot name="headerPrefix">
                                           <img src="{{ asset('assets/icons/profile.svg' )}}"/>
                                     </c-slot>
 
                                    <c-slot name="header">
                                            <div>Edit Health Records</div>
                                    </c-slot>

                        <form id="edit-health-record-form" action="">
                             <c-input type="text" label="Height:" placeholder="{{ $item['Height'] }}" required /><br>
                             <c-input type="text" label="Weight:" placeholder="{{ $item['Weight'] }}" required /><br>
                             <c-input type="text" label="Head Circumference:" placeholder="{{ $item['Head Circumference'] }}" required /><br>
                             <c-select label="Health Status:" multiple="1" Default="{{ $item['Health Status'] }}">
                              <option class="select-item" data-value="child">Good</option>
                              <option class="select-item" data-value="child">Bad</option>
                            </c-select><br>
                            <c-textarea label="Additional Notes:" placeholder="Nutrition Facts." rows="4"></c-textarea>
                        </form>

                        <c-slot name="close">
                        Close
                        </c-slot>
                        <c-slot name="footer">
                          <c-button type="submit" variant="primary" form="edit-health-record-form">Save Changes</c-button>
                        </c-slot>
                    </c-modal>
                <c-dropdown.sep />
                    <c-modal id="mark-as-invalid-record-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                     <c-dropdown.item>Mark as Invalid</c-dropdown.item>
                                    </c-slot>
                                     <c-slot name="headerPrefix">
                                           <img src="{{ asset('assets/icons/profile.svg' )}}"/>
                                     </c-slot>
 
                                    <c-slot name="header">
                                            <div>Mark as Invalid</div>
                                    </c-slot>

                                    <p>Are you sure you want to mark this record as invalid?</p>

                         <c-slot name="close">
                          cancel
                        </c-slot>
                        <c-slot name="footer">
                          <c-button  size="sm" variant="destructive">Mark</c-button>
                        </c-slot>
                    </c-modal>
                <c-dropdown.sep />
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