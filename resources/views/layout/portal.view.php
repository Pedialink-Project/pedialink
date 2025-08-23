<div class="app-wrapper" style="display:flex;">
    <c-sidebar $type="<?= $_SESSION['role'] ?? 'guest' ?>" $class="my-sidebar">
        <c-slot name="header"><h3>Menu</h3></c-slot>
        <c-slot name="footer"><p>Help & Support</p></c-slot>
    </c-sidebar>

    <div id="main-content" style="flex:1; padding:20px;">
        @yield('content')
    </div>
</div>

