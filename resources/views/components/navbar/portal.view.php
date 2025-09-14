<?php
// resources/views/components/navbar.view.php
// Attributes: $class (extra), $brand (logo path or HTML)
$uid = 'navbar_' . bin2hex(random_bytes(6));
$class = $class ?? '';

$notifications = auth()->user()->notifications ?? [
  [
    'message' => 'New user registered',
    'time' => '2 hours',
    'read' => false,
  ],
  [
    'message' => 'Server rebooted',
    'time' => '1 day',
    'read' => true,
  ],

];

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
    <c-dropdown.main>
      <c-slot name="trigger">
        <c-button vareint="outline" class="icon-btn" aria-label="Notifications" title="Notifications">
          <img src="{{ asset('assets/icons/notification-02.svg') }}" />
        </c-button>
      </c-slot>

      <c-slot name="menu">
        <div class="notification-menu">
          <div class="header-section">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M2.10831 12.308C1.9311 13.4697 2.72337 14.276 3.69342 14.6779C7.41238 16.2185 12.5877 16.2185 16.3067 14.6779C17.2767 14.276 18.069 13.4697 17.8918 12.308C17.7829 11.5941 17.2443 10.9996 16.8454 10.4191C16.3228 9.64941 16.2708 8.80988 16.2708 7.91669C16.2708 4.46491 13.4633 1.66669 10 1.66669C6.53681 1.66669 3.72931 4.46491 3.72931 7.91669C3.72924 8.80988 3.67731 9.64941 3.15471 10.4191C2.75574 10.9996 2.21722 11.5941 2.10831 12.308Z"
                stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path
                d="M6.66663 15.8333C7.0487 17.271 8.39624 18.3333 9.99996 18.3333C11.6037 18.3333 12.9512 17.271 13.3333 15.8333"
                stroke="#3A3C41" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <div class="title">
              Notifications
            </div>
          </div>

          <div class="row-container">

            @foreach($notifications as $notification)
            <div class="row">
              <div class="row__left">

                <div class="notification-details">
                  <div class="message">
                    {{$notification['message']}}
                  </div>
                  <div class="time">
                    {{$notification['time']}} ago
                  </div>
                </div>
              </div>
              <div class="row__right">

                <c-button varient="primary" size="sm"> Mark as Read</c-button>


              </div>
            </div>
            @endforeach
          </div>
        </div>
      </c-slot>

    </c-dropdown.main>

    <c-dropdown.main>
      <c-slot name="trigger" class="nav-user-trigger">
        <div class="nav-user" role="button" aria-haspopup="true">
          <div class="avatar">
            <img src="{{ asset('assets/avatar-placeholder.png') }}" alt="User avatar">
          </div>
          <div class="user-meta">
            <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Test Name'}}</div>
            <div class="user-role">{{ auth()->check() ? auth()->user()->role : 'Test role' }}</div>
          </div>
        </div>
      </c-slot>

      <c-slot name="menu">
        <c-dropdown.item>


          My Account

        </c-dropdown.item>
        <c-dropdown.sep />
        <c-dropdown.item>
          Settings
        </c-dropdown.item>
        <c-modal size="md" :initOpen="false">
          <c-slot name="trigger">
            <c-dropdown.item>

              <div class="item">
                Logout
              </div>
            </c-dropdown.item>

          </c-slot>
          <c-slot name="title">
            Confirm Logout
          </c-slot>
          <c-slot name="body">
            Are you sure you want to logout?
          </c-slot>
          <c-slot name="footer">
            <form action="{{ route('logout') }}" method="post">

              <c-button varient="destructive" type="submit">Logout</c-button>
            </form>
          </c-slot>
        </c-modal>


      </c-slot>

    </c-dropdown.main>



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