@extends('layout/parent')

@section('content')
    You are logged in as parent

    <form action="{{ route('logout') }}" method="post">
        <button>Logout</button>
    </form>
@endsection