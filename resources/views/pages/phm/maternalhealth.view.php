@extends('layout/portal')

@section('title')
    PHM  Maternal Health
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
        ['Recorded at' => '2024-01-15 at 09.00 AM', 'BMI' => '18.5','Blood Pressure' => '120/80 mmHg', 'Blood Sugar' =>'90 mg/dL', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-16 at 09.15 AM', 'BMI' => '22.3','Blood Pressure' => '130/85 mmHg', 'Blood Sugar' =>'110 mg/dL', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-17 at 09.28 AM', 'BMI' => '19.4','Blood Pressure' => '115/75 mmHg', 'Blood Sugar' =>'95 mg/dL', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-13 at 08.00 AM', 'BMI' => '24.6','Blood Pressure' => '140/90 mmHg', 'Blood Sugar' =>'130 mg/dL', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-22 at 08.30 AM', 'BMI' => '21.4','Blood Pressure' => '125/80 mmHg', 'Blood Sugar' =>'100 mg/dL', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-18 at 09.45 AM', 'BMI' => '23.7','Blood Pressure' => '135/88 mmHg', 'Blood Sugar' =>'120 mg/dL', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-25 at 09.10 AM', 'BMI' => '20.9','Blood Pressure' => '128/82 mmHg', 'Blood Sugar' =>'105 mg/dL', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-12 at 09.00 AM', 'BMI' => '22.5','Blood Pressure' => '132/86 mmHg', 'Blood Sugar' =>'115 mg/dL', 'Health Status' => 'Bad'],
        ['Recorded at' => '2024-01-21 at 09.24 AM', 'BMI' => '21.5','Blood Pressure' => '118/78 mmHg', 'Blood Sugar' =>'98 mg/dL', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-14 at 09.00 AM', 'BMI' => '19.6','Blood Pressure' => '122/80 mmHg', 'Blood Sugar' =>'92 mg/dL', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-16 at 09.00 AM', 'BMI' => '20.9','Blood Pressure' => '126/84 mmHg', 'Blood Sugar' =>'108 mg/dL', 'Health Status' => 'Good'],
        ['Recorded at' => '2024-01-22 at 09.00 AM', 'BMI' => '23.3','Blood Pressure' => '134/87 mmHg', 'Blood Sugar' =>'118 mg/dL', 'Health Status' => 'Good'],
    ];
    ?>

    <c-table.controls :columns='["Recorded at","BMI","Blood Pressure","Blood Sugar","Health Status"]'>

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
                    <c-input type="text" label="Recorded at:" placeholder="Enter Recorded Date & Time" required /><br>
                    <c-input type="text" label="BMI:" placeholder="Enter BMI of the Mother" required /><br>
                    <c-input type="text" label="Blood Pressure:" placeholder="Enter Blood Pressure of the Mother (in mmHg)" required /><br>
                    <c-input type="text" label="Blood Sugar:" placeholder="Enter Blood Sugar of the Mother (in mg/dL )" required /><br>
                    <c-select label="Status:" name="permissions" multiple="1" searchable="1">
                        <li class="select-item" data-value="child">Good</li>
                        <li class="select-item" data-value="maternal">Bad</li>
                    </c-select><br>
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
                        <c-table.th sortable="1" width="260px">Recorded at</c-table.th>
                        <c-table.th sortable="1" width="220px">BMI</c-table.th>
                        <c-table.th sortable="1" width="200px">Blood Pressure</c-table.th>
                        <c-table.th align="left" sortable="1" width="220px">Blood Sugar</c-table.th>
                        <c-table.th align="left">Health Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key=>$item)
                        <c-table.tr>
                            <c-table.td col="Recorded at">{{ $item['Recorded at'] }}</c-table.td>
                            <c-table.td col="BMI">{{ $item['BMI'] }}</c-table.td>
                            <c-table.td col="Blood Pressure">{{ $item['Blood Pressure'] }}</c-table.td>
                            <c-table.td col="Blood Sugar">{{ $item['Blood Sugar'] }}</c-table.td>
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
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Record</c-dropdown.item>
                                            </c-slot>
                                            <c-slot name="headerSuffix">
                                                    <c-badge type="purple">{{$item['Health Status']}}</c-badge>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                  <img src="{{ asset('assets/icons/profile.svg' )}}"/>
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
                                                    icon="{{ asset('assets/icons/user-add--01.svg') }}"
                                                    title="Recorded At"
                                                    info="{{ $item['Recorded at'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Height"
                                                    info="160cm"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Weight"
                                                    info="77kg"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Blood Pressure"
                                                    info="{{ $item['Blood Pressure'] }} "
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="Blood Sugar"
                                                    info="{{ $item['Blood Sugar'] }} "
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/filter.svg') }}"
                                                    title="Pregnancy Stage"
                                                    info="First Trimester"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Fundal Height"
                                                    info="5 cm"
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
                                            <c-slot name="footer">
                                              <c-button type="submit" form="admin-register-form" variant="primary">Download Report</c-button>
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
                             <c-input type="text" label="Recorded at:" placeholder="{{ $item['Recorded at'] }}" required /><br>
                             <c-input type="text" label="BMI:" placeholder="{{ $item['BMI'] }}" required /><br>
                             <c-input type="text" label="Blood Pressure:" placeholder="{{ $item['Blood Pressure'] }}" required /><br>
                             <c-input type="text" label="Blood Sugar:" placeholder="{{ $item['Blood Sugar'] }}" required /><br>
                             <c-select label="Blood :" multiple="1" Default="{{ $item['Health Status'] }}">
                              <option class="select-item" data-value="child">Good</option>
                              <option class="select-item" data-value="child">Bad</option>
                            </c-select><br>
                            <c-textarea label="Additional Notes:" placeholder="Nutrition Facts." rows="4"></c-textarea>
                        </form>
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