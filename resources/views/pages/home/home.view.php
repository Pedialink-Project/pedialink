@extends('layout/main')

@section('title')
Home
@endsection

@section('content')
<h1>Home page</h1>

<c-badge $type="primary" $size="lg">
  HI
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



@endsection