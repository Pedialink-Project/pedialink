@extends("layout/portal")

@section('title')
    Settings
@endsection

@section('header')
    Settings
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/auth/settings.css') }}">
@endsection

@section('content')
    <div class="settings-card-group">
        <c-card class="settings-card">
            <c-slot name="header">
                <div class="header-settings">
                    <h4>
                        Profile Information
                    </h4>
                    <span>Update your profile's account information and email</span>
                </div>
            </c-slot>

            <form id="update-profile-info" method="POST" action="{{ route('update.profile') }}" class="settings-form">
                <c-input
                    type="name"
                    label="Name"
                    name="name"
                    value="{{ old('name') ?? $name }}"
                    error="{{ errors('name') ?? '' }}"
                    placeholder="Enter your name"
                />
                <c-input
                    type="email"
                    label="Email"
                    name="email"
                    value="{{ old('email') ?? $email }}"
                    error="{{ errors('email') ?? '' }}"
                    placeholder="Enter your email"
                />
            </form>

            <div class="footer-settings">
                <c-button form="update-profile-info" type="submit" variant="primary">Save</c-button>
            </div>
        </c-card>

        <c-card class="settings-card">
            <c-slot name="header">
                <div class="header-settings">
                    <h4>
                        Update Password
                    </h4>
                    <span>Update your profile's account information and email</span>
                </div>
            </c-slot>

            <form id="update-password" method="POST" action="{{ route('update.password') }}" class="settings-form">
                <c-input
                    type="password"
                    label="Current Password"
                    name="currentPassword"
                    error="{{ errors('currentPassword') ?? '' }}"
                    placeholder="Enter your current password"
                />
                <c-input
                    type="password"
                    label="New Password"
                    name="password"
                    error="{{ errors('password') ?? '' }}"
                    placeholder="Enter your new password"
                />
                <c-input type="password" label="Confirm Password" name="confirmPassword" placeholder="Re-enter your new password" />
            </form>

            <div class="footer-settings">
                <c-button form="update-password" type="submit" variant="primary">Save</c-button>
            </div>
        </c-card>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/password-box.js')}}"></script>
@endsection