@extends('layout/portal')

@section('title')
    PHM  Vaccination
@endsection

@section('header')
   Vaccination Details
@endsection

@section('content')
    <?php
    $items = [
        ['id' => 'C-123', 'name' => 'Sarah Peter',  'Age' => '4 months', 'Vaccination Status' => 'Completed', 'Vaccination Date' => '2023-01-15'],
        ['id' => 'D-123', 'name' => 'John peter',   'Age' => '7 months', 'Vaccination Status' => 'Over due','Vaccination Date' => '2023-02-20'],
        ['id' => 'B-123', 'name' => 'Daniel Parker',  'Age' => '5 months', 'Vaccination Status' => 'upcoming','Vaccination Date' => '2023-01-30'],
        ['id' => 'C-123', 'name' => 'Sarah Peter',  'Age' => '2 months', 'Vaccination Status' => 'pending','Vaccination Date' => '2023-01-15'],
        ['id' => 'F-123', 'name' => 'Michal John',  'Age' => '3 months', 'Vaccination Status' => 'pending','Vaccination Date' => '2023-01-16'],
        ['id' => 'J-123', 'name' => 'Allyson Felix',  'Age' => '4 months', 'Vaccination Status' => 'Over Due','Vaccination Date' => '2023-01-17'],
        ['id' => 'L-123', 'name' => 'Noah Lyles',  'Age' => '4 months', 'Vaccination Status' => 'pending','Vaccination Date' => '2023-01-18'],
        ['id' => 'T-123', 'name' => 'Yohan Blake',  'Age' => '7 months', 'Vaccination Status' => 'Completed','Vaccination Date' => '2023-01-18'],
        ['id' => 'K-123', 'name' => 'Sarah sense',  'Age' => '2 months', 'Vaccination Status' => 'Completed','Vaccination Date' => '2023-01-19'],
        ['id' => 'A-123', 'name' => 'Devon Ann',  'Age' => '4 months', 'Vaccination Status' => 'Over Due','Vaccination Date' => '2023-01-13'],
        ['id' => 'L-123', 'name' => 'Jonathn Parker',  'Age' => '2 months', 'Vaccination Status' => 'Completed','Vaccination Date' => '2023-01-14'],
    ];
    ?>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1" width="200px">ID</c-table.th>
                        <c-table.th sortable="1" width="220px">Name</c-table.th>
                        <c-table.th sortable="1" width="220px">Age</c-table.th>
                        <c-table.th align="left" sortable="1" width="220px">Vaccination Status</c-table.th>
                        <c-table.th align="left" sortable="1">Vaccination Date</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key => $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="Age">{{ $item['Age'] }}</c-table.td>
                            <c-table.td col="Vaccination Satus">{{ $item['Vaccination Status'] }}</c-table.td>
                            <c-table.td col="Vaccination Date">{{ $item['Vaccination Date'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item><b>Actions</c-dropdown.item>
                                         <c-modal id="View-vaccination-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Vaccination Details</c-dropdown.item>
                                            </c-slot>

                            <c-slot name="headerSuffix">
                                @if (strtolower($item["Vaccination Status"]) === "completed")
                                    <c-badge type="yellow">{{ $item['Vaccination Status']}}</c-badge>
                                @elseif (strtolower($item["Vaccination Status"]) === "pending")
                                    <c-badge type="green">{{ $item['Vaccination Status']}}</c-badge>
                                @elseif (strtolower($item["Vaccination Status"]) === "over due")
                                    <c-badge type="purple">{{ $item['Vaccination Status']}}</c-badge>
                                @elseif (strtolower($item["Vaccination Status"]) === "upcoming")
                                    <c-badge type="red">{{ $item['Vaccination Status']}}</c-badge>
                                @endif
                            </c-slot>

                                            <c-slot name="header">
                                                <div>Vaccination Details</div>
                                            </c-slot>

                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="ID"
                                                    info="{{ $item['id'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Name"
                                                    info="{{ $item['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Age"
                                                    info="{{ $item['Age'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Type of Vaccine"
                                                    info="Rubella Vaccine"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Vaccination Status"
                                                    info="{{ $item['Vaccination Status'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Vaccination Date"
                                                    info="{{ $item['Vaccination Date'] }}"
                                                />
                                            </c-modal.viewcard>
                                            
                                            <h4>Additional Information</h4>
                                             <ul>
                                              <li>Alargies: None</li>
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
                                     <c-dropdown.item>Edit Vaccination Details</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerPrefix">
                                           <img src="{{ asset('assets/icons/configuration-02.svg' )}}"/>
                                     </c-slot>

                                    <c-slot name="header">
                                            <div>Edit Vaccination Details</div>
                                    </c-slot>

                        <form id="admin-register-form" action="">
                             <c-input type="text" label="ID:" placeholder="{{ $item['id'] }}" required /><br>
                             <c-input type="text" label="Name:" placeholder="{{ $item['name'] }}" required /><br>
                             <c-input type="text" label="Age:" placeholder="{{ $item['Age'] }}" required /><br>
                             <c-select label="Vaccination Status:" name="permissions" multiple="1" searchable="1">
                                    <li class="select-item" data-value="child">Upcoming</li>
                                    <li class="select-item" data-value="maternal">Pending</li>
                                    <li class="select-item" data-value="infant">Completed</li>
                                    <li class="select-item" data-value="toddler">Overdue</li>
                             </c-select><br>
                             <c-input type="text" label="Vaccination Date:" placeholder="{{ $item['Vaccination Date'] }}" required /><br>
                        </form>
                        <c-slot name="footer">
                          <c-button type="button" variant="outline" data-modal-close="registerAdmin">Save Changes</c-button>
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