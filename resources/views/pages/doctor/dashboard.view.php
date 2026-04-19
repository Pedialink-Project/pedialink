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