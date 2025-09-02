@extends('layout/portal')

@section('title')
Parent Dashboard
@endsection


@section('header')
Good Evening, 
<br>Harry
<c-card>
    Hi
</c-card>
@endsection


@section('content')
<div class="container">
    <c-card class="card">
        <div class="header">
            <div class="title-section">
                <span class="card-title">Upcoming Events</span>
                <span class="card-subtitle">Stay updated with upcoming events</span>
            </div>
            <c-button varient="secondary">View All</c-button>
        </div>
        <div class="card-body">
            <div class="tab">
                
            </div>
        </div>
        
    </c-card>
</div>


@endsection