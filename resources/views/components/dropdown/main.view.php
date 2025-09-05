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

  const trigger = root.querySelector('.dropdown-trigger');
  const menu = root.querySelector('.dropdown-menu');
  if (!trigger || !menu) {
    return;
  }

  // store original parent so we can move back if needed (optional)
  const _origParent = menu.parentNode;
  const _origNext = menu.nextSibling;

  // accessibility boilerplate
  if (!trigger.hasAttribute('aria-controls')) {
    trigger.setAttribute('aria-controls', uid + '_menu');
  }

  if (!trigger.hasAttribute('id')) {
    trigger.id = uid + '_trigger';
  }

  trigger.setAttribute('aria-haspopup', 'menu');
  trigger.setAttribute('aria-expanded', 'false');
  menu.setAttribute('aria-hidden', 'true');

  // helpers
  const cssEscape = (s) => {
    if (window.CSS && CSS.escape) return CSS.escape(s);
    return String(s).replace(/([^\w-])/g, '\\$1');
  };

  // make sure menu hidden initially (avoid FOUS)
  menu.style.display = 'none';

  let opened = false;
  let onWindowChange = null;

  // measure and position menu relative to trigger using viewport coords (fixed)
  function positionMenu() {
    // ensure menu measurable: show it offscreen invisibly
    menu.style.visibility = 'hidden';
    menu.style.display = '';
    menu.style.position = 'fixed';
    menu.style.left = '-9999px';
    menu.style.top = '-9999px';
    menu.style.maxHeight = ''; // reset any previous max-height

    const trigRect = trigger.getBoundingClientRect();
    const mRect = menu.getBoundingClientRect();

    const gap = 8; // small gap between trigger and menu

    // decide vertical placement: prefer below, otherwise above
    let placeVert = 'bottom';
    let top = trigRect.bottom + gap;
    if (window.innerHeight - trigRect.bottom < mRect.height + gap) {
      // not enough space below -> place above if possible
      if (trigRect.top > mRect.height + gap) {
        placeVert = 'top';
        top = trigRect.top - mRect.height - gap;
      } else {
        // neither fits: clamp max height and keep below
        placeVert = 'bottom';
        // available below space
        const availBelow = Math.max(60, window.innerHeight - trigRect.bottom - gap);
        menu.style.maxHeight = availBelow + 'px';
        top = trigRect.bottom + gap;
      }
    }

    // horizontal placement: if data-placement="left" use left align to trig left, else right align to trig right
    const pref = menu.getAttribute('data-placement') || 'right';
    let left;
    if (pref === 'left') {
      left = trigRect.left;
    } else {
      left = trigRect.right - mRect.width;
    }

    // clamp to viewport with small margin
    const margin = 8;
    left = Math.min(Math.max(margin, left), Math.max(margin, window.innerWidth - mRect.width - margin));

    // finally apply
    menu.style.left = Math.round(left) + 'px';
    menu.style.top = Math.round(top) + 'px';
    menu.dataset.placementActual = placeVert;
    menu.style.visibility = '';
  }

  function openMenu() {
    if (opened) {
      return;
    }

    // append to body so menu does not affect table or any ancestor layout
    if (menu.parentNode !== document.body) {
      document.body.appendChild(menu);
    }
    menu.style.position = 'fixed';
    menu.style.zIndex = 9999;
    menu.style.display = ''; // make visible for measurement+interaction
    menu.setAttribute('aria-hidden', 'false');
    root.classList.add('open');
    trigger.setAttribute('aria-expanded', 'true');
    opened = true;

    // position first
    positionMenu();

    // focus first item
    const first = Array.from(menu.querySelectorAll('.dropdown-item'))
      .find(it => !it.disabled && it.offsetParent !== null);

    if (first) {
      first.focus();
    }

    // reposition on resize/scroll (throttled-ish)
    let raf = null;
    onWindowChange = () => {
      if (raf) cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        positionMenu();
        raf = null;
      });
    };
    window.addEventListener('resize', onWindowChange, { passive: true });
    // watch scroll from any scrollable ancestors and window
    window.addEventListener('scroll', onWindowChange, { passive: true });
  }

  function closeMenu(shouldRefocus = true) {
    if (!opened) return;
    menu.style.display = 'none';
    menu.setAttribute('aria-hidden', 'true');
    root.classList.remove('open');
    trigger.setAttribute('aria-expanded', 'false');
    opened = false;

    // cleanup listeners
    if (onWindowChange) {
      window.removeEventListener('resize', onWindowChange);
      window.removeEventListener('scroll', onWindowChange);
      onWindowChange = null;
    }

    // optionally refocus trigger
    if (shouldRefocus) {
      setTimeout(() => trigger.focus(), 0);
    }

    // (optional) move menu back to original position in DOM so server-side structure preserved
    // Putting it back avoids surprises if other scripts expect it there.
    try {
      if (_origParent && _origParent !== document.body) {
        if (_origNext && _origNext.parentNode === _origParent) {
          _origParent.insertBefore(menu, _origNext);
        } else {
          _origParent.appendChild(menu);
        }
        menu.style.position = ''; // reset
        menu.style.left = '';
        menu.style.top = '';
        menu.style.zIndex = '';
        menu.style.maxHeight = '';
      }
    } catch (err) {
      // ignore
    }
  }

  const toggleMenu = () => {
    if (menu.getAttribute('aria-hidden') === 'true') openMenu(); else closeMenu(true);
  };

  // trigger clicks
  trigger.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleMenu();
  });

  // close on outside click
  document.addEventListener('click', (ev) => {
    if (!opened) {
      return;
    }

    if (!menu.contains(ev.target) && !root.contains(ev.target)) {
      closeMenu(false);
    }
  });

  // keyboard navigation (works the same — menu may be moved but listeners are attached)
  menu.addEventListener('keydown', (e) => {
    const items = Array.from(menu.querySelectorAll('.dropdown-item')).filter(it => it.offsetParent !== null && !it.disabled);
    if (!items.length) {
      return;
    }
    
    const idx = items.indexOf(document.activeElement);

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      items[(idx + 1) % items.length].focus();
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      items[(idx - 1 + items.length) % items.length].focus();
    } else if (e.key === 'Home') {
      e.preventDefault();
      items[0].focus();
    } else if (e.key === 'End') {
      e.preventDefault();
      items[items.length - 1].focus();
    } else if (e.key === 'Escape') {
      e.preventDefault();
      closeMenu(true);
    }
  });

  // click on item: close if configured (delegation)
  menu.addEventListener('click', (e) => {
    const it = e.target.closest('.dropdown-item');
    if (!it) {
      return;
    }
    const closeOnSelect = {{ $closeOnSelect }};
    if (closeOnSelect) {
      closeMenu(true);
    }
  });

  // focus trap / close when focus leaves menu
  document.addEventListener('focusin', (ev) => {
    if (!opened) {
      return;
    }
    if (!menu.contains(ev.target) && !root.contains(ev.target)) {
      closeMenu(false);
    }
  });

  // init: ensure hidden
  menu.style.display = 'none';
})();
</script>