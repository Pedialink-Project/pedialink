@extends('layout/admin')

@section('content')
    You are logged in as admin

    <form action="{{ route('logout') }}" method="post">
        <button>Logout</button>
    </form>
@endsection