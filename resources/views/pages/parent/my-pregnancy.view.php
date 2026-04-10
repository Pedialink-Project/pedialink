@extends('layout/portal')

@section('title')
Parent - My Pregnancy
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/my-pregnancy.css') }}">
@endsection

@section('header')
<div class="title-section">
    <svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_474_12888)">
            <circle cx="9.99996" cy="10" r="8.33333" stroke="#18181B" stroke-width="1.5" />
            <path d="M11.6667 13.3333C11.1893 13.8599 10.6163 14.1667 10 14.1667C9.3838 14.1667 8.81075 13.8599 8.33337 13.3333" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
            <path d="M7.50004 9.58333C7.26135 9.32006 6.97483 9.16666 6.66671 9.16666C6.35859 9.16666 6.07206 9.32006 5.83337 9.58333" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
            <path d="M14.1667 9.58333C13.928 9.32006 13.6415 9.16666 13.3333 9.16666C13.0252 9.16666 12.7387 9.32006 12.5 9.58333" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
            <path d="M10 1.66667C8.61929 1.66667 7.5 2.78596 7.5 4.16667C7.5 5.54738 8.61929 6.66667 10 6.66667C10.6403 6.66667 11.2244 6.42596 11.6667 6.03009" stroke="#18181B" stroke-width="1.5" stroke-linecap="round" />
        </g>
        <defs>
            <clipPath id="clip0_474_12888">
                <rect width="20" height="20" fill="white" />
            </clipPath>
        </defs>
    </svg>

    <span>My Pregnancy</span>
</div>
@endsection

@section('content')

@if(!$pregnancies)
<c-emptytable
    alt="No Pregnancy Details"
    title="No Pregnancy Details Available"
    description="There is no maternal profile linked to your account yet. Please contact your Public Health Midwife to create one." />
@else
<div class="card-container">
    @foreach ($pregnancies as $index => $pregnancy)
    <?php $words = explode(" ", $maternal["name"]);
    $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
    ?>
    <c-card class="card">
        <div class="card-header">
            <div class="header__left">
                <div class="profile-pic">
                    <div class="initials">0{{ $index + 1 }}</div>
                </div>
                <div class="preg-info">
                    <h3 class="preg-number">Pregnancy {{ $index + 1 }}</h3>
                </div>
            </div>
            @if($pregnancy['delivery_outcome'] === 'ongoing')
            <c-badge type="green">
                Ongoing
            </c-badge>
            @endif
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="label">Age at Start Date</span>
                <span class="value">
                    {{$pregnancy['age_at_lmp']}} years
                </span>
            </div>
            <div class="detail-row">
                <span class="label">Start Date</span>
                <span class="value">{{ $pregnancy['lmp'] }}</span>
            </div>
            <div class="detail-row">
                <span class="label">End Date</span>
                <span class="value">{{ $pregnancy['edd'] }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Delivery Outcome</span>
                <span class="value">{{ ucfirst(str_replace('_', ' ', $pregnancy['delivery_outcome'])) }}</span>
            </div>


        </div>

    </c-card>

    @endforeach
</div>
@endif

@endsection