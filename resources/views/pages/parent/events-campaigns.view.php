@extends('layout/portal')

@section('title')
Parent - Event & Campaigns
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/events-campaigns.css') }}">
@endsection

@section('header')
<div class="title-section">
   <svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path
         d="M12.4385 3.67585L6.8946 6.33709C6.46792 6.5419 6.01203 6.59321 5.54729 6.4891C5.24314 6.42097 5.09105 6.3869 4.96859 6.37291C3.44786 6.19925 2.5 7.40284 2.5 8.78688V9.54644C2.5 10.9305 3.44786 12.1341 4.96859 11.9604C5.09105 11.9464 5.24315 11.9123 5.54729 11.8442C6.01203 11.7401 6.46793 11.7914 6.8946 11.9962L12.4385 14.6575C13.7112 15.2684 14.3475 15.5738 15.057 15.3357C15.7664 15.0977 16.01 14.5868 16.497 13.565C17.8343 10.7593 17.8343 7.57403 16.497 4.76829C16.01 3.74654 15.7664 3.23566 15.057 2.99757C14.3475 2.75949 13.7112 3.06494 12.4385 3.67585Z"
         stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      <path
         d="M12.4385 3.67585L6.8946 6.33709C6.46792 6.5419 6.01203 6.59321 5.54729 6.4891C5.24314 6.42097 5.09105 6.3869 4.96859 6.37291C3.44786 6.19925 2.5 7.40284 2.5 8.78688V9.54644C2.5 10.9305 3.44786 12.1341 4.96859 11.9604C5.09105 11.9464 5.24315 11.9123 5.54729 11.8442C6.01203 11.7401 6.46793 11.7914 6.8946 11.9962L12.4385 14.6575C13.7112 15.2684 14.3475 15.5738 15.057 15.3357C15.7664 15.0977 16.01 14.5868 16.497 13.565C17.8343 10.7593 17.8343 7.57403 16.497 4.76829C16.01 3.74654 15.7664 3.23566 15.057 2.99757C14.3475 2.75949 13.7112 3.06494 12.4385 3.67585Z"
         stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      <path
         d="M10.8334 14.1667V14.5833C10.8334 15.6534 10.8334 16.1884 10.6467 16.4905C10.3978 16.8933 9.94267 17.1208 9.47112 17.0783C9.11745 17.0464 8.68943 16.7254 7.83337 16.0833L6.83337 15.3333C6.01882 14.7224 5.83337 14.3515 5.83337 13.3333V12.0833"
         stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      <path
         d="M10.8334 14.1667V14.5833C10.8334 15.6534 10.8334 16.1884 10.6467 16.4905C10.3978 16.8933 9.94267 17.1208 9.47112 17.0783C9.11745 17.0464 8.68943 16.7254 7.83337 16.0833L6.83337 15.3333C6.01882 14.7224 5.83337 14.3515 5.83337 13.3333V12.0833"
         stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      <path d="M6.25 11.6667V6.66666" stroke="#18181B" stroke-width="1.5" stroke-linecap="round"
         stroke-linejoin="round" />
      <path d="M6.25 11.6667V6.66666" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round"
         stroke-linejoin="round" />
   </svg>


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
   ['id' => 'EVT001', 'title' => 'Health Awareness Campaign', 'subtitle' => 'Join us for a day of health awareness activities and workshops.', 'status' => 'Upcoming', 'date' => '2024-07-15', 'time' => '10:00 AM - 4:00 PM', 'registeredParticipants' => 45, 'totalParticipants' => 100, 'location' => 'Community Center', 'isRegistered' => true, 'isFinished' => false, 'organizer' => 'Health Dept.', 'purpose' => 'Join us for a comprehensive polio vaccination drive targeting children under 5 years. This initiative is part of our ongoing effort to maintain polio-free status in our community. Qualified healthcare professionals will administer the vaccines following WHO guidelines.', 'notes' => ['Please bring your child\'s vaccination record', 'Arrive 15 minutes early for registration', 'Light refreshments will be provided']],
   ['id' => 'EVT002', 'title' => 'Nutrition Workshop', 'subtitle' => 'Learn about balanced diets and healthy eating habits.', 'status' => 'Completed', 'date' => '2024-06-10', 'time' => '1:00 PM - 3:00 PM', 'registeredParticipants' => 30, 'totalParticipants' => 30, 'location' => 'Health Clinic', 'isRegistered' => false, 'isFinished' => true, 'organizer' => 'Nutrition Dept.', 'purpose' => 'Join us for a comprehensive polio vaccination drive targeting children under 5 years. This initiative is part of our ongoing effort to maintain polio-free status in our community. Qualified healthcare professionals will administer the vaccines following WHO guidelines.', 'notes' => ['Please bring your child\'s vaccination record']],
   ['id' => 'EVT003', 'title' => 'Vaccination Drive', 'subtitle' => 'Get your vaccinations done for a healthier community.', 'status' => 'Ongoing', 'date' => '2024-06-20', 'time' => '9:00 AM - 5:00 PM', 'registeredParticipants' => 75, 'totalParticipants' => 150, 'location' => 'Local School', 'isRegistered' => true, 'isFinished' => false, 'organizer' => 'Health Dept.', 'purpose' => 'Join us for a comprehensive polio vaccination drive targeting children under 5 years. This initiative is part of our ongoing effort to maintain polio-free status in our community. Qualified healthcare professionals will administer the vaccines following WHO guidelines.', 'notes' => ['Please bring your child\'s vaccination record']],
   ['id' => 'EVT004', 'title' => 'Mental Health Seminar', 'subtitle' => 'Discuss mental health topics with experts in the field.', 'status' => 'Upcoming', 'date' => '2024-08-05', 'time' => '11:00 AM - 2:00 PM', 'registeredParticipants' => 20, 'totalParticipants' => 50, 'location' => 'Library Conference Room', 'isRegistered' => false, 'isFinished' => false, 'organizer' => 'Mental Health Dept.', 'purpose' => 'Join us for a comprehensive polio vaccination drive targeting children under 5 years. This initiative is part of our ongoing effort to maintain polio-free status in our community. Qualified healthcare professionals will administer the vaccines following WHO guidelines.', 'notes' => ['Please bring your child\'s vaccination record']],
   ['id' => 'EVT005', 'title' => 'First Aid Training', 'subtitle' => "Learn essential first aid skills to help in emergencies.", 'status' => 'Completed', 'date' => '2024-05-25', 'time' => '2:00 PM - 6:00 PM', 'registeredParticipants' => 25, 'totalParticipants' => 25, 'location' => 'Hospital Training Room', 'isRegistered' => false, 'isFinished' => true, 'organizer' => 'Emergency Services', 'purpose' => 'Join us for a comprehensive polio vaccination drive targeting children under 5 years. This initiative is part of our ongoing effort to maintain polio-free status in our community. Qualified healthcare professionals will administer the vaccines following WHO guidelines.', 'notes' => ['Please bring your child\'s vaccination record']],
];

?>
<div class="card-container">
   @foreach ($eventDetails as $key => $event)
   {{
   $badgeType = '';
   if(strtolower($event['status']) == 'completed') {
   $badgeType = 'red';
   } elseif (strtolower($event['status']) == 'upcoming') {
   $badgeType = 'purple';}
   else {
   $badgeType = 'red';
   }


   $buttonType = 'primary';
   $buttonText = 'Book Now';

   if($event['isRegistered'] && !$event['isFinished']){
   $buttonType = 'destructive';
   $buttonText = 'Cancel Booking';
   }elseif($event['isFinished']){
   $buttonType = 'disabled';
   $buttonText = 'Event Finished';}
   }}

   <c-card class="event-card">
      <div class="card-header">
         <span class="event-title">
            {{$event['title']}}
         </span>

         <c-badge type="{{ $badgeType}}">
            {{$event['status']}}
         </c-badge>
      </div>

      <hr>


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
               info="{{ $event['registeredParticipants'] .'/' .$event['totalParticipants'] }}" />
            <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
               info="{{ $event['location'] }}" />
         </c-modal.viewcard>

      </div>

      <div class="card-footer">

         <c-modal id="view-event-{{$key}}" size="md" :initOpen="false">
            <c-slot name="trigger">
               <c-button variant="secondary">
                  View Details
               </c-button>
            </c-slot>

            <c-slot name="headerPrefix">
               <img src="{{ asset('assets/icons/megaphone-02.svg' )}}" />
            </c-slot>

            <c-slot name="header">
               <div>Event Details</div>
            </c-slot>

            <c-slot name="headerSuffix">

               <c-badge type="{{ $badgeType }}">
                  {{$event['status']}}
               </c-badge>
            </c-slot>



            <c-modal.viewcard>
               <c-modal.viewitem icon="{{ asset('assets/icons/megaphone-02.svg') }}" title="Event ID"
                  info="{{ $event['id'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
                  info="{{ $event['date'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                  info="{{ $event['time'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user-group.svg') }}" title="Registered Participants"
                  info="{{ $event['registeredParticipants'] .'/' .$event['totalParticipants'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
                  info="{{ $event['location'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user.svg') }}" title="Organizer"
                  info="{{ $event['organizer'] }}" />
            </c-modal.viewcard>






            <c-modal.viewlist title="Purpose">
               <c-slot name="list">
                  <li>{{ $event['purpose'] }}</li>
               </c-slot>
            </c-modal.viewlist>

            <c-modal.viewlist title="Notes">
               <c-slot name="list">
                  @foreach($event['notes'] as $note)
                  <li>{{ $note }}</li>
                  @endforeach
               </c-slot>
            </c-modal.viewlist>

            <c-slot name="close">
               Cancel
            </c-slot>

            <c-slot name="footer">

               <c-button variant="{{ $buttonType }}">
                  {{ $buttonText }}
               </c-button>
            </c-slot>
         </c-modal>


         @if(!$event['isRegistered'] && !$event['isFinished'])
         <c-modal id="book-event-{{$key}}" size="md" :initOpen="false">
            <c-slot name="trigger">
               <c-button variant="{{ $buttonType }}">
                  {{ $buttonText }}
               </c-button>

            </c-slot>

            <c-slot name="headerPrefix">
               <img src="{{ asset('assets/icons/megaphone-02.svg' )}}" />
            </c-slot>

            <c-slot name="header">
               <div>Event Details</div>
            </c-slot>

            <c-slot name="headerSuffix">

               <c-badge type="{{ $badgeType }}">
                  {{$event['status']}}
               </c-badge>
            </c-slot>



            <c-modal.viewcard>
               <c-modal.viewitem icon="{{ asset('assets/icons/megaphone-02.svg') }}" title="Event ID"
                  info="{{ $event['id'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
                  info="{{ $event['date'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                  info="{{ $event['time'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user-group.svg') }}" title="Registered Participants"
                  info="{{ $event['registeredParticipants'] .'/' .$event['totalParticipants'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
                  info="{{ $event['location'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user.svg') }}" title="Organizer"
                  info="{{ $event['organizer'] }}" />
            </c-modal.viewcard>






            <c-modal.viewlist title="Purpose">
               <c-slot name="list">
                  <li>{{ $event['purpose'] }}</li>
               </c-slot>
            </c-modal.viewlist>

            <c-modal.viewlist title="Notes">
               <c-slot name="list">
                  @foreach($event['notes'] as $note)
                  <li>{{ $note }}</li>
                  @endforeach
               </c-slot>
            </c-modal.viewlist>

            <c-slot name="close">
               Cancel
            </c-slot>

            <c-slot name="footer">

               <c-button variant="{{ $buttonType }}">
                  {{ $buttonText }}
               </c-button>
            </c-slot>
         </c-modal>
         @elseif ($event['isRegistered'] && !$event['isFinished'])
         <c-modal id="cancel-event-{{$key}}" size="md" :initOpen="false">
            <c-slot name="trigger">
                <c-button variant="{{ $buttonType }}">
                  {{ $buttonText }}
               </c-button>
            </c-slot>

            <c-slot name="headerPrefix">
               <img src="{{ asset('assets/icons/megaphone-02.svg' )}}" />
            </c-slot>

            <c-slot name="header">
               <div>Event Details</div>
            </c-slot>

            <c-slot name="headerSuffix">

               <c-badge type="{{ $badgeType }}">
                  {{$event['status']}}
               </c-badge>
            </c-slot>



            <c-modal.viewcard>
               <c-modal.viewitem icon="{{ asset('assets/icons/megaphone-02.svg') }}" title="Event ID"
                  info="{{ $event['id'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
                  info="{{ $event['date'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                  info="{{ $event['time'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user-group.svg') }}" title="Registered Participants"
                  info="{{ $event['registeredParticipants'] .'/' .$event['totalParticipants'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
                  info="{{ $event['location'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user.svg') }}" title="Organizer"
                  info="{{ $event['organizer'] }}" />
            </c-modal.viewcard>






            <c-modal.viewlist title="Purpose">
               <c-slot name="list">
                  <li>{{ $event['purpose'] }}</li>
               </c-slot>
            </c-modal.viewlist>

            <c-modal.viewlist title="Notes">
               <c-slot name="list">
                  @foreach($event['notes'] as $note)
                  <li>{{ $note }}</li>
                  @endforeach
               </c-slot>
            </c-modal.viewlist>

            <c-slot name="close">
               Cancel
            </c-slot>

            <c-slot name="footer">

               <c-button variant="{{ $buttonType }}">
                  {{ $buttonText }}
               </c-button>
            </c-slot>
         </c-modal>



         @endif


      </div>


   </c-card>

   @endforeach
</div>



@endsection