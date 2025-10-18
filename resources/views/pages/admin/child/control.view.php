@extends('layout/portal')

@section('title')
    Edit Access Control
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/child-control.css') }}">
@endsection

@section('header')
    CH-{{ $id }} &#8594; Edit Access Control
@endsection

@section('content')
    <?php

    $primaryAccess = [
        ["id" => 1234, "name" => "John William", "type" => "father"],
        ["id" => 1234, "name" => "Mauri William", "type" => "mother"],

    ];

    $secondaryAccess = [
        ["id" => 1234, "name" => "Dr Nirmala Perara", "role" => "doctor"],
        ["id" => 1234, "name" => "Dr Nirmala Perara", "role" => "doctor"],
        ["id" => 1234, "name" => "Dr Nirmala Perara", "role" => "doctor"],
        ["id" => 1234, "name" => "Dr Nirmala Perara", "role" => "doctor"],
        ["id" => 1234, "name" => "Dr Nirmala Perara", "role" => "doctor"],
        ["id" => 1234, "name" => "Dr Nirmala Perara", "role" => "doctor"],

    ];

    ?>
    <div class="control-content">
        @foreach ($primaryAccess as $key => $primaryAccount)
            <c-card class="control-card">
                <c-slot name="header">
                    <h3>{{ $primaryAccount["name"] }}</h3>
                </c-slot>
                <p class="control-card-content">
                    P-{{ $primaryAccount["id"] }} has primary access to this profile
                </p>
                <c-slot name="footer">
                    <div class="control-card-btn-grp">
                        <c-modal hideClass="lg-modal">
                            <c-slot name="trigger">
                                <c-button variant="secondary">
                                    <img src="{{ asset('assets/icons/document-validation.svg')}}">
                                    View <span class="lg-text">Details</span>
                                </c-button>
                            </c-slot>

                            <c-slot name="headerPrefix">
                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                            </c-slot>

                            <c-slot name="header">
                                <div>View Linkage Info</div>
                            </c-slot>

                            <c-modal.viewcard>
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="ID"
                                    info="P-{{ $primaryAccount['id'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Full Name"
                                    info="{{ $primaryAccount['name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="Child ID"
                                    info="C-{{ $id }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Child Full Name"
                                    info="{{ $name }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                    title="Linked On"
                                    info="Monday, January 15, 2023"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                    title="Type"
                                    info="{{ ucfirst($primaryAccount['type']) }}"
                                />
                            </c-modal.viewcard>

                            <c-slot name="close">
                                Close
                            </c-slot>
                        </c-modal>
                        <c-modal size="sm">
                            <c-slot name="trigger">
                                <c-button variant="destructive">
                                    <img class="deny-icon" src="{{ asset('assets/icons/cancel-circle.svg')}}">
                                    Remove <span class="lg-text">Linkage</span>
                                </c-button>
                            </c-slot>

                            <c-slot name="headerPrefix">
                                <img src="{{ asset('assets/icons/cancel-circle-dark.svg') }}" />
                            </c-slot>
                            
                            <c-slot name="header">
                                Remove Linkage
                            </c-slot>

                            <p>
                                Remove linkage of <span class="name-deny">"{{ $primaryAccount["name"] }}"</span> of 
                                id <span class="id-deny">D-{{ $primaryAccount["id"] }}</span> with child account
                                <span class="child-name-deny">"{{ $name }}"</span> of id <span class="child-id-deny">C-{{ $id }}</span>?
                            </p>
                            
                            <form id="deny-account-{{ $key }}" action="" class="hidden"></form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button type="submit" variant="destructive" form="deny-account-{{ $key }}">
                                    Remove Linkage
                                </c-button>
                            </c-slot>
                        </c-modal>
                    </div>
                </c-slot>
            </c-card>
        @endforeach

        @foreach ($secondaryAccess as $key => $secondaryAccount)
            <c-card class="control-card">
                <c-slot name="header">
                    <h3>{{ $secondaryAccount["name"] }}</h3>
                </c-slot>
                <p class="control-card-content">
                    D-{{ $secondaryAccount["id"] }} has been granted access to this profile
                </p>
                <c-slot name="footer">
                    <div class="control-card-btn-grp">
                        <c-modal hideClass="lg-modal">
                            <c-slot name="trigger">
                                <c-button variant="secondary">
                                    <img src="{{ asset('assets/icons/document-validation.svg')}}">
                                    View <span class="lg-text">Details</span>
                                </c-button>
                            </c-slot>

                            <c-slot name="headerPrefix">
                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                            </c-slot>

                            <c-slot name="header">
                                <div>View Linkage Info</div>
                            </c-slot>

                            <c-modal.viewcard>
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="ID"
                                    info="P-{{ $secondaryAccount['id'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Full Name"
                                    info="{{ $secondaryAccount['name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="Child ID"
                                    info="C-{{ $id }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Child Full Name"
                                    info="{{ $name }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                    title="Given Access On"
                                    info="Monday, January 15, 2023"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                    title="Type"
                                    info="{{ ucfirst($secondaryAccount['role']) }}"
                                />
                            </c-modal.viewcard>

                            <c-slot name="close">
                                Close
                            </c-slot>
                        </c-modal>
                        <c-modal size="sm">
                            <c-slot name="trigger">
                                <c-button variant="destructive">
                                    <img class="deny-icon" src="{{ asset('assets/icons/cancel-circle.svg')}}">
                                    Remove <span class="lg-text">Access</span>
                                </c-button>
                            </c-slot>

                            <c-slot name="headerPrefix">
                                <img src="{{ asset('assets/icons/cancel-circle-dark.svg') }}" />
                            </c-slot>
                            
                            <c-slot name="header">
                                Remove Access
                            </c-slot>

                            <p>
                                Remove access of <span class="name-deny">"{{ $primaryAccount["name"] }}"</span> of 
                                id <span class="id-deny">D-{{ $primaryAccount["id"] }}</span> with child account
                                <span class="child-name-deny">"{{ $name }}"</span> of id <span class="child-id-deny">C-{{ $id }}</span>?
                            </p>
                            
                            <form id="deny-account-{{ $key }}" action="" class="hidden"></form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button type="submit" variant="destructive" form="deny-account-{{ $key }}">
                                    Remove Access
                                </c-button>
                            </c-slot>
                        </c-modal>
                    </div>
                </c-slot>
            </c-card>
        @endforeach
    </div>

    <c-table.pagination />
@endsection