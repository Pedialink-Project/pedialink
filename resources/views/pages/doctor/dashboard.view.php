@extends('layout/portal')

@section('title')
Doctor Dashboard
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/doctor/dashboard.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection



@section('content')
<div class="top-section">

    <section class="greet">
        <h1>Welcome <span class="user-name">{{ auth()->check() ? auth()->user()->name : 'Parent Name'}}</span>
        </h1>
    </section>
    <section class="pill-container">
        <c-pill>
            <c-slot name="title">Assigned Patients</c-slot>
            <c-slot name="number">{{ $patientsCount }}</c-slot>
            <c-slot name="icon">
                <img src="{{asset('assets/icons/baby-01.svg')}}">
            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Appoinments</c-slot>
            <c-slot name="number">{{ $appointmentsCount }}</c-slot>
            <c-slot name="icon">
                <img src="{{asset('assets/icons/profile.svg')}}">
            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Urgent Cases</c-slot>
            <c-slot name="number">{{ $urgentCasesCount }}</c-slot>
            <c-slot name="icon">
                <img src="{{asset('assets/icons/vaccine.svg')}}">
            </c-slot>
        </c-pill>
    </section>
</div>

<main class="container">
    <div class="left-col">


        <!-- Upcoming Events Card -->
        <c-card class="card growth-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Patient Case Overview</span>
                    <span class="card-subtitle">Risk rates of assigned patients</span>
                </div>
                <!-- Child Selector -->
                <c-select name='child' class="select-type" placeholder="Select Type" value="children">
                    <li id="select-children-chart" class="select-item" data-value="children">Children</li>
                    <li id="select-mother-chart" class="select-item" data-value="mothers">Mothers</li>
                </c-select>
            </div>
            <hr class="divider">
            <div class="card-body growth-card">
                <canvas id="riskChart">
                </canvas>
                <canvas id="riskChartMother">
                </canvas>
            </div>
        </c-card>

        <!-- Upcoming Appoinments Card -->
        <c-card class="card appoinment-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Upcoming Appoinments</span>
                    <span class="card-subtitle">Your scheduled visits to the clinic</span>
                </div>
                <c-link type="secondary" href="{{ route('doctor.appointments.overview') }}">View Schedule</c-link>
            </div>
            <hr class="divider">
            <div class="card-body">
                @if(count($upcomingAppointments) <= 0)
                    <c-emptycard
                        title="No upcoming appointments"
                        description="There are no scheduled appointments in the near future."
                    />