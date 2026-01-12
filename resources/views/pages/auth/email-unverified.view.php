@extends('layout/auth')

@section('title')
    Verify Email
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/auth/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/auth/verify-email.css') }}">
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
                <h1>Verify Email</h1>
                <span class="text-muted">Resend your email verification</span>
            </div>
    
            <c-card class="form-content">
                <div class="verify-info">
                    Your email is unverified. Click on the verification link on the email that has been sent
                    or resend the verification email by clicking the below link.
                </div>

                <form method="POST" action="{{ route('email.verify.send') }}" class="hidden" id="resend-verification"></form>

                <div class="footer">
                    
                    @if ($blocked == true)
                        <c-button id="resend-email" type="submit" form="resend-verification" variant="primary" disabled>
                            Resend Email
                        </c-button>
                        <div id="retry-info">
                            Retry in <span class="seconds">00.59</span>
                        </div>
                    @else
                        <c-button id="resend-email" type="submit" form="resend-verification" variant="primary">
                            Resend Email
                        </c-button>
                    @endif
                </div>
            </c-card>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function displayTimeout() {
            const retryBox = document.getElementById("retry-info");
            const seconds = retryBox.querySelector(".seconds");
            const resendBtn = document.getElementById("resend-email");
            let remaining = 59;

            const formatTime = (seconds) => {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                return `${m}:${s.toString().padStart(2, "0")}`;
            };

            seconds.textContent = formatTime(remaining);

            const interval = setInterval(() => {
                remaining--;

                if (remaining <= 0) {
                    retryBox.style.display = "None";
                    resendBtn.disabled = false;
                    return;
                }

                seconds.textContent = formatTime(remaining);
            }, 1000);

            return interval;

        }

        @if ($blocked == true)
            displayTimeout();
        @endif
    </script>
@endsection