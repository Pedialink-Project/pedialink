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


<c-table.controls action="{{ route('parent.events.campaigns') }}"  :filters="['status' => ['upcoming', 'ongoing', 'completed', 'cancelled']]">



</c-table.controls>



@endsection

@section('content')

@if(empty($events))
<div class="no-data">
   <img src="{{ asset('assets/icons/empty-data.svg') }}" alt="No Events" />
   <h2>No Events & Campaigns Available</h2>
   <p>Try adjusting your filters or check back later for new events and campaigns.</p>
</div>
@endif
<div class="card-container">
   @foreach ($events as $key => $event)
   {{
   $badgeType = '';
   if(strtolower($event['event_status']) == 'completed') {
   $badgeType = 'red';
   } elseif (strtolower($event['event_status']) == 'upcoming') {
   $badgeType = 'purple';}
   else {
   $badgeType = 'red';
   }
   }}

   <c-card class="event-card">
      <div class="card-header">
         <span class="event-title">
            {{$event['title']}}
         </span>

         <c-badge type="{{ $badgeType}}">
            {{ucfirst($event['event_status'])}}
         </c-badge>
      </div>

      <hr>


      <span class="event-subtitle">
         {{$event['description']}}
      </span>

      <div class="card-body">
         <c-modal.viewcard>
            <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
               info="{{ $event['event_date'] }}" />
            <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
               info="{{ $event['event_time'] }}" />
            <c-modal.viewitem icon="{{ asset('assets/icons/user-group.svg') }}" title="Registered Participants"
               info="{{ $event['participants_count'] .'/' .$event['max_count'] }}" />
            <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
               info="{{ $event['event_location'] }}" />
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
                  {{ucfirst($event['event_status'])}}
               </c-badge>
            </c-slot>



            <c-modal.viewcard>
               <c-modal.viewitem icon="{{ asset('assets/icons/megaphone-02.svg') }}" title="Event ID"
                  info="{{ $event['id'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
                  info="{{ $event['event_date'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                  info="{{ $event['event_time'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user-group.svg') }}" title="Registered Participants"
                  info="{{ $event['participants_count'] .'/' .$event['max_count'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
                  info="{{ $event['event_location'] }}" />
               <c-modal.viewitem icon="{{ asset('assets/icons/user.svg') }}" title="Organizer"
                  info="{{ $event['admin']['name'] }}" />
            </c-modal.viewcard>






            <c-modal.viewlist title="Purpose">
               <c-slot name="list">
                  <li>{{ $event['purpose'] }}</li>
               </c-slot>
            </c-modal.viewlist>

            <c-modal.viewlist title="Notes">
               <c-slot name="list">

                  <li>{{ $event['notes'] }}</li>

               </c-slot>
            </c-modal.viewlist>

            <c-slot name="close">
               Close
            </c-slot>


         </c-modal>

         @if($event['booking_status'] == NULL && $event['participants_count'] < $event['max_count'] && strtolower($event['event_status']) == 'upcoming')
         <c-modal id="book-event-{{$key}}" size="md" :initOpen="flash('booked') == $event['id'] ? true : false">
            <c-slot name="trigger">
               <c-button variant="primary">
                  Book Now
               </c-button>

            </c-slot>

            <c-slot name="headerPrefix">
               <img src="{{ asset('assets/icons/megaphone-02.svg' )}}" />
            </c-slot>

            <c-slot name="header">
               <div>Book Event </div>
            </c-slot>

            <c-slot name="headerSuffix">

               <c-badge type="{{ $badgeType }}">
                  {{ucfirst($event['event_status'])}}
               </c-badge>
            </c-slot>

            <div class="info-card">
               <span class="title">
                  Current Event
               </span>

               <c-modal.viewcard>

                  <c-modal.viewitem icon="{{ asset('assets/icons/megaphone-02.svg') }}" title="Event"
                     info="{{ $event['title'] }}" />
                  <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
                     info="{{ $event['event_date'] }} " />
                  <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                     info="{{ $event['event_time'] }}" />
                  <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
                     info="{{ $event['event_location'] }}" />
                  <c-modal.viewitem icon="{{ asset('assets/icons/user.svg') }}" title="Organizer"
                     info="{{ $event['admin']['name'] }}" />



               </c-modal.viewcard>


            </div>




            <form id="book-event-form-{{$key}}" action="{{route('parent.events.campaigns.book', ['id' => $event['id']])}} " method="POST" novalidate>
               <c-input type="text" label="Name " name="name"   value="{{ old('name') ?? '' }}"
                        error="{{ errors('name') ?? '' }}" placeholder="Enter Participant Name" required />
               <c-input type="email" label="Email " name="email"  value="{{ old('email') ?? '' }}"
                        error="{{ errors('email') ?? '' }}" placeholder="Enter Email" required />
               <c-input type="text" label="Phone Number " name="phone"  value="{{ old('phone') ?? '' }}"
                        error="{{ errors('phone') ?? '' }}" placeholder="Enter Phone number" required>
                  <c-slot name="prefix">
                     +94
                  </c-slot>
                </c-input>
            </form>


            <c-slot name="close">
               Cancel
            </c-slot>

            <c-slot name="footer">

               <c-button variant="primary" type="submit" form="book-event-form-{{$key}}">
                  Book Now
               </c-button>
            </c-slot>
         </c-modal>
         @elseif ($event['booking_status'] == 'booked' && strtolower($event['event_status']) == 'upcoming')
         <c-modal id="cancel-event-{{$key}}" size="md" :initOpen="flash('cancelBooking') == $event['id'] ? true : false">
            <c-slot name="trigger">
               <c-button variant="destructive">
                  Cancel Booking
               </c-button> </c-slot>

            <c-slot name="headerPrefix">
               <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                     d="M4.43484 8.56878C6.44624 5.00966 7.45193 3.2301 8.83197 2.77202C9.59117 2.52 10.409 2.52 11.1682 2.77202C12.5482 3.2301 13.5539 5.00966 15.5653 8.56878C17.5767 12.1279 18.5824 13.9075 18.2807 15.3575C18.1148 16.1552 17.7059 16.8787 17.1126 17.4244C16.0343 18.4163 14.0229 18.4163 10.0001 18.4163C5.97729 18.4163 3.96589 18.4163 2.88755 17.4244C2.29431 16.8787 1.88541 16.1552 1.71943 15.3575C1.41774 13.9075 2.42344 12.1279 4.43484 8.56878Z"
                     stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" />
                  <path
                     d="M4.43484 8.56878C6.44624 5.00966 7.45193 3.2301 8.83197 2.77202C9.59117 2.52 10.409 2.52 11.1682 2.77202C12.5482 3.2301 13.5539 5.00966 15.5653 8.56878C17.5767 12.1279 18.5824 13.9075 18.2807 15.3575C18.1148 16.1552 17.7059 16.8787 17.1126 17.4244C16.0343 18.4163 14.0229 18.4163 10.0001 18.4163C5.97729 18.4163 3.96589 18.4163 2.88755 17.4244C2.29431 16.8787 1.88541 16.1552 1.71943 15.3575C1.41774 13.9075 2.42344 12.1279 4.43484 8.56878Z"
                     stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" />
                  <path
                     d="M10.2017 14.6667V11.3333C10.2017 10.9405 10.2017 10.7441 10.0797 10.622C9.95766 10.5 9.76125 10.5 9.36841 10.5"
                     stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" stroke-linecap="round"
                     stroke-linejoin="round" />
                  <path
                     d="M10.2017 14.6667V11.3333C10.2017 10.9405 10.2017 10.7441 10.0797 10.622C9.95766 10.5 9.76125 10.5 9.36841 10.5"
                     stroke="#DC2626" stroke-opacity="0.9" stroke-width="1.5" stroke-linecap="round"
                     stroke-linejoin="round" />
                  <path d="M9.99325 8H10.0007" stroke="#DC2626" stroke-opacity="0.9" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M9.99325 8H10.0007" stroke="#DC2626" stroke-opacity="0.9" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" />
               </svg>
            </c-slot>

            <c-slot name="header">
               <span class="cancel">Cancel Event</span>

            </c-slot>

            <div class="info-card">
               <span class="title">
                  Current Event
               </span>

               <c-modal.viewcard>

                  <c-modal.viewitem icon="{{ asset('assets/icons/megaphone-02.svg') }}" title="Event"
                     info="{{ $event['title'] }}" />
                  <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date"
                     info="{{ $event['event_date'] }} " />
                  <c-modal.viewitem icon="{{ asset('assets/icons/clock-01.svg') }}" title="Time"
                     info="{{ $event['event_time'] }}" />
                  <c-modal.viewitem icon="{{ asset('assets/icons/location-05.svg') }}" title="Location"
                     info="{{ $event['event_location'] }}" />
                  <c-modal.viewitem icon="{{ asset('assets/icons/user.svg') }}" title="Organizer"
                     info="{{ $event['admin']['name'] }}" />



               </c-modal.viewcard>


            </div>

            <div class="msg">
               Are you sure you want to cancel booking of this Evenr? This action cannot be undone.
            </div>



            <form id="cancel-event-form-{{ $key }}" action="{{route('parent.events.campaigns.cancel', ['id' => $event['id']])}}" method="POST" novalidate>
               <c-input type="text" name="reason" label="Reason for Cancellation" placeholder="Enter your reason" value="{{ old('reason') ?? '' }}"
                        error="{{ errors('reason') ?? '' }}"
                  required />
            </form>

            <c-slot name="close">
               Close
            </c-slot>

            <c-slot name="footer">
               <c-button variant="destructive" type="submit" form="cancel-event-form-{{$key}}">
                  Cancel Booking
               </c-button>


            </c-slot>
         </c-modal>
         @elseif (strtolower($event['event_status']) == 'completed')
         <c-button variant="disabled">
            Event Completed
         </c-button>
         @elseif (strtolower($event['event_status']) == 'ongoing')
         <c-button variant="disabled">
            Event Ongoing
         </c-button>
          @elseif (strtolower($event['booking_status']) == 'cancelled')
         <c-button variant="disabled">
            Booking Cancelled
         </c-button>
         @else
         <c-button variant="disabled">
            Cancelled
         </c-button>
         @endif
      </div>
   </c-card>
   @endforeach
</div>
@endsection