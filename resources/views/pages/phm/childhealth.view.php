@extends('layout/portal')

@section('title')
    PHM  Child Health
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/phm/childprofiles.css') }}">
@endsection

@section('header')
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
            <c-modal id="registerStaff" size="sm" :initOpen="false">
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

                <form id="admin-register-form" action="">
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
                        <c-table.th sortable="1" width="230px">Recorded at</c-table.th>
                        <c-table.th sortable="1" width="220px">Height</c-table.th>
                        <c-table.th sortable="1" width="220px">Weight</c-table.th>
                        <c-table.th align="left" sortable="1" width="220px">Head Circumference</c-table.th>
                        <c-table.th align="left">Health Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key=>$item)
                        <c-table.tr>
                            <c-table.td col="Recorded at">{{ $item['Recorded at'] }}</c-table.td>
                            <c-table.td col="Height">{{ $item['Height'] }}</c-table.td>
                            <c-table.td col="Weight">{{ $item['Weight'] }}</c-table.td>
                            <c-table.td col="Head Circumference">{{ $item['Head Circumference'] }}</c-table.td>
                            <c-table.td col="Health Status">{{ $item['Health Status'] }}</c-table.td>
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
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Record ID"
                                                    info="12000"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Height"
                                                    info="{{ $item['Height'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Total Vaccinations"
                                                    info="2"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Weight"
                                                    info="{{ $item['Weight'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Recorded At"
                                                    info="{{ $item['Recorded at'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
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
                                    <c-modal id="edit-Health-Record-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                     <c-dropdown.item>Edit Health Records</c-dropdown.item>
                                    </c-slot>
                                     <c-slot name="headerPrefix">
                                           <img src="{{ asset('assets/icons/configuration-02.svg' )}}"/>
                                     </c-slot>
 
                                    <c-slot name="header">
                                            <div>Edit Health Records</div>
                                    </c-slot>

                        <form id="admin-register-form" action="">
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
                          <c-button type="button" variant="outline" data-modal-close="registerAdmin">Save Changes</c-button>
                        </c-slot>
                    </c-modal>
                <c-dropdown.sep />
                    <c-modal id="mark-as-invalid-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                     <c-dropdown.item>Mark as Invalid</c-dropdown.item>
                                    </c-slot>
                                     <c-slot name="headerPrefix">
                                           <img src="{{ asset('assets/icons/configuration-02.svg' )}}"/>
                                     </c-slot>
 
                                    <c-slot name="header">
                                            <div>Mark as Invalid</div>
                                    </c-slot>

                                    <p>Are you sure you want to mark this record as invalid?</p>

                         <c-slot name="close">
                          cancel
                        </c-slot>
                        <c-slot name="footer">
                          <c-button type="button" variant="destructive" data-modal-close="registerAdmin">Mark</c-button>
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