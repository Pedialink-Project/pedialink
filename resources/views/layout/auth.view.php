<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>
    @yield('content')
    @yield('scripts')

    <script src="{{ asset('js/password-box.js') }}"></script>
    <c-toast :initToasts="flash('_message') ?? null"></c-toast>
</body>
</html>