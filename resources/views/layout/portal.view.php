<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/portal.css') }}">
    
    @yield('css')
</head>

<body>
    <div class="app-layout">
        <c-sidebar 
            type="{{ auth()->check() ? auth()->user()->role : 'parent' }}"
        />
        <c-navbar.portal />
        <div class="sidebar-overlay" onclick="document.body.classList.remove('show-sidebar')"></div>
        <main class="app-main">
            <div class="content-inner">
                <header class="page-header">
                    <h1 class="page-title">@yield('header')</h1>
                    <div class="header__right">@yield('header_right')</div>
                </header>
                <div>
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    @yield('scripts')

    <c-toast :initToasts="flash('_message') ?? null"></c-toast>
</body>

</html>