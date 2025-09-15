@extends('layout/portal')

@section('title')
Parent - Event & Campaigns
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/events-campaigns.css') }}">
@endsection

@section('header')
<div class="title-section">

   <span>Events & Campaigns</span>
</div>
@endsection

@section('header_right')

<c-button variant="outline" class="filter">
   <img src="{{ asset('assets/icons/filter.svg') }}" />
   Date
   <img src="{{ asset('assets/icons/arrow-down-01-round.svg') }}" />
</c-button>

<c-button variant="outline" class="filter">
   <img src="{{ asset('assets/icons/filter.svg') }}" />
   Status
   <img src="{{ asset('assets/icons/arrow-down-01-round.svg') }}" />
</c-button>
<div class="search-box">
   <span class="search-icon" aria-hidden="true">
      <img src="{{ asset('assets/icons/search.svg') }}" />

   </span>
   <input type="search" name="q" placeholder="Search" />
</div>






@endsection

@section('content')

<?php
$eventDetails = [
   ['id' => 'EVT001', 'title' => 'Health Awareness Campaign', 'subtitle' => 'Join us for a day of health awareness activities and workshops.', 'status' => 'Upcoming', 'date' => '2024-07-15', 'time' => '10:00 AM - 4:00 PM', 'registeredParticipants' => 45, 'totalParticipants' => 100, 'location' => 'Community Center'],
   ['id' => 'EVT002', 'title' => 'Nutrition Workshop', 'subtitle' => 'Learn about balanced diets and healthy eating habits.', 'status' => 'Completed', 'date' => '2024-06-10', 'time' => '1:00 PM - 3:00 PM', 'registeredParticipants' => 30, 'totalParticipants' => 30, 'location' => 'Health Clinic'],
   ['id' => 'EVT003', 'title' => 'Vaccination Drive', 'subtitle' => 'Get your vaccinations done for a healthier community.', 'status' => 'Ongoing', 'date' => '2024-06-20', 'time' => '9:00 AM - 5:00 PM', 'registeredParticipants' => 75, 'totalParticipants' => 150, 'location' => 'Local School'],
   ['id' => 'EVT004', 'title' => 'Mental Health Seminar', 'subtitle' => 'Discuss mental health topics with experts in the field.', 'status' => 'Upcoming', 'date' => '2024-08-05', 'time' => '11:00 AM - 2:00 PM', 'registeredParticipants' => 20, 'totalParticipants' => 50, 'location' => 'Library Conference Room'],
   ['id' => 'EVT005', 'title' => 'First Aid Training', 'subtitle' => "Learn essential first aid skills to help in emergencies.",  'status' => 	'Completed',	'date' =>	'2024-05-25',	'time' =>	'2:00 PM - 6:00 PM',	'registeredParticipants' =>	25,	'totalParticipants' =>	25,	'location' =>	'Hospital Training Room'],
];

?>
<div class="card-container">
   @foreach ($eventDetails as $event)
   {{
   $badgeType = '';
   if(strtolower($event['status']) == 'completed') {
   $badgeType = 'red';
   } elseif (strtolower($event['status']) == 'upcoming') {
   $badgeType = 'purple';}
   else {
   $badgeType = 'red';
   }
   }}
   <c-card class="card">
      <div class="card-header">
         <span class="event-title">
            {{$event['title']}}
         </span>

         <c-badge type="{{ $badgeType}}">
            {{$event['status']}}
         </c-badge>
      </div>

      <span class="event-subtitle">
         {{$event['subtitle']}}
      </span>

      <div class="card-body">
         <c-modal.viewcard>
            <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
               info="{{ $event['date'] }}" />
            <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
               info="{{ $event['time'] }}" />
            <c-modal.viewitem icon="{{ asset('assets/icons/user-group.svg') }}" title="Registered Participants"
               info="{{ $event['registeredParticipants'] / $event['totalParticipants'] }}" />
            <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
               info="{{ $event['location'] }}" />
         </c-modal.viewcard>

      </div>

      <c-slot name="footer">
         <c-button variant="secondary">
            View Details
         </c-button>

      </c-slot>


   </c-card>

   @endforeach
</div>



@endsection