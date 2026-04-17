@extends('layout/portal')

@section('title')
    Maternal Profile - Access Requests
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/access.css') }}">
@endsection

@section('header')
    Maternal Profile - Access Requests
@endsection

@section('content')
    @if (count($accessRequests) <= 0)
        <c-emptytable
            alt="No access requests"
            title="No maternal access requests"
            description="Currently no maternal access requests are pending!"
        />
    @endif
    <div class="access-content">
        @foreach ($accessRequests as $key => $request)
            <c-card class="access-card">
                <c-slot name="header">
                    <h3>{{ $request["staff"]["name"] }} &#8594; Mother &middot; {{ display_entity_id('maternal', $request["maternal"]["id"]) }}</h3>
                </c-slot>
                <c-slot name="headerSuffix">
                    <span class="access-time">{{ time_ago($request["created_at"] )}}</span>
                    <c-dropdown.main class="view-access-sm-btn">
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
                                    <div>Access Request Info</div>
                                </c-slot>

                                <c-modal.viewcard>
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                        title="Staff ID"
                                        info="D-{{ $request['staff']['id'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/user.svg') }}"
                                        title="Staff Full Name"
                                        info="{{ $request['staff']['name'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/profile-02.svg') }}"
                                        title="Mother ID"
                                        info="{{ display_entity_id('maternal', $request['maternal']['id']) }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/user.svg') }}"
                                        title="Mother Full Name"
                                        info="{{ $request['maternal']['name'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                        title="Requested On"
                                        info="{{ $request['created_at'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                        title="Staff Role"
                                        info="{{ ucfirst($request['staff']['role']) }}"
                                    />
                                </c-modal.viewcard>

                                <div class="access-additional-content">
                                    <h4>Staff Details</h4>
                                    <ul>
                                        <li>NIC: {{ $request['staff']['nic'] }}</li>
                                        <li>Type: {{ ucfirst($request["role"] ) }}</li>
                                    </ul>
                                </div>

                                <div class="access-additional-content">
                                    <h4>Request Details</h4>
                                    <ul>
                                        <li>Requested Info: {{ $request['reason_title']}}</li>
                                        <li>Reason: {{ $request['reason_description'] }}</li>
                                    </ul>
                                </div>

                                <c-slot name="close">
                                    Close
                                </c-slot>
                            </c-modal>
                        </c-slot>
                    </c-dropdown.main>
                </c-slot>
                <p class="access-card-content">
                    D-{{ $request["staff"]['id'] }} Requested access: {{ $request['reason_title'] }}
                </p>
                <p class="access-card-content">
                    Reason: {{ $request['reason_description'] }}
                </p>
                <c-slot name="footer">
                    <div class="access-card-btn-grp">
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
                                Approve request of <span class="approve-text">"{{ $request["staff"]["name"] }}"</span> with 
                                id <span class="approve-text">D-{{ $request["staff"]["id"] }}</span> to access maternal account
                                <span class="approve-text">"{{ $request["maternal"]["name"] }}"</span> of id <span class="approve-text">{{ display_entity_id('maternal', $request["maternal"]["id"]) }}</span>?
                            </p>

                            <form id="approve-account-{{ $key }}" method="POST" action="{{ route('admin.maternal.access.requests.approve', ['id' => $request['id']]) }}" class="hidden"></form>

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
                                Deny request of <span class="deny-text">"{{ $request["staff"]["name"] }}"</span> with 
                                id <span class="deny-text">D-{{ $request["staff"]["id"] }}</span> to access maternal account
                                <span class="deny-text">"{{ $request["maternal"]["name"] }}"</span> of id <span class="deny-text">{{ display_entity_id('maternal', $request["maternal"]["id"]) }}</span>?
                            </p>
                            
                            <form id="deny-account-{{ $key }}" method="POST" action="{{ route('admin.maternal.access.requests.deny', ['id' => $request['id']]) }}" class="hidden"></form>

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
                                <div>Access Request Info</div>
                            </c-slot>

                            <c-modal.viewcard>
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="Staff ID"
                                    info="D-{{ $request['staff']['id'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Staff Full Name"
                                    info="{{ $request['staff']['name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/profile-02.svg') }}"
                                    title="Mother ID"
                                    info="{{ display_entity_id('maternal', $request['maternal']['id']) }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Mother Full Name"
                                    info="{{ $request['maternal']['name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                    title="Requested On"
                                    info="{{ $request['created_at'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                    title="Staff Role"
                                    info="{{ ucfirst($request['staff']['role']) }}"
                                />
                            </c-modal.viewcard>

                            <c-modal.viewlist title="Staff Details">
                                <c-slot name="list">
                                    <li>NIC: {{ $request['staff']['nic'] }}</li>
                                    <li>Type: {{ ucfirst($request['staff']['role']) }}</li>
                                </c-slot>
                            </c-modal.viewlist>

                            <c-modal.viewlist title="Request Details">
                                <c-slot name="list">
                                    <li>Requested Info: {{ $request['reason_title'] }}</li>
                                    <li>Reason: {{ $request['reason_description'] }}</li>
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

    <c-table.pagination :links="$links" />
@endsection