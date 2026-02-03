@extends('layout/portal')

@section('title')
Child Health Records
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/doctor/child-health.css') }}">
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
Health Records &#8594; {{$name}} &middot; C-000{{ $id }}
@endsection

@section('content')


<c-table.controls :columns='["Recorded at","Height","Weight","Head Circumference","Health Status"]'>

    <c-slot name="extrabtn">
        <c-modal id="add-health-record-modal" size="sm" :initOpen="flash('add') ? true : false">
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

            <form id="add-health-record-form" class="child-health-form" action="{{route('doctor.child.health.add', ['id' => $id])}}" method="POST">
                <c-input type="text" name="height" label="Height" value="{{ old('height') ?? '' }}"
                    error="{{ errors('height') ?? '' }}" placeholder="Enter Height of the Child (in cm)"  />
                <c-input type="text" name="weight" label="Weight" value="{{ old('weight') ?? '' }}"
                    error="{{ errors('weight') ?? '' }}" placeholder="Enter Weight of the Child (in kg)"  />
                <c-input type="text" name="head_circumference" label="Head Circumference" value="{{ old('head_circumference') ?? '' }}"
                    error="{{ errors('head_circumference') ?? '' }}" placeholder="Enter Head Circumference of the Child (in cm)"  />
                <c-input type="date" name="visit_date" label="Visit Date" value="{{ old('visit_date') ?? '' }}"
                    error="{{ errors('visit_date') ?? '' }}" placeholder="Select the Visit Date"  />
                
                <c-textarea name="notes" label="Additional Notes" value="{{ old('notes') ?? '' }}"
                    error="{{ errors('notes') ?? '' }}" placeholder="Enter any additional notes here" rows="4"></c-textarea>
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
                    <c-table.th sortable="1">Recorded at</c-table.th>
                    <c-table.th sortable="1">Height</c-table.th>
                    <c-table.th sortable="1">Weight</c-table.th>
                    <c-table.th align="left" sortable="1">Head Circumference</c-table.th>
                    <c-table.th align="left">Health Status</c-table.th>
                    <c-table.th class="table-actions"></c-table.th>
                </c-table.tr>
            </c-table.thead>

            <c-table.tbody>
                @foreach ($records as $key=>$record)
                <c-table.tr>
                    <c-table.td col="Recorded at">{{ $record['visit_date'] }}</c-table.td>
                    <c-table.td col="Height">{{ $record['height'] }}</c-table.td>
                    <c-table.td col="Weight">{{ $record['weight'] }}</c-table.td>
                    <c-table.td col="Head Circumference">{{ $record['head_circumference'] }}</c-table.td>
                    <c-table.td col="Health Status">
                        @if (strtolower($record['health_status']) === "good")
                        <c-badge type="green">
                            {{ ucwords(str_replace('_', ' ', $record['health_status'])) }}
                        </c-badge>
                        @elseif (strtolower($record['health_status']) === "at_risk")
                        <c-badge type="yellow">
                            {{ ucwords(str_replace('_', ' ', $record['health_status'])) }}
                        </c-badge>
                        @elseif (strtolower($record['health_status']) === "critical")
                        <c-badge type="red">
                            {{ ucwords(str_replace('_', ' ', $record['health_status'])) }}
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
                                        <img src="{{ asset('assets/icons/profile.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="trigger">
                                        <c-dropdown.item>View Record</c-dropdown.item>
                                    </c-slot>

                                    <c-slot name="headerSuffix">
                                        @if (strtolower($record['health_status']) === "good")
                                        <c-badge type="green">
                                            {{ ucwords(str_replace('_', ' ', $record['health_status'])) }}
                                        </c-badge>
                                        @elseif (strtolower($record['health_status']) === "at_risk")
                                        <c-badge type="yellow">
                                            {{ ucwords(str_replace('_', ' ', $record['health_status'])) }}
                                        </c-badge>
                                        @elseif (strtolower($record['health_status']) === "critical")
                                        <c-badge type="red">
                                            {{ ucwords(str_replace('_', ' ', $record['health_status'])) }}
                                        </c-badge>
                                        @endif </c-slot>

                                    <c-slot name="header">
                                        <div>Health Record</div>
                                    </c-slot>

                                    <c-modal.viewcard>
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/profile.svg') }}"
                                            title="Record ID"
                                            info="REC001" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/ruler.svg') }}"
                                            title="Height"
                                            info="{{ $record['height'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/vaccine.svg') }}"
                                            title="Total Vaccinations"
                                            info="2" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/body-weight.svg') }}"
                                            title="Weight"
                                            info="{{ $record['weight'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                            title="Visit Date"
                                            info="{{ $record['visit_date'] }}" />
                                        <c-modal.viewitem
                                            icon="{{ asset('assets/icons/ruler.svg') }}"
                                            title="Head Circumference"
                                            info="{{ $record['head_circumference'] }} " />
                                    </c-modal.viewcard>

                                    <c-modal.viewlist title="Additional Information">
                                        <c-slot name="list">
                                            <li>Nutrition Facts: Good</li>
                                            <li>Lorem Ipsum</li>
                                        </c-slot>
                                    </c-modal.viewlist>

                                    <c-slot name="close">
                                        Close
                                    </c-slot>
                                </c-modal>
                                <c-modal id="edit-health-record-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Edit Health Records</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/profile.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Edit Health Records</div>
                                    </c-slot>

                                    <form id="edit-health-record-form" class="child-health-form" action="">
                                        <c-input type="text" label="Height:" placeholder="Enter height" required />
                                        <c-input type="text" label="Weight:" placeholder="Enter weight" required />
                                        <c-input type="text" label="Head Circumference:" placeholder="Enter head circumference" required />
                                        <c-select label="Health Status:" value="{{ strtolower($item['Health Status']) }}">
                                            <option class="select-item" data-value="good">Good</option>
                                            <option class="select-item" data-value="bad">Bad</option>
                                        </c-select>
                                        <c-textarea label="Additional Notes:" placeholder="Nutrition Facts." rows="4"></c-textarea>
                                    </form>

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>
                                    <c-slot name="footer">
                                        <c-button type="submit" variant="primary">Save Changes</c-button>
                                    </c-slot>
                                </c-modal>
                                <c-modal id="mark-as-invalid-record-{{ $key }}" size="sm" :initOpen="false">
                                    <c-slot name="trigger">
                                        <c-dropdown.item>Mark as Invalid</c-dropdown.item>
                                    </c-slot>
                                    <c-slot name="headerPrefix">
                                        <img src="{{ asset('assets/icons/profile.svg' )}}" />
                                    </c-slot>

                                    <c-slot name="header">
                                        <div>Mark as Invalid</div>
                                    </c-slot>

                                    <p>Are you sure you want to mark this record as invalid?</p>

                                    <c-slot name="close">
                                        Cancel
                                    </c-slot>

                                    <c-slot name="footer">
                                        <c-button size="sm" variant="destructive">Mark</c-button>
                                    </c-slot>
                                </c-modal>
                            </c-slot>
                        </c-dropdown.main>
                    </c-table.td>
                </c-table.tr>
                @endforeach

                @if(count($records) === 0)
                <tr>
                    <td colspan="6">
                        <div class="table-empty">No items found</div>
                    </td>
                </tr>
                @endif
            </c-table.tbody>
        </c-table.main>
    </div>
</c-table.wrapper>

<c-table.pagination />
@endsection