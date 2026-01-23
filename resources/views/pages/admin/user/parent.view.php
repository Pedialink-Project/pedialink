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
    <div class="parent-approval-content">
        @if (count($parents) <= 0)
            <div class="empty-parents" role="status">
                <div class="empty-parents__content">
                    <svg class="empty-parents__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <rect x="2.5" y="3.5" width="13" height="17" rx="1.2" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="8" y1="7" x2="12" y2="7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="18.2" cy="17.2" r="3" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="20.2" y1="19.2" x2="22" y2="21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>

                    <p class="empty-parents__message">No records found</p>
                </div>
            </div>
        @endif

        @foreach ($parents as $key => $parent)
            <c-card class="approval-card">
                <c-slot name="header">
                    <h3>{{ $parent["name"] }}</h3>
                </c-slot>
                <c-slot name="headerSuffix">
                    <span class="approval-time">
                        {{ time_ago($parent['created_at']) }}
                    </span>
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
                                        info="P-{{ $parent['id'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/user.svg') }}"
                                        title="Full Name"
                                        info="{{ $parent['name'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                        title="Created On"
                                        info="{{ date('Y-m-d', strtotime($parent['created_at'])) }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/student-card.svg') }}"
                                        title="NIC"
                                        info="{{ $parent['nic'] }}"
                                    />
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/mother.svg') }}"
                                        title="Account Type"
                                        info="{{ ucfirst($parent['type']) }}"
                                    />
                                    
                                    <c-modal.viewitem
                                        icon="{{ asset('assets/icons/location-05.svg') }}"
                                        title="Location"
                                        info="{{ ucfirst($parent['division']) }}"
                                    />
                                </c-modal.viewcard>

                                <div>
                                    <h4>Additional Information</h4>
                                    <div class="btn-grp">
                                        <form action="{{ route('admin.user.parent.download', ['id' => $parent['id'], 'type' => 'marriage']) }}" method="get" target="_blank">
                                            <c-button class="download-btn" type="submit" variant="primary">
                                                <img src="{{ asset('assets/icons/download-04.svg') }}" />
                                                Download marriage certificate
                                            </c-button>
                                        </form>
                                        <form action="{{ route('admin.user.parent.download', ['id' => $parent['id'], 'type' => 'birth']) }}" method="get" target="_blank">
                                            <c-button class="download-btn" type="submit" variant="primary">
                                                <img src="{{ asset('assets/icons/download-04.svg') }}" />
                                                Download birth certificate
                                            </c-button>
                                        </form>
                                        <form action="{{ route('admin.user.parent.download', ['id' => $parent['id'], 'type' => 'nic']) }}" method="get" target="_blank">
                                            <c-button class="download-btn" type="submit" variant="primary">
                                                <img src="{{ asset('assets/icons/download-04.svg') }}" />
                                                Download NIC Copy
                                            </c-button>
                                        </form>
                                    </div>
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

                            <form id="approve-account-{{ $key }}" method="POST" action="{{ route('admin.user.parent.approve', ['id' => $parent['id']])}}" class="hidden">

                            </form>

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

                            <form id="deny-account-{{ $key }}" method="POST" action="{{ route('admin.user.parent.deny', ['id' => $parent['id']])}}" class="hidden"></form>

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
                                    info="P-{{ $parent['id'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/user.svg') }}"
                                    title="Full Name"
                                    info="{{ $parent['name'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/calendar-02.svg') }}"
                                    title="Created On"
                                    info="{{ date('Y-m-d', strtotime($parent['created_at'])) }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/student-card.svg') }}"
                                    title="NIC"
                                    info="{{ $parent['nic'] }}"
                                />
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/mother.svg') }}"
                                    title="Account Type"
                                    info="{{ ucfirst($parent['type']) }}"
                                />
                                
                                <c-modal.viewitem
                                    icon="{{ asset('assets/icons/location-05.svg') }}"
                                    title="Location"
                                    info="{{ ucfirst($parent['division']) }}"
                                />
                            </c-modal.viewcard>

                            <c-modal.viewlist title="Additional Information">
                                <c-slot name="list">
                                    <form action="{{ route('admin.user.parent.download', ['id' => $parent['id'], 'type' => 'marriage']) }}" method="get" target="_blank">
                                        <c-button class="download-btn" type="submit" variant="primary">
                                            <img src="{{ asset('assets/icons/download-04.svg') }}" />
                                            Download marriage certificate
                                        </c-button>
                                    </form>
                                    <form action="{{ route('admin.user.parent.download', ['id' => $parent['id'], 'type' => 'birth']) }}" method="get" target="_blank">
                                        <c-button class="download-btn" type="submit" variant="primary">
                                            <img src="{{ asset('assets/icons/download-04.svg') }}" />
                                            Download birth certificate
                                        </c-button>
                                    </form>
                                    <form action="{{ route('admin.user.parent.download', ['id' => $parent['id'], 'type' => 'nic']) }}" method="get" target="_blank">
                                        <c-button class="download-btn" type="submit" variant="primary">
                                            <img src="{{ asset('assets/icons/download-04.svg') }}" />
                                            Download NIC Copy
                                        </c-button>
                                    </form>
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

    @if (count($parents) <= 0)
        <c-table.pagination />
    @else
        <c-table.pagination :links="$links" />
    @endif
@endsection