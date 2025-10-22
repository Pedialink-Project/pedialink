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
                stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path
                d="M6.66663 15.8333C7.0487 17.271 8.39624 18.3333 9.99996 18.3333C11.6037 18.3333 12.9512 17.271 13.3333 15.8333"
                stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <div class="title">
              Notifications
            </div>
          </div>

          <div class="row-container">

            @foreach($notifications as $notification)
            <div class="row" >
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

                <c-button variant="primary" size="sm"> Mark as Read</c-button>


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

          <a class="drop-item " href="{{ auth()->check() ? route(auth()->user()->role .'.dashboard') : '#' }}">
            <svg width="17" height="17" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M5.48131 12.9013C4.30234 13.6033 1.21114 15.0368 3.09388 16.8305C4.01359 17.7067 5.03791 18.3333 6.32572 18.3333H13.6743C14.9621 18.3333 15.9864 17.7067 16.9061 16.8305C18.7889 15.0368 15.6977 13.6033 14.5187 12.9013C11.754 11.2551 8.24599 11.2551 5.48131 12.9013Z"
                stroke="#18181B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path
                d="M13.75 5.41667C13.75 7.48774 12.0711 9.16667 10 9.16667C7.92893 9.16667 6.25 7.48774 6.25 5.41667C6.25 3.3456 7.92893 1.66667 10 1.66667C12.0711 1.66667 13.75 3.3456 13.75 5.41667Z"
                stroke="#18181B" stroke-width="1.5" />
            </svg>

            <span>My Account</span>
          </a>



        </c-dropdown.item>
        <c-dropdown.sep />
        <c-dropdown.item>
          <a class="drop-item " href="{{ auth()->check() ? route(auth()->user()->role .'.settings') : '#' }}">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_474_15232)">
                <path
                  d="M13.5902 3.65331C13.0977 3.65331 12.8514 3.65331 12.6271 3.57006C12.5959 3.5585 12.5652 3.54578 12.535 3.53192C12.3175 3.43214 12.1433 3.25801 11.7951 2.90974C10.9935 2.10816 10.5927 1.70737 10.0996 1.67041C10.0333 1.66544 9.96667 1.66544 9.90036 1.67041C9.40721 1.70737 9.00642 2.10816 8.20484 2.90974C7.85657 3.25801 7.68244 3.43214 7.46493 3.53192C7.43473 3.54578 7.40401 3.5585 7.37285 3.57006C7.1485 3.65331 6.90224 3.65331 6.40971 3.65331H6.31886C5.06228 3.65331 4.43399 3.65331 4.04362 4.04368C3.65324 4.43405 3.65324 5.06234 3.65324 6.31893V6.40978C3.65324 6.9023 3.65324 7.14856 3.57 7.37291C3.55844 7.40407 3.54572 7.43479 3.53186 7.46499C3.43208 7.6825 3.25795 7.85663 2.90968 8.2049C2.1081 9.00648 1.70731 9.40727 1.67035 9.90042C1.66538 9.96673 1.66538 10.0333 1.67035 10.0996C1.70731 10.5928 2.1081 10.9936 2.90968 11.7951C3.25795 12.1434 3.43208 12.3175 3.53186 12.535C3.54572 12.5653 3.55844 12.596 3.57 12.6271C3.65324 12.8515 3.65324 13.0977 3.65324 13.5903V13.6811C3.65324 14.9377 3.65324 15.566 4.04362 15.9564C4.43399 16.3467 5.06228 16.3467 6.31886 16.3467H6.40971C6.90224 16.3467 7.1485 16.3467 7.37285 16.43C7.40401 16.4415 7.43473 16.4543 7.46493 16.4681C7.68244 16.5679 7.85657 16.742 8.20484 17.0903C9.00642 17.8919 9.40721 18.2927 9.90036 18.3296C9.96667 18.3346 10.0333 18.3346 10.0996 18.3296C10.5927 18.2927 10.9935 17.8919 11.7951 17.0903C12.1433 16.742 12.3175 16.5679 12.535 16.4681C12.5652 16.4543 12.5959 16.4415 12.6271 16.43C12.8514 16.3467 13.0977 16.3467 13.5902 16.3467H13.6811C14.9376 16.3467 15.5659 16.3467 15.9563 15.9564C16.3467 15.566 16.3467 14.9377 16.3467 13.6811V13.5903C16.3467 13.0977 16.3467 12.8515 16.4299 12.6271C16.4415 12.596 16.4542 12.5653 16.4681 12.535C16.5678 12.3175 16.742 12.1434 17.0902 11.7951C17.8918 10.9936 18.2926 10.5928 18.3296 10.0996C18.3345 10.0333 18.3345 9.96673 18.3296 9.90042C18.2926 9.40727 17.8918 9.00648 17.0902 8.2049C16.742 7.85663 16.5678 7.6825 16.4681 7.465C16.4542 7.43479 16.4415 7.40407 16.4299 7.37291C16.3467 7.14856 16.3467 6.9023 16.3467 6.40978V6.31893C16.3467 5.06234 16.3467 4.43405 15.9563 4.04368C15.5659 3.65331 14.9376 3.65331 13.6811 3.65331H13.5902Z"
                  stroke="#18181B" stroke-width="1.5" />
                <path
                  d="M12.9167 9.99998C12.9167 11.6108 11.6109 12.9166 10 12.9166C8.38921 12.9166 7.08337 11.6108 7.08337 9.99998C7.08337 8.38915 8.38921 7.08331 10 7.08331C11.6109 7.08331 12.9167 8.38915 12.9167 9.99998Z"
                  stroke="#18181B" stroke-width="1.5" />
              </g>
              <defs>
                <clipPath id="clip0_474_15232">
                  <rect width="20" height="20" fill="white" />
                </clipPath>
              </defs>
            </svg>

            <span>Settings</span>
</a>
  </c-dropdown.item>
  <c-modal size="md" :initOpen="false">
    <c-slot name="trigger">
      <c-dropdown.item>

        <div class="drop-item logout">

          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M15 17.625C14.9264 19.4769 13.3831 21.0494 11.3156 20.9988C10.8346 20.987 10.2401 20.8194 9.05112 20.484C6.18961 19.6768 3.70555 18.3203 3.10956 15.2815C3 14.723 3 14.0944 3 12.8373L3 11.1627C3 9.90561 3 9.27705 3.10956 8.71846C3.70555 5.67965 6.18961 4.32316 9.05112 3.51603C10.2401 3.18064 10.8346 3.01295 11.3156 3.00119C13.3831 2.95061 14.9264 4.52307 15 6.37501"
              stroke="#DC2626" stroke-width="1.5" stroke-linecap="round" />
            <path d="M21 12H10M21 12C21 11.2998 19.0057 9.99153 18.5 9.5M21 12C21 12.7002 19.0057 14.0085 18.5 14.5"
              stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span>Logout</span>

        </div>
      </c-dropdown.item>

    </c-slot>
    <c-slot name="headerPrefix">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M15 17.625C14.9264 19.4769 13.3831 21.0494 11.3156 20.9988C10.8346 20.987 10.2401 20.8194 9.05112 20.484C6.18961 19.6768 3.70555 18.3203 3.10956 15.2815C3 14.723 3 14.0944 3 12.8373L3 11.1627C3 9.90561 3 9.27705 3.10956 8.71846C3.70555 5.67965 6.18961 4.32316 9.05112 3.51603C10.2401 3.18064 10.8346 3.01295 11.3156 3.00119C13.3831 2.95061 14.9264 4.52307 15 6.37501"
          stroke="#DC2626" stroke-width="1.5" stroke-linecap="round" />
        <path d="M21 12H10M21 12C21 11.2998 19.0057 9.99153 18.5 9.5M21 12C21 12.7002 19.0057 14.0085 18.5 14.5"
          stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>

    </c-slot>

    <c-slot name="header">
      <span class="logout">Conform Logout</span>
    </c-slot>

    <span class="logout-msg"> Are you sure you want to logout?
    </span>

    <c-slot name="close">
      Cancel
    </c-slot>

    <c-slot name="footer">
      <form action="{{ auth()->check() ? route('logout') : '#' }}" method="post">
        <c-button variant="destructive" type="submit">Logout</c-button>
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