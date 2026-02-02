@extends('layout/auth')

@section('title')
    Staff Login
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/auth/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/auth/staff-register.css') }}">
@endsection

@section('content')
    <div id="main">
        <div>
            <c-link href="{{ route('home') }}" class="return-link-group">
                <c-slot name="icon">
                    <svg width="25px" height="25px" viewBox="0 0 1024 1024" fill="#000000" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill="" /></svg>
                </c-slot>
                Go to Home
            </c-link>
        </div>
        <div class="form-container">
            <div class="topic">
                <h1>Welcome Back</h1>
                <span class="text-muted">Create your account to continue</span>
            </div>
            <c-card class="form-content">
                <div class="tab-links">
                    <c-link href="{{ route('parent.login') }}" type="subtle">
                        Parent
                    </c-link>
                    <c-link href="{{ route('staff.login') }}" type="subtle" class="active">
                        Staff
                    </c-link>
                </div>
                <div>
                    <form id="staff-register" method="POST" action="{{ route('staff.register.submit', [], ['token' => $token]) }}">
                        <c-input
                            label="Email:"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            type="email"
                            value="{{ $email }}"
                            disabled
                        />
                        <c-select label="Role:" name="role" placeholder="Select your role" name="role" value="{{ $role }}" disabled>
                            <li class="select-item" data-value="phm">Public Health Midwife</li>
                            <li class="select-item" data-value="doctor">Doctor</li>
                        </c-select>
                        <c-input
                            label="Name:"
                            id="name"
                            name="name"
                            placeholder="Enter your name"
                            type="text"
                            value="{{ old('name') ?? ''}}"
                            error="{{ errors('name') ?? '' }}"
                        />
                        <c-input
                            id="nic"
                            name="nic"
                            type="text"
                            label="NIC:"
                            placeholder="Enter your NIC number"
                            value="{{ old('nic') ?? ''}}"
                            error="{{ errors('nic') ?? '' }}"
                        />
                        <c-input
                            label="License No:"
                            id="license_no"
                            name="license_no"
                            placeholder="Enter your License No"
                            type="text"
                            value="{{ old('license_no') ?? ''}}"
                            error="{{ errors('license_no') ?? '' }}"
                        />
                        @if ($role === "phm")
                            <c-select name="division" label="GS Division:" value="{{ old('division') ?? '' }}" error="{{ errors('division') ?? '' }}">
                                @foreach ($areas as $area)
                                    <li class="select-item" data-value="{{ $area->id }}">{{ $area->code }}</li>
                                @endforeach
                            </c-select>
                        @endif
                        <c-input
                            label="Password:"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            type="password"
                            value="{{ old('password') ?? ''}}"
                            error="{{ errors('password') ?? '' }}"
                        />
                        <c-input
                            label="Confirm Password:"
                            id="password"
                            name="confirm_password"
                            placeholder="Enter your password"
                            type="password"
                        />
                    </form>
                </div>
                <div class="footer">
                    <c-button id="sign-up" type="submit" form="staff-register" variant="primary">
                        Sign Up
                    </c-button>
                </div>
            </c-card>
        </div>

    </div>
@endsection
