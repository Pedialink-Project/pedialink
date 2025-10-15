@extends('layout/portal')

@section('title')
    PHM  Appointments
@endsection

@section('header')
   Appointments Details
@endsection

@section('content')
    <?php
    $items = [
        ['id' => 'P-1345', 'name' => 'Nancy Drew', 'Age' => '28', 'Category' => 'Mother', 'Date & Time' =>'2023-01-19', 'Status' => 'Upcoming'],
        ['id' => 'D-1345', 'name' => 'John peter', 'Age' => '32', 'Category' => 'Mother', 'Date & Time' =>'2023-02-17', 'Status' => 'Pending'],
        ['id' => 'S-1345', 'name' => 'Femke Bol', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-16', 'Status' => 'Completed'],
        ['id' => 'T-1345', 'name' => 'Daniel Parker', 'Age' => '1', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Cancelled'],
        ['id' => 'R-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
        ['id' => 'I-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
        ['id' => 'N-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
        ['id' => 'P-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
        ['id' => 'W-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
        ['id' => 'A-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
        ['id' => 'A-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
        ['id' => 'D-1345', 'name' => 'Nancy Drew', 'Age' => '2', 'Category' => 'Baby', 'Date & Time' =>'2023-01-17', 'Status' => 'Upcoming'],
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Age","Category","Date & Time","Status"]'>

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
                Add Appointment
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
                        <c-table.th sortable="1" width="200px">Age(yrs)</c-table.th>
                        <c-table.th sortable="1" width="220px">Category</c-table.th>
                        <c-table.th align="left" sortable="1" width="200px">Date & Time</c-table.th>
                        <c-table.th align="left" sortable="1" width="220px">Status</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($items as $key => $item)
                        <c-table.tr>
                            <c-table.td col="id">{{ $item['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $item['name'] }}</c-table.td>
                            <c-table.td col="Age">{{ $item['Age'] }}</c-table.td>
                            <c-table.td col="Category">{{ $item['Category'] }}</c-table.td>
                            <c-table.td col="Date & Time">{{ $item['Date & Time'] }}</c-table.td>
                            <c-table.td col="Status">{{ $item['Status'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                         <c-dropdown.item><b>Actions</c-dropdown.item>
                                         <c-modal id="View-appointment-{{ $key }}" size="sm" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Appointment</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerSuffix">
                                                    <c-badge type="yellow">{{ $item['Status']}}</c-badge>
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Appointment Details</div>
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
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Age"
                                                    info="2 Years"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/user.svg') }}"
                                                    title="Category"
                                                    info="{{ $item['Category'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Date & Time"
                                                    info="{{ $item['Date & Time'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-evaluation.svg') }}"
                                                    title="Age"
                                                    info="{{ $item['Age'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Vaccination Status"
                                                    info="{{ $item['Status'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                                    title="Type of Vaccine"
                                                    info="B.C.G."
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
                                        <c-dropdown.item>Cancel Appointment</c-dropdown.item>
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