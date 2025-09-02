<?php
// resources/views/components/navbar.view.php
// Attributes: $class (extra), $brand (logo path or HTML)
$uid = 'navbar_' . bin2hex(random_bytes(6));
$class = $class ?? '';
?>
<nav id="{{ $uid }}" class="app-navbar {{ $class }}">
  <div class="app-navbar__left">
    <button class="nav-toggle-btn" id="{{ $uid }}_toggle" aria-label="Toggle sidebar" title="Toggle sidebar">
      <!-- hamburger -->
      <img src="{{ asset('assets/icons/sidebar-left.svg') }}" />
    </button>
  </div>

  <div class="app-navbar__center">
    @if (!empty($slots['search']))
      {{ $slots['search'] }}
    @else
      <div class="nav-search" role="search">
        <span class="search-icon" aria-hidden="true">
        <img src="{{ asset('assets/icons/search.svg') }}" />

        </span>
        <input type="search" name="q" placeholder="Search" />
      </div>
    @endif
  </div>

  <div class="app-navbar__right">
    <button class="icon-btn" aria-label="Notifications" title="Notifications">
      <img src="{{ asset('assets/icons/notification-02.svg') }}" />
    </button>

    <div class="nav-user" role="button" aria-haspopup="true">
      <div class="avatar">
        <img src="{{ asset('assets/avatar-placeholder.png') }}" alt="User avatar">
      </div>
      <div class="user-meta">
        <div class="user-name">Eva Mendes</div>
        <div class="user-role">Doctor</div>
      </div>
    </div>
  </div>
</nav>

<script>
(() => {
    const uid = `{{ $uid }}`;
    const root = document.getElementById(uid);
    if (!root) {
        return;
    }

    const toggleBtn = document.getElementById(uid + '_toggle');

    // Toggle behaviour:
    // - On desktop: toggle collapsed rail class 'sidebar-collapsed' on body
    // - On small screens (<= 920px): toggle overlay open 'show-sidebar' on body
    const SMALL_PX = 920;

    const isSmall = () => window.innerWidth <= SMALL_PX;

    const onToggle = (e) => {
        e.preventDefault();
        if (isSmall()) {
            // mobile: show overlay sidebar
            document.body.classList.toggle('show-sidebar');
            // ensure collapsed isn't interfering
            document.body.classList.remove('sidebar-collapsed');
        } else {
            // desktop: collapse / expand rail
            document.body.classList.toggle('sidebar-collapsed');
            // make sure overlay state removed
            document.body.classList.remove('show-sidebar');
        }
    };

    toggleBtn.addEventListener('click', onToggle);

    // Close overlay if user clicks outside the sidebar when overlay is open
    document.addEventListener('click', (ev) => {
        if (!document.body.classList.contains('show-sidebar')) {
            return;
        }

        // if click is inside navbar toggle or inside sidebar, keep open
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) {
            return;
        }

        if (sidebar.contains(ev.target)) {
            return;
        }

        const toggle = toggleBtn;
        if (toggle && toggle.contains(ev.target)) {
            return;
        }

        // otherwise close
        document.body.classList.remove('show-sidebar');
    });

    // close overlay on Escape
    document.addEventListener('keydown', (ev) => {
        if (ev.key === 'Escape') {
            document.body.classList.remove('show-sidebar');
        }
    });

    // clean up on resize: if switching to large screen, remove overlay
    window.addEventListener('resize', () => {
        if (!isSmall()) {
            document.body.classList.remove('show-sidebar');
        }
    });
})();
</script>
