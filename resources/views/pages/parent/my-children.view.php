@extends('layout/portal')

@section('title')
Parent - My Childern
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/my-children.css') }}">
@endsection

@section('header')
<div class="top-section">
   <span>My Children</span>
   <div class="search-box"></div>
</div>
@endsection

@section('content')
<?php
$childDetails = [
   [
      "image" => "",
      "name" => "Sara Johnson",
      "nickname" => "Baby Sara",
      "status" => "Good",
      "dob" => "22-10-2023",
      "age" => "2 Years old",
      "phm" => "Dr. Smith",
      "appointments" => 1,
      "vaccinations" => 0
   ],
   [
      "image" => "",
      "name" => "Sara Johnson",
      "nickname" => "Baby Sara",
      "status" => "Critical",
      "dob" => "22-10-2023",
      "age" => "2 Years old",
      "phm" => "Dr. Smith",
      "appointments" => 1,
      "vaccinations" => 0
   ],
   [
      "image" => "",
      "name" => "Sara Johnson",
      "nickname" => "Baby Sara",
      "status" => "Good",
      "dob" => "22-10-2023",
      "age" => "2 Years old",
      "phm" => "Dr. Smith",
      "appointments" => 1,
      "vaccinations" => 0
   ],
];
?>

<div class="container">
   @foreach ($childDetails as $child)
   <?php $words = explode(" ", $child["name"]);
   $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
   ?>
   <c-card class="card">
      <div class="card-header">
         <div class="header-left">
            <div class="profile-pic">
               @if ($child['image'])
               <img src="{{ asset('images/' . $child['image']) }}" alt="Profile Picture">
               @else
               <div class="initials">{{ $initials }}</div>
               @endif
            </div>
            <div class="child-info">
               <h3 class="child-name">{{ $child['name'] }}</h3>
               <p class="nickname">{{ $child['nickname'] }}</p>
            </div>
         </div>
         <c-badge type="primary">{{$child['status']}}</c-badge>
      </div>
      <div class="card-body">
         <div class="detail-row">
            <span class="label">Age</span>
            <span class="value">{{ $child['age'] }}</span>
         </div>
         <div class="detail-row">
            <span class="label">Date of Birth</span>
            <span class="value">{{ $child['dob'] }}</span>
         </div>
         <div class="detail-row">
            <span class="label">Appointments</span>
            <span class="value">{{ $child['appointments'] }} Scheduled</span>
         </div>
         <div class="detail-row">
            <span class="label">Vaccinations</span>
            <span class="value">{{ $child['vaccinations'] }} UpComing</span>
         </div>

      </div>
      <div class="card-footer">
         <c-button varient="primary" size="lg">View Details</c-button>
      </div>

   </c-card>

   @endforeach
</div>





@endsection