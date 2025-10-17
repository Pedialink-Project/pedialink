@extends('layout/portal')

@section('title')
    Manage Vaccination Schedule
@endsection

@section('header')
    Manage Vaccination Schedule - 2020_v000{{ $schedule_id }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/manage.css') }}">
@endsection

@section('content')
    <?php
    $scheduleList = [
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        ["id" => "0001", "name" => "Tuberculosis", "code" => "BCG", "dose_number" => 5],
        
    ];
    ?>

    <c-table.controls :columns='["ID","Name","Code","Dose Number"]'>
        <c-slot name="extrabtn">
            <c-modal id="add-vaccine-modal" size="sm" :initOpen="false">
                <c-slot name="trigger">
                    <c-button class="add-vaccine-btn" variant="primary">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_967_38088)">
                                <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935" stroke="#FAFAFA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z" stroke="#FAFAFA" stroke-width="1.5"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_967_38088">
                                    <rect width="20" height="20" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                        <span>Add Vaccine</span>
                    </c-button>
                </c-slot>

                <c-slot name="headerPrefix">
                    <svg width="25" height="26" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 2.1665V3.83317M5 2.1665V3.83317" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.5 7.1665H17.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.08337 10.7027C2.08337 7.07161 2.08337 5.25607 3.12681 4.12803C4.17024 3 5.84962 3 9.20837 3H10.7917C14.1505 3 15.8298 3 16.8733 4.12803C17.9167 5.25607 17.9167 7.07161 17.9167 10.7027V11.1306C17.9167 14.7617 17.9167 16.5773 16.8733 17.7053C15.8298 18.8333 14.1505 18.8333 10.7917 18.8333H9.20837C5.84962 18.8333 4.17024 18.8333 3.12681 17.7053C2.08337 16.5773 2.08337 14.7617 2.08337 11.1306V10.7027Z" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.5 13H12.5M10 10.5L10 15.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                </c-slot>
                <c-slot name="header">
                    <div>Add Vaccine to Schedule</div>
                </c-slot>

                <form id="add-vaccine" class="manage-schedule-form" action="">
                    <c-input type="text" label="Schedule Name" placeholder="Enter schedule name" required />
                    <c-input type="number" label="Dose Numbers (in days)" placeholder="Enter dose number" required />
                    <c-input type="number" label="Minimum Age (in days)" placeholder="Enter minimum age for dose" />
                    <c-input type="number" label="Due Age (in days)" placeholder="Enter due age for dose" />
                    <c-input type="number" label="Minimum Age Gap (in days)" placeholder="Enter minimum age for dose" />
                    <c-textarea label="Additional Details" placeholder="Enter additional details"></c-textarea>
                </form>

                <c-slot name="close">
                    Cancel
                </c-slot>

                <c-slot name="footer">
                    <c-button type="submit" form="add-vaccine" variant="primary">Add Vaccine</c-button>
                </c-slot>
            </c-modal>
        </c-slot>
    </c-table.controls>

    <c-table.wrapper card="1">
        <div class="table-wrapper" data-responsive="true">
            <c-table.main sticky="1" size="comfortable">
                <c-table.thead>
                    <c-table.tr>
                        <c-table.th sortable="1">ID</c-table.th>
                        <c-table.th sortable="1">Name</c-table.th>
                        <c-table.th sortable="1">Code</c-table.th>
                        <c-table.th sortable="1">Dose Number</c-table.th>
                        <c-table.th class="table-actions"></c-table.th>
                    </c-table.tr>
                </c-table.thead>

                <c-table.tbody>
                    @foreach ($scheduleList as $key => $scheduleItem)
                        <c-table.tr>
                            <c-table.td col="id">{{ $scheduleItem['id'] }}</c-table.td>
                            <c-table.td col="name">{{ $scheduleItem['name'] }}</c-table.td>
                            <c-table.td col="code">{{ $scheduleItem['code'] }}</c-table.td>
                            <c-table.td col="dose_number">{{ $scheduleItem['dose_number'] }}</c-table.td>
                            <c-table.td class="table-actions" align="center">
                                <c-dropdown.main>
                                    <c-slot name="trigger">
                                        <c-button variant="ghost" class="dropdown-trigger">
                                            <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                                        </c-button>
                                    </c-slot>
                                    <c-slot name="menu">
                                        <c-dropdown.item>Copy Vaccine Code</c-dropdown.item>
                                        <c-dropdown.sep />
                                        <c-modal size="md" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>View Details</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Vaccine Details</div>
                                            </c-slot>
                                            
                                            <c-modal.viewcard>
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                                    title="Vaccine Code"
                                                    info="{{ $scheduleItem['code'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/vaccine.svg') }}"
                                                    title="Vaccine Name"
                                                    info="{{ $scheduleItem['name'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/test-tube-01.svg') }}"
                                                    title="Dose Number"
                                                    info="{{ $scheduleItem['dose_number'] }}"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-minimum.svg') }}"
                                                    title="Minimum Age"
                                                    info="62 Days"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                                    title="Due Age"
                                                    info="270 Days"
                                                />
                                                <c-modal.viewitem
                                                    icon="{{ asset('assets/icons/chart-maximum.svg') }}"
                                                    title="Maximum Gap"
                                                    info="28 Days"
                                                />
                                            </c-modal.viewcard>

                                            <div class="vaccine-schedule-additional-content">
                                                <h4>Additional Details</h4>
                                                <ul>
                                                    <li>Lorem Ipsum</li>
                                                    <li>Lorem Ipsum</li>
                                                </ul>
                                            </div>

                                            <div class="vaccine-schedule-additional-content">
                                                <h4>Notes</h4>
                                                <p>Lorem Ipsum</p>
                                            </div>

                                            <c-slot name="close">
                                                Close
                                            </c-slot>
                                        </c-modal>
                                        <c-modal size="md" :initOpen="false">
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Edit Details</c-dropdown.item>
                                            </c-slot>

                                            <c-slot name="headerPrefix">
                                                <svg width="25" height="26" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M15 2.1665V3.83317M5 2.1665V3.83317" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M2.5 7.1665H17.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M2.08337 10.7027C2.08337 7.07161 2.08337 5.25607 3.12681 4.12803C4.17024 3 5.84962 3 9.20837 3H10.7917C14.1505 3 15.8298 3 16.8733 4.12803C17.9167 5.25607 17.9167 7.07161 17.9167 10.7027V11.1306C17.9167 14.7617 17.9167 16.5773 16.8733 17.7053C15.8298 18.8333 14.1505 18.8333 10.7917 18.8333H9.20837C5.84962 18.8333 4.17024 18.8333 3.12681 17.7053C2.08337 16.5773 2.08337 14.7617 2.08337 11.1306V10.7027Z" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7.5 13H12.5M10 10.5L10 15.5" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>

                                            </c-slot>

                                            <c-slot name="header">
                                                <div>Edit Details</div>
                                            </c-slot>

                                            <form id="edit-vaccine" class="manage-schedule-form" action="">
                                                <c-input type="text" label="Schedule Name" placeholder="Enter schedule name" value="{{ $scheduleItem['name'] }}" required />
                                                <c-input type="number" label="Dose Numbers (in days)" placeholder="Enter dose number" value="{{ $scheduleItem['dose_number'] }}" required />
                                                <c-input type="number" label="Minimum Age (in days)" placeholder="Enter minimum age for dose" value="62" required />
                                                <c-input type="number" label="Due Age (in days)" placeholder="Enter due age for dose" value="270" required />
                                                <c-input type="number" label="Minimum Age Gap (in days)" placeholder="Enter minimum age for dose" value="28" required />
                                                <c-textarea label="Additional Details" placeholder="Enter additional details"></c-textarea>
                                            </form>
                                            
                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" form="edit-vaccine" variant="primary">Save Changes</c-button>
                                            </c-slot>
                                        </c-modal>
                                        <c-modal>
                                            <c-slot name="trigger">
                                                <c-dropdown.item>Remove Vaccine</c-dropdown.item>
                                            </c-slot>
                                            <c-slot name="header">
                                                <div>Remove Vaccine</div>
                                            </c-slot>

                                            <p>
                                                Do you want to remove vaccine from schedule <span class="schedule-vaccine-highlight">2020_v000{{ $schedule_id }}</span>v?
                                            </p>

                                            <c-slot name="close">
                                                Cancel
                                            </c-slot>

                                            <c-slot name="footer">
                                                <c-button type="submit" variant="destructive">
                                                    Remove Vaccine
                                                </c-button>
                                            </c-slot>
                                        </c-modal>                                    
                                    </c-slot>
                                </c-dropdown.main>
                            </c-table.td>
                        </c-table.tr>
                    @endforeach
                    @if(count($scheduleList) === 0)
                        <tr><td colspan="6"><div class="table-empty">No items found</div></td></tr>
                    @endif
                </c-table.tbody>
            </c-table.main>
        </div>
    </c-table.wrapper>

    <c-table.pagination />
@endsection