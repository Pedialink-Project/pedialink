<?php
// resources/views/components/dropdown.view.php
// Slots:
//  - @slot('trigger')  -> user provides the trigger HTML (3-dot icon, button, etc).
//  - @slot('menu')     -> optional: full custom menu markup. If absent, default slot content is used.

$uid = 'dropdown_' . bin2hex(random_bytes(6));
$placement = !empty($placement) && in_array($placement, ['left','right']) ? 
    $placement : 'right';
$classes = 'dropdown';
if (!empty($class)) {
    $classes .= ' ' . $class;
}
$closeOnSelect = isset($closeOnSelect) ? (bool)$closeOnSelect : true; // true by default
?>
<div id="{{ $uid }}" class="{{ $classes }}" data-dd-uid="{{ $uid }}">
  <div class="dropdown-trigger-wrapper">
    @if (!empty($slots['trigger']))
      {{ $slots['trigger'] }}
    @else
      <button type="button" class="dropdown-trigger icon-only" aria-haspopup="menu" aria-expanded="false" aria-controls="{{ $uid . '_menu' }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="5" cy="12" r="1.5" /><circle cx="12" cy="12" r="1.5" /><circle cx="19" cy="12" r="1.5" /></svg>
      </button>
    @endif
  </div>

  <div
    id="{{ $uid . '_menu' }}"
    class="dropdown-menu"
    role="menu"
    aria-hidden="true"
    data-placement="{{ $placement }}"
    aria-labelledby="{{ $uid . '_trigger' }}"
  >
    @if (!empty($slots['menu']))
      {{ $slots['menu'] }}
    @endif
    {{ $slot }}
  </div>
</div>

<script>
(() => {
  const uid = `{{ $uid }}`;
  const root = document.getElementById(uid);
  if (!root) {
    return;
  }

  // allow user to put trigger button anywhere in the trigger slot, find first .dropdown-trigger
  const trigger = root.querySelector('.dropdown-trigger');
  const menu = root.querySelector('.dropdown-menu');
  if (!trigger || !menu) {
    return;
  }

  // ensure trigger has accessible attributes
  if (!trigger.hasAttribute('aria-controls')) {
    trigger.setAttribute('aria-controls', uid + '_menu');
  }
  
  if (!trigger.hasAttribute('id')) {
    trigger.id = uid + '_trigger';
  }

  trigger.setAttribute('aria-haspopup', 'menu');
  trigger.setAttribute('aria-expanded', 'false');
  menu.setAttribute('aria-hidden', 'true');

  const itemsSelector = '.dropdown-item';
  const items = () => Array.from(menu.querySelectorAll(itemsSelector));

  const openMenu = () => {
    menu.style.display = '';
    menu.setAttribute('aria-hidden', 'false');
    root.classList.add('open');
    trigger.setAttribute('aria-expanded', 'true');
    // focus first interactive item if present
    const first = items().find(i => !i.disabled && i.offsetParent !== null);
    if (first) {
      first.focus();
    }
  };

  const closeMenu = (shouldRefocus = true) => {
    menu.style.display = 'none';
    menu.setAttribute('aria-hidden', 'true');
    root.classList.remove('open');
    trigger.setAttribute('aria-expanded', 'false');
    if (shouldRefocus) setTimeout(() => trigger.focus(), 0);
  };

  const toggleMenu = () => {
    const hidden = menu.getAttribute('aria-hidden') === 'true';
    if (hidden) openMenu(); else closeMenu(true);
  };

  // click toggle
  trigger.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleMenu();
  });

  // item click delegation
  menu.addEventListener('click', (e) => {
    const it = e.target.closest(itemsSelector);
    if (!it) {
      return;
    }
    // Let default behaviour happen (link navigation or form buttons).
    // If closeOnSelect is true, close menu.

    const closeOnSelect = {{ $closeOnSelect }};
    if (closeOnSelect) {
      closeMenu(true);
    }
  });

  // keyboard nav
  menu.addEventListener('keydown', (e) => {
    const list = items().filter(it => it.offsetParent !== null && !it.disabled);
    if (!list.length) return;
    const idx = list.indexOf(document.activeElement);
    if (e.key === 'ArrowDown') {
      e.preventDefault(); list[(idx + 1) % list.length].focus();
    }
    else if (e.key === 'ArrowUp') {
      e.preventDefault(); list[(idx - 1 + list.length) % list.length].focus();
    }
    else if (e.key === 'Home') {
      e.preventDefault(); list[0].focus();
    }
    else if (e.key === 'End') {
      e.preventDefault(); list[list.length - 1].focus();
    }
    else if (e.key === 'Escape') {
      e.preventDefault(); closeMenu(true);
    }
  });

  // Trap focus inside menu when open (basic)
  document.addEventListener('focusin', (ev) => {
    if (menu.getAttribute('aria-hidden') === 'true') {
      return;
    }
    
    if (!root.contains(ev.target)) {
      // focus moved outside -> close without refocus so user can focus next element
      closeMenu(false);
    }
  });

  // close on outside click (do not refocus)
  document.addEventListener('click', (ev) => {
    if (!root.contains(ev.target)) {
      closeMenu(false);
    }
  });

  // init: hide menu explicitly to avoid FOUS (flash of unstyled)
  menu.style.display = 'none';
})();
</script>