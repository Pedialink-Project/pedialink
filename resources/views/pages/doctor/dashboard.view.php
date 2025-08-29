@extends('layout/doctor')

@section('content')
    You are logged in as doctor

    <form action="{{ route('logout') }}" method="post">
        <button>Logout</button>
    </form>
@endsection