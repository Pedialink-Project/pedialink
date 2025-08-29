@extends('layout/portal')

@section('title')
Home
@endsection

@section('sidebar')
<c-sidebar></c-sidebar>

@endsection

@section('content')
<h1>Home page</h1>

<c-badge $type="primary" $size="lg">
  HI
</c-badge>



@endsection