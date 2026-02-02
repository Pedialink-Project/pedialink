@extends('layout/auth')

@section('title')
    Verify Documents
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/auth/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/auth/parent-verify.css') }}">
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
                @if ($submitted)
                    <h1>Documents submitted</h1>
                    <span class="text-muted">Your documents have been submitted</span>
                @else
                    <h1>Verify Documents</h1>
                    <span class="text-muted">Submit documents here</span>
                @endif
            </div>
    
            <c-card class="form-content">
                @if ($submitted)
                    <div class="submit-info">
                        Your documents have been submitted, wait until the admin verifies the documents
                    </div>
                    <div class="footer">
                        <c-link id="go-back" type="primary" href="{{ route('home') }}">
                            Go to Home
                        </c-link>
                    </div>
                @else
                    <form method="POST" action="{{ route('parent.document.submit') }}" id="verification-documents" enctype="multipart/form-data">
                        <div class="form-inputs">
                            <c-input
                                type="file"
                                label="Birth Certificate"
                                name="birth_certificate"
                                placeholder="Submit birth document"
                                error="{{ errors('birth_certificate_error') ?? '' }}"
                                required
                            />
                            <c-input
                                type="file"
                                label="Marriage Certificate"
                                name="marriage_certificate"
                                placeholder="Submit marriage document"
                                error="{{ errors('marriage_certificate_error') ?? '' }}"
                                required
                            />
                            <c-input
                                type="file"
                                label="NIC copy"
                                name="nic_copy"
                                placeholder="Submit NIC copy"
                                error="{{ errors('nic_copy_error') ?? '' }}"
                                required
                            />
                        </div>
                    </form>
                    <div class="footer">
                        <c-button id="submit-documents" form="verification-documents" type="submit" variant="primary">
                            Submit Documents
                        </c-button>
                    </div>
                @endif

            </c-card>
        </div>
    </div>
@endsection