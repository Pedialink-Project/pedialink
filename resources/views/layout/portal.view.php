<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="css/app.css">
    @yield('css')
</head>

<body>
    <div style="display:flex;">
        <div>
             @yield('sidebar')
        </div>
        <div style="flex:1;">
            @yield('content')
        </div>
    </div>
    @yield('scripts')
</body>

</html>