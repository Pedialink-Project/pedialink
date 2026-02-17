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
     @if (count($primaryAccess) <= 0 && count($secondaryAccess) <= 0 && count($staffAccess) <= 0)
        <c-emptytable
            alt="No access requests"
            title="No access control entries found"
            description="No entries for access control found"
        />
        
    @endif
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
                    PHM-{{ $secondaryAccount["id"] }} has secondary access to this profile
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
                    </div>
                </c-slot>
            </c-card>
        @endforeach
        @foreach ($staffAccess as $key => $staffAccount)
            <c-card class="control-card">
                <c-slot name="header">
                    <h3>{{ $staffAccount["name"] }}</h3>
                </c-slot>
                <p class="control-card-content">
                    {{ ucfirst($staffAccount["role"]) }}-{{ $staffAccount["id"] }} has been granted access to this profile
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
                                    info="P-{{ $staffAccount['id'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Full Name"
                                    info="{{ $staffAccount['name'] }}"
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
                                    info="{{ ucfirst($staffAccount['role']) }}"
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
                                Remove access of <span class="name-deny">"{{ $staffAccount["name"] }}"</span> of 
                                id <span class="id-deny">{{ ucfirst($staffAccount["role"]) }}-{{ $staffAccount["id"] }}</span> with child account
                                <span class="child-name-deny">"{{ $name }}"</span> of id <span class="child-id-deny">C-{{ $id }}</span>?
                            </p>
                            
                            <form id="secondary-deny-account-{{ $key }}" method="POST" action="{{ route('admin.child.access.control.revoke', ['id' => $id]) }}" class="hidden">
                                <input class="hidden" type="text" name="id" value="{{ $staffAccount['id'] }}" />
                                <input class="hidden" type="type" name="type" value="phm" />
                            </form>

                            <c-slot name="close">
                                Cancel
                            </c-slot>

                            <c-slot name="footer">
                                <c-button type="submit" variant="destructive" form="secondary-deny-account-{{ $key }}">
                                    Remove Access
                                </c-button>
                            </c-slot>
                        </c-modal>
                    </div>
                </c-slot>
            </c-card>
        @endforeach
    </div>

    <c-table.pagination :links="$links" />
@endsection