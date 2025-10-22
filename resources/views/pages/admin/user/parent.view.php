@extends('layout/portal')

@section('title')
    Parent Account Approval
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/user-parent.css') }}">
@endsection

@section('header')
    Parent Account Approval
@endsection

@section('content')
    <?php

    $parents = [
        ["id" => 1234, "name" => "Peter Johns"],
        ["id" => 1234, "name" => "Peter Johns"],
        ["id" => 1234, "name" => "Peter Johns"],
        ["id" => 1234, "name" => "Peter Johns"],
        ["id" => 1234, "name" => "Peter Johns"],
        ["id" => 1234, "name" => "Peter Johns"],

    ];
    ?>
    <div class="parent-approval-content">

        @foreach ($parents as $key => $parent)
            <c-card class="approval-card">
                <c-slot name="header">
                    <h3>{{ $parent["name"] }}</h3>
                </c-slot>
                <c-slot name="headerSuffix">
                    <span class="approval-time">30 minutes ago</span>
                    <c-dropdown.main class="view-approval-sm-btn">
                        <c-slot name="trigger">
                            <c-button variant="ghost" class="dropdown-trigger">
                                <img src="{{ asset('assets/icons/horizontal-more.svg')}}" />
                            </c-button>
                        </c-slot>
                        <c-slot name="menu">
                            <c-modal>
                                <c-slot name="trigger">
                                    <c-dropdown.item>
                                        <c-slot name="icon">
                                            <img src="{{ asset('assets/icons/document-validation.svg')}}">
                                        </c-slot>
                                        View Details
                                    </c-dropdown.item>
                                </c-slot>

                                <c-slot name="headerPrefix">
                                    <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                                </c-slot>

                                <c-slot name="header">
                                    <div>Account Approval Info</div>
                                </c-slot>

                                <c-modal.viewcard>
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                        title="Profile"
                                        info="P-1234"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/user.svg') }}"
                                        title="Full Name"
                                        info="{{ $parent['name'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                        title="Created On"
                                        info="Monday, January 15, 2023"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                        title="NIC"
                                        info="200301243423"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/mother.svg') }}"
                                        title="Account Type"
                                        info="Mother"
                                    />
                                    
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/location-05.svg') }}"
                                        title="Location"
                                        info="Borella"
                                    />
                                </c-modal.viewcard>

                                <div class="approval-additional-content">
                                    <h4>Additional Information</h4>
                                    <ul>
                                        <li>Address: Lorem Ipsum</li>
                                    </ul>
                                </div>

                                <c-slot name="close">
                                    Close
                                </c-slot>
                            </c-modal>
                        </c-slot>
                    </c-dropdown.main>
                </c-slot>
                <p class="approval-card-content">Parent Account P-{{ $parent["id"] }} is pending approval</p>
                <c-slot name="footer">
                    <div class="approval-card-btn-grp">
                        <c-modal size="sm">
                            <c-slot name="trigger">
                                <c-button variant="primary">
                                    <img src="{{ asset('assets/icons/checkmark-circle-02.svg')}}">
                                    Approve
                                </c-button>
                            </c-slot>

                            <c-slot name="headerPrefix">
                                <img src="{{ asset('assets/icons/checkmark-circle-02-dark.svg') }}" />
                            </c-slot>

                            <c-slot name="header">
                                Approve Account
                            </c-slot>

                            <p>Approve parent <span class="parent-name-approve">"{{ $parent["name"] }}"</span> of id <span class="parent-id-approve">P-{{ $parent["id"] }}</span>?</p>

                            <form id="approve-account-{{ $key }}" action="" class="hidden"></form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button type="submit" variant="primary" form="approve-account-{{ $key }}">
                                    Approve Account
                                </c-button>
                            </c-slot>
                        </c-modal>
                        <c-modal size="sm">
                            <c-slot name="trigger">
                                <c-button variant="destructive">
                                    <img class="deny-icon" src="{{ asset('assets/icons/cancel-circle.svg')}}">
                                    Deny
                                </c-button>
                            </c-slot>

                            <c-slot name="headerPrefix">
                                <img src="{{ asset('assets/icons/cancel-circle-dark.svg') }}" />
                            </c-slot>
                            
                            <c-slot name="header">
                                Deny Account
                            </c-slot>

                            <p>Deny parent <span class="parent-name-deny">"{{ $parent["name"] }}"</span> of id <span class="parent-id-deny">P-{{ $parent["id"] }}</span>?</p>

                            <form id="deny-account-{{ $key }}" action="" class="hidden"></form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button type="submit" variant="destructive" form="deny-account-{{ $key }}">
                                    Deny Account
                                </c-button>
                            </c-slot>
                        </c-modal>
                        <c-modal hideClass="lg-modal">
                            <c-slot name="trigger">
                                <c-button variant="secondary" class="view-approval-lg-btn">
                                    <img src="{{ asset('assets/icons/document-validation.svg')}}">
                                    View Details
                                </c-button>
                            </c-slot>

                            <c-slot name="headerPrefix">
                                <img src="{{ asset('assets/icons/profile-02.svg' )}}" />
                            </c-slot>

                            <c-slot name="header">
                                <div>Account Approval Info</div>
                            </c-slot>

                            <c-modal.viewcard>
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="Profile"
                                    info="P-1234"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Full Name"
                                    info="{{ $parent['name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                    title="Created On"
                                    info="Monday, January 15, 2023"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                    title="NIC"
                                    info="200301243423"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                    title="Account Type"
                                    info="Mother"
                                />
                                
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                    title="Location"
                                    info="Borella"
                                />
                            </c-modal.viewcard>

                            <c-modal.viewlist title="Additional Information">
                                <c-slot name="list">
                                    <li>Address: Lorem Ipsum</li>
                                </c-slot>
                            </c-modal.viewlist>

                            <c-slot name="close">
                                Close
                            </c-slot>
                        </c-modal>
                    </div>
                </c-slot>
            </c-card>
        @endforeach
    </div>

    <c-table.pagination />
@endsection