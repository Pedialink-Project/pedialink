@extends('layout/phm')

@section('content')
    You are logged in as PHM

    <form action="{{ route('logout') }}" method="post">
        <button>Logout</button>
    </form>
@endsection