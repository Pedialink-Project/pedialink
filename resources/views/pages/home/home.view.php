@extends('layout/main')

@section('title')
Home
@endsection

@section('content')
<h1>Home page</h1>

<c-badge $type="green" $size="lg">
  Good
</c-badge>
<c-badge $type="red" $size="lg">
  Critical
</c-badge>
<c-badge $type="purple" $size="lg">
  Upcoming
</c-badge>
<c-badge $type="yellow" $size="lg">
  Pending
</c-badge>
<c-badge $type="blue" $size="lg">
  Parent
</c-badge>

<c-link type="primary" href="{{ route('parent.login') }}">
  Parent Login
</c-link>

<c-link type="primary" href="{{ route('staff.login') }}">
  Staff Login
</c-link>

<c-link type="primary" href="{{ route('test.portal') }}">
  Test Portal
</c-link>

<c-link type="primary" href="{{ route('test.message') }}">
  Test message
</c-link>

<c-modal id="eventDetails" size="sm" :initOpen="false">
    <c-slot name="trigger">
        <c-button variant="primary">Open details</c-button>
    </c-slot>

    <c-slot name="header">
        <div>Event Details</div>
    </c-slot>

    <p>Here goes the modal body — images, form, whatever.</p>

    <c-slot name="footer">
        <c-button type="button" variant="outline" data-modal-close="eventDetails">Cancel</c-button>
        <c-button type="button" variant="primary" data-modal-confirm="eventDetails">Save</c-button>
    </c-slot>
</c-modal>

<form action="{{ route('logout')}}" method="post">
  <c-button type="submit">Logout</c-button>
</form>



@endsection