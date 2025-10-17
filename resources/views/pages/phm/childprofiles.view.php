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
        ['id' => 'C-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed','gs_devision'=>'Borella'],
        ['id' => 'D-123', 'name' => 'John Peter',   'Age' => '7 months', 'Vaccination Status' => 'Over due','gs_devision'=>'Borella'],
        ['id' => 'B-123', 'name' => 'Daniel Parker',  'Age' => '5 months', 'Vaccination Status' => 'Completed','gs_devision'=>'Borella'],
        ['id' => 'C-124', 'name' => 'Emily Stone',  'Age' => '6 months', 'Vaccination Status' => 'Pending','gs_devision'=>'Dehiwala'],
        ['id' => 'F-125', 'name' => 'Michael Lee',  'Age' => '8 months', 'Vaccination Status' => 'Completed','gs_devision'=>'Moratuwa'],
        ['id' => 'J-126', 'name' => 'Olivia Brown',  'Age' => '3 months', 'Vaccination Status' => 'Completed','gs_devision'=>'Ratmalana'],
        ['id' => 'L-127', 'name' => 'Liam Smith',  'Age' => '9 months', 'Vaccination Status' => 'Over due','gs_devision'=>'Wellawatta'],
        ['id' => 'T-128', 'name' => 'Sophia Green',  'Age' => '2 months', 'Vaccination Status' => 'Completed','gs_devision'=>'Other'],
        ['id' => 'K-129', 'name' => 'Noah White',  'Age' => '10 months', 'Vaccination Status' => 'Pending','gs_devision'=>'Borella'],
        ['id' => 'A-130', 'name' => 'Ava Black',  'Age' => '5 months', 'Vaccination Status' => 'Completed','gs_devision'=>'Dehiwala'],
        ['id' => 'L-131', 'name' => 'Mason Gray',  'Age' => '7 months', 'Vaccination Status' => 'Completed','gs_devision'=>'Moratuwa'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Vaccination Status","GN Devision"]'>

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
            <c-modal id="registerStaff" size="sm" :initOpen="false">
                <c-slot name="trigger">
                    <c-button variant="primary">
                        Add Child Profile
                    </c-button>
                </c-slot>
                <c-slot name="headerPrefix">
                    <img src="{{ asset('assets/icons/user-add--01.svg' )}}"/>
                </c-slot>
                <c-slot name="header">
                    <div>Add Child Profile</div>
                </c-slot>

                <form id="admin-register-form" action="">
                    <c-input type="text" label="Child Full Name:" placeholder="Enter Full Name" required /><br>
                    <c-select label="GS Devision" name="options" multiple="1" searchable="1" required>
                        <li class="select-item" data-value="option1">Borella</li>
                        <li class="select-item" data-value="option2">Dehiwala</li>
                        <li class="select-item" data-value="option3">Moratuwa</li>
                        <li class="select-item" data-value="option4">Ratmalana</li>
                        <li class="select-item" data-value="option5">Wellawatta</li>
                        <li class="select-item" data-value="option6">Other</li>
                    </c-select><br>
                    <c-input type="date" label="Date of Birth:" required /><br>
                    <c-textarea label="Address:" placeholder="Enter Address" rows="4" required></c-textarea><br>
                    <c-input type="file" label="Birth Certificate" required/><br>
                    <c-input type="file" label="Additional Documents" /><br>
                    <c-textarea label="Additional Notes:" placeholder="Enter any additional notes here..." rows="4"></c-textarea>
                </form>
                <c-slot name="close">
                        Close
                </c-slot>
                <c-slot name="footer">
                    <c-button type="submit" form="admin-register-form" variant="primary">Create a Child Profile</c-button>
                </c-slot>
            </c-modal>
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
                        <c-table.th align="left">Vaccination Status</c-table.th>
                        <c-table.th align="left">GN Devision</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key => $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="Age">{{ $item['Age'] }}</c-table.td>
                            <c-table.td col="Vaccination Status">{{ $item['Vaccination Status'] }}</c-table.td>
                            <c-table.td col="GN Devision" align="center">{{ $item['gs_devision'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                        <c-slot name="menu">
                                         <c-dropdown.item>Copy Child ID</c-dropdown.item>
                                         <c-modal id="View-Child-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="headerPrefix">
                                                  <img src="{{ asset('assets/icons/profile.svg' )}}"/>
                                            </c-slot>
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Child Profile</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerSuffix">
                                                    <c-badge type="success">Good</c-badge>
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Child Profile Details</div>
                                            </c-slot>

                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Record ID"
                                                    info="12000"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Name"
                                                    info="{{ $item['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Total Vaccinations"
                                                    info="2"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Age"
                                                    info="{{ $item['Age'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Vaccination Status"
                                                    info="{{ $item['Vaccination Status'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="Head Circumference"
                                                    info="32 cm"
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
                                     <c-dropdown.item>Edit Child Profile</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="headerPrefix">
                                           <img src="{{ asset('assets/icons/configuration-02.svg' )}}"/>
                                     </c-slot>
                                    <c-slot name="header">
                                            <div>Edit Child Profile</div>
                                    </c-slot>

                                          <form id="admin-register-form" action="">
                                              <c-input type="text" label="Child Full Name:" placeholder="{{ $item['name'] }}" required /><br>
                                              <c-input type="text" label="GN Devision:" placeholder="{{ $item['gs_devision'] }}" required /><br>
                                              <c-input type="date" label="Date of Birth:" value="" required /><br>
                                              <c-textarea label="Address:" placeholder="132,1/2,Lorem street" rows="1"></c-textarea>
                                              <c-select label="Health Status:" multiple="1" Default="{{ $item['Health Status'] }}">
                                              <option class="select-item" data-value="child">Good</option>
                                              <option class="select-item" data-value="child">Good</option>
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
                                         <c-dropdown.item href="{{ route('phm.growth.monitoring',['id'=>$key,])}}">View Growth Records</c-dropdown.item>
                                        <c-dropdown.item href="{{ route('phm.child.health.records',['id'=>$key,])}}">View Health Records</c-dropdown.item>
                                        <c-dropdown.item href="{{ route('phm.vaccination',['id'=>$key,])}}">View Vaccination Records</c-dropdown.item>
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