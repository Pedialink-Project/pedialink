@extends('layout/auth')

@section('title')
    Parent Register
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/auth/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/auth/parent-register.css') }}">
@endsection

@section('content')
    <div id="main">
        <div>
            <c-link href="{{ route('parent.login') }}" class="return-link-group">
                <c-slot name="icon">
                    <svg width="25px" height="25px" viewBox="0 0 1024 1024" fill="#000000" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill="" /></svg>
                </c-slot>
                Back to Login
            </c-link>
        </div>
        <div class="form-container">
            <div class="topic">
                <h1>Create Account</h1>
            </div>
            <c-card class="form-content">
                <div class="tab-links">
                    <c-link href="{{ route('parent.login') }}" type="subtle" class="active">
                        Parent
                    </c-link>
                    <c-link href="{{ route('staff.login') }}" type="subtle">
                        Staff
                    </c-link>
                </div>
                <div>
                    <form id="parent-register" action="">
                        <c-input label="Email:" placeholder="First Name" type="text" />
                        <c-input label="Email:" placeholder="Last Name" type="text" />
                        <c-input label="Email:" placeholder="Enter your email" type="email" />
                        <c-input label="Password:" placeholder="Enter your password" type="password" />
                        <c-input label="Confirm Password:" placeholder="Confirm your password" type="password" />
                        <!-- <c-textarea id="text1" label="Address">dsads</c-textarea> -->
                    </form>
                    <div class="terms-conditions-group">
                        
                    </div>
                </div>
                <div class="footer">
                    <c-button id="next-page" form="parent-register" variant="primary">
                        Next
                    </c-button>
                </div>
            </c-card>
        </div>

    </div>
@endsection
