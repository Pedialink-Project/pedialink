@extends('layout/portal')

@section('title')
Doctor Appoinments Configure
@endsection

@section('header')
<svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.08337 10C2.08337 6.26806 2.08337 4.40208 3.24274 3.24271C4.40211 2.08334 6.26809 2.08334 10 2.08334C13.732 2.08334 15.598 2.08334 16.7573 3.24271C17.9167 4.40208 17.9167 6.26806 17.9167 10C17.9167 13.732 17.9167 15.5979 16.7573 16.7573C15.598 17.9167 13.732 17.9167 10 17.9167C6.26809 17.9167 4.40211 17.9167 3.24274 16.7573C2.08337 15.5979 2.08337 13.732 2.08337 10Z" stroke="#3A3C41" stroke-width="1.5"/>
        <path d="M9.16663 5.83334L14.1666 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 5.83334L6.66671 5.83334" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 10L6.66671 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M5.83337 14.1667L6.66671 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M9.16663 10L14.1666 10" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M9.16663 14.1667L14.1666 14.1667" stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
    Appointments Configure
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/doctor/configure.css') }}">
@endsection

@section('content')

    <c-table.controls :filters="['status' => ['active', 'inactive']]" action="{{ route('doctor.appointments.configure')}}">
        <c-slot name="filter">
            <c-button variant="outline">
                <img src="{{ asset('assets/icons/filter.svg') }}" />
                Status
            </c-button>
        </c-slot>
        <c-slot name="extrabtn">
            <c-modal id="add-configure-modal" size="sm" :initOpen="flash('add') ? true : false">
                <c-slot name="trigger">
                    <c-button class="add-vaccine-btn" variant="primary">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_967_38088)">
                                <path d="M10.0001 6.66602V13.3327M13.3334 9.99935L6.66675 9.99935" stroke="#FAFAFA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.3334 9.99967C18.3334 5.3973 14.6025 1.66634 10.0001 1.66634C5.39771 1.66634 1.66675 5.3973 1.66675 9.99967C1.66675 14.602 5.39771 18.333 10.0001 18.333C14.6025 18.333 18.3334 14.602 18.3334 9.99967Z" stroke="#FAFAFA" stroke-width="1.5"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_967_38088">
                                    <rect width="20" height="20" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                        <span>Add Weekday</span>