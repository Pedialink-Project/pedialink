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