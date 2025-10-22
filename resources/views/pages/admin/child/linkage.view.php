@extends('layout/portal')

@section('title')
    Child Profile - Linkage Requests
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/child-linkage.css') }}">
@endsection

@section('header')
    Child Profile - Linkage Requests
@endsection

@section('content')
    <?php

    $linkRequests = [
        ["parent_id" => 1234, "role" => "parent", "type" => "father", "parent_name" => "Peter Johns", "child_id" => 1234, "child_name" => "Nancy Jekkins"],
        ["parent_id" => 1234, "role" => "parent", "type" => "mother", "parent_name" => "Nethmi Johns", "child_id" => 1234, "child_name" => "Nancy Jekkins"],
        ["parent_id" => 1234, "role" => "parent", "type" => "father", "parent_name" => "Peter Johns", "child_id" => 1234, "child_name" => "Nancy Jekkins"],
        ["parent_id" => 1234, "role" => "parent", "type" => "father", "parent_name" => "Peter Johns", "child_id" => 1234, "child_name" => "Nancy Jekkins"],
        ["parent_id" => 1234, "role" => "parent", "type" => "mother", "parent_name" => "Sarah Johns", "child_id" => 1234, "child_name" => "Nancy Jekkins"],
        ["parent_id" => 1234, "role" => "parent", "type" => "mother", "parent_name" => "Alisa Johns", "child_id" => 1234, "child_name" => "Nancy Jekkins"],
        

    ];
    ?>
    <div class="linkage-content">

        @foreach ($linkRequests as $key => $request)
            <c-card class="linkage-card">
                <c-slot name="header">
                    <h3>{{ $request["parent_name"] }} &#8594; Child &middot; C-{{ $request["child_id"] }}</h3>
                </c-slot>
                <c-slot name="headerSuffix">
                    <span class="linkage-time">30 minutes ago</span>
                    <c-dropdown.main class="view-linkage-sm-btn">
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
                                        title="Parent ID"
                                        info="P-{{ $request['parent_id'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/user.svg') }}"
                                        title="Parent Full Name"
                                        info="{{ $request['parent_name'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                        title="Child ID"
                                        info="C-{{ $request['child_id'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/user.svg') }}"
                                        title="Child Full Name"
                                        info="{{ $request['child_name'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                        title="Created On"
                                        info="Monday, January 15, 2023"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/location-05.svg') }}"
                                        title="GS Division"
                                        info="Borella"
                                    />
                                </c-modal.viewcard>

                                <c-modal.viewlist title="Parent Details">
                                    <c-slot name="list">
                                        <li>NIC: 230001045</li>
                                        <li>Type: {{ ucfirst($request["type"] ) }}</li>
                                        <li>Address: 124/23, St Peters Lane, Colombo</li>
                                    </c-slot>
                                </c-modal.viewlist>

                                <c-modal.viewlist title="Child Details">
                                    <c-slot name="list">
                                        <li>Link Status: 1 account linked</li>
                                        <li>Registered GS Division: Borella</li>
                                    </c-slot>
                                </c-modal.viewlist>

                                <c-slot name="close">
                                    Close
                                </c-slot>
                            </c-modal>
                        </c-slot>
                    </c-dropdown.main>
                </c-slot>
                <p class="linkage-card-content">P-{{ $request["parent_id"] }} requested to link with child account</p>
                <c-slot name="footer">
                    <div class="linkage-card-btn-grp">
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
                                Approve Request
                            </c-slot>

                            <p>
                                Approve request of <span class="approve-text">"{{ $request["parent_name"] }}"</span> with 
                                id <span class="approve-text">P-{{ $request["parent_id"] }}</span> to link with child account
                                <span class="approve-text">"{{ $request["child_name"] }}"</span> of id <span class="approve-text">C-{{ $request["child_id"] }}</span>?
                            </p>

                            <form id="approve-account-{{ $key }}" action="" class="hidden"></form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button type="submit" variant="primary" form="approve-account-{{ $key }}">
                                    Approve Request
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
                                Deny Request
                            </c-slot>

                            <p>
                                Deny request of <span class="deny-text">"{{ $request["parent_name"] }}"</span> with 
                                id <span class="deny-text">P-{{ $request["parent_id"] }}</span> to link with child account
                                <span class="deny-text">"{{ $request["child_name"] }}"</span> of id <span class="deny-text">C-{{ $request["child_id"] }}</span>?
                            </p>
                            
                            <form id="deny-account-{{ $key }}" action="" class="hidden"></form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button type="submit" variant="destructive" form="deny-account-{{ $key }}">
                                    Deny Request
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
                                    title="Parent ID"
                                    info="P-{{ $request['parent_id'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Parent Full Name"
                                    info="{{ $request['parent_name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="Child ID"
                                    info="C-{{ $request['child_id'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Child Full Name"
                                    info="{{ $request['child_name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                    title="Created On"
                                    info="Monday, January 15, 2023"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                    title="GS Division"
                                    info="Borella"
                                />
                            </c-modal.viewcard>

                            <c-modal.viewlist title="Parent Details">
                                <c-slot name="list">
                                    <li>NIC: 230001045</li>
                                    <li>Type: {{ ucfirst($request["type"] ) }}</li>
                                    <li>Address: 124/23, St Peters Lane, Colombo</li>
                                </c-slot>
                            </c-modal.viewlist>

                            <c-modal.viewlist title="Child Details">
                                <c-slot name="list">
                                    <li>Link Status: 1 account linked</li>
                                    <li>Registered GS Division: Borella</li>
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