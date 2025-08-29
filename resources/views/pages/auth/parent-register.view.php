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
            <c-link href="{{ (!empty($final) && $final) ? route('parent.register') : route('parent.login') }}" class="return-link-group">
                <c-slot name="icon">
                    <svg width="25px" height="25px" viewBox="0 0 1024 1024" fill="#000000" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill="" /></svg>
                </c-slot>
                @if (!empty($final) && $final)
                    Back
                @else 
                    Back to Login
                @endif
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
                    @if (!empty($final) && $final)
                        <form id="parent-register-final" method="POST" action="{{ route('parent.register.final.submit') }}">
                            <c-select name="type" label="Account Type:" value="{{ old('type') ?? ''}}" error="{{ errors('type') ?? '' }}">
                                <li class="select-item" data-value="mother">Mother</li>
                                <li class="select-item" data-value="father">Father</li>
                            </c-select>
                            <c-input id="nic" name="nic" type="text" label="NIC:" placeholder="Enter your NIC number" value="{{ old('nic') ?? ''}}" error="{{ errors('nic') ?? '' }}" />
                            <c-textarea id="address" name="address" label="Address:" error="{{ errors('address') ?? '' }}">{{ old('address') ?? '' }}</c-textarea>
                            <c-select name="division" label="GS Division:" value="{{ old('division') ?? '' }}" error="{{ errors('division') ?? '' }}">
                                @foreach ($areas as $area)
                                    <li class="select-item" data-value="{{ $area->id }}">{{ $area->code }}</li>
                                @endforeach
                            </c-select>
                        </form>
                    @else
                        <form id="parent-register" method="POST" action="{{ route('parent.register.submit') }}">
                            <c-input
                                label="First Name:"
                                id="firstName"
                                name="firstName"
                                placeholder="First Name"
                                type="text"
                                value="{{ old('firstName') ?? '' }}"
                                error="{{ errors('firstName') ?? '' }}"
                            />
                            <c-input 
                                label="Last Name:" 
                                id="lastName" 
                                name="lastName" 
                                placeholder="Last Name" 
                                type="text" 
                                value="{{ old('lastName') ?? '' }}" 
                                error="{{ errors('lastName') ?? '' }}"
                            />
                            <c-input 
                                label="Email:" 
                                id="email" 
                                name="email" 
                                placeholder="Enter your email" 
                                type="email" 
                                value="{{ old('email') ?? '' }}" 
                                error="{{ errors('email') ?? '' }}"
                            />
                            <c-input 
                                label="Password:" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password" 
                                type="password" 
                                value="{{ old('password') ?? '' }}" 
                                error="{{ errors('password') ?? '' }}"
                            />
                            <c-input 
                                label="Confirm Password:" 
                                id="confirmPassword" 
                                name="confirmPassword" 
                                placeholder="Confirm your password" 
                                type="password"
                            />
                        </form>
                    @endif
                    <div class="terms-conditions-group">
                        
                    </div>
                </div>
                <div class="footer">
                    @if (!empty($final) && $final)
                        <c-button id="sign-up" form="parent-register-final" variant="primary">
                            Sign Up
                        </c-button>
                    @else
                        <c-button id="next-page" form="parent-register" variant="primary">
                            Next
                        </c-button>
                    @endif
                </div>
            </c-card>
        </div>

    </div>
@endsection
