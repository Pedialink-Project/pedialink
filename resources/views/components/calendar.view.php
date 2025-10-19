<?php
/**
 * calendar.view.php
 *
 * Attributes:
 *  - $month (1-12) optional; default = current month
 *  - $year optional; default = current year
 *  - $events optional; array or JSON string. each event object:
 *       ['date' => 'YYYY-MM-DD', 'title' => 'Vaccine A', 'items' => [ ... ], 'color' => 'success' , 'meta'=>...]
 *     Multiple events can share the same date.
 *  - $modalId optional; id of an existing modal component to open on date click. If not provided the component will dispatch an event.
 *  - $startWeekOn optional: 0=Sunday (default) or 1=Monday
 *  - $locale optional: e.g. 'en-US' or 'si-LK' (default: browser)
 *
 * Example events for testing:
 * $events = [
 *   ['date'=>'2025-10-19','title'=>'MMR Dose 1','items'=>[['child'=>'Kumara','time'=>'09:00','vaccine'=>'MMR']]],
 *   ['date'=>'2025-10-20','title'=>'DTaP','items'=>[['child'=>'Nimal','time'=>'10:30']]],
 * ];
 */
$now = new DateTimeImmutable('now', new DateTimeZone('Asia/Colombo'));
$month = isset($month) ? (int)$month : (int)$now->format('n');
$year = isset($year) ? (int)$year : (int)$now->format('Y');
$startWeekOn = isset($startWeekOn) ? (int)$startWeekOn : 0;
$locale = isset($locale) ? $locale : 'en-US';
$modalId = isset($modalId) ? $modalId : null;

// normalize events into array
$eventsArr = [];
if (!empty($events)) {
  if (is_string($events)) {
    $decoded = json_decode($events, true);
    if (is_array($decoded)) $eventsArr = $decoded;
  } elseif (is_array($events)) {
    $eventsArr = $events;
  }
}

// prepare a map date -> array of events
$eventsMap = [];
foreach ($eventsArr as $e) {
  if (empty($e['date'])) continue;
  $d = (string)$e['date'];
  if (!isset($eventsMap[$d])) $eventsMap[$d] = [];
  $eventsMap[$d][] = $e;
}

$jsonMap = htmlspecialchars(json_encode($eventsMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
?>

<link rel="stylesheet" href="{{ asset('css/components/calendar.css') }}" />

<div class="calendar" id="calendar_{{ $month }}_{{ $year }}" data-calendar-month="{{ $month }}" data-calendar-year="{{ $year }}" data-calendar-map='<?= $jsonMap ?>' data-calendar-start-week="{{ $startWeekOn }}" data-calendar-locale="{{ $locale }}" data-calendar-modal="{{ $modalId ?? '' }}">
  <div class="calendar-header">
    <div class="calendar-title" id="calendarTitle_{{ $month }}_{{ $year }}"></div>
    <div class="calendar-controls">
      <button type="button" class="icon-btn calendar-prev" aria-label="Previous month">&larr;</button>
      <button type="button" class="icon-btn calendar-today" aria-label="Today">Today</button>
      <button type="button" class="icon-btn calendar-next" aria-label="Next month">&rarr;</button>
    </div>
  </div>

  <div class="calendar-weekdays" aria-hidden="true" id="calendarWeekdays_{{ $month }}_{{ $year }}"></div>
  <div class="calendar-grid" role="grid" id="calendarGrid_{{ $month }}_{{ $year }}" tabindex="0"></div>
</div>

<script>
(() => {
  // modern, modular calendar. No external libs.
  const root = document.getElementById('calendar_<?= $month ?>_<?= $year ?>');
  if (!root) return;

  const monthInit = parseInt(root.dataset.calendarMonth, 10);
  const yearInit = parseInt(root.dataset.calendarYear, 10);
  const startWeekOn = parseInt(root.dataset.calendarStartWeek || '0', 10);
  const locale = root.dataset.calendarLocale || navigator.language || 'en-US';
  const modalId = root.dataset.calendarModal || '';
  const eventsMap = (() => {
    try { return JSON.parse(root.dataset.calendarMap || '{}'); } catch(e) { return {}; }
  })();

  // utilities
  const pad = (n) => n.toString().padStart(2, '0');
  const toISO = (y, m, d) => `${y}-${pad(m)}-${pad(d)}`;

  // nodes
  const titleNode = root.querySelector(`#calendarTitle_${monthInit}_${yearInit}`);
  const weekdaysNode = root.querySelector(`#calendarWeekdays_${monthInit}_${yearInit}`);
  const grid = root.querySelector(`#calendarGrid_${monthInit}_${yearInit}`);

  // internal state
  let viewYear = yearInit;
  let viewMonth = monthInit; // 1-12

  // build weekday labels based on startWeekOn
  function renderWeekdays() {
    const base = new Date(2020, 0, 5); // Sunday
    const labels = [];
    for (let i = 0; i < 7; i++) {
      const idx = (startWeekOn + i) % 7;
      const d = new Date(base);
      d.setDate(base.getDate() + idx);
      labels.push(d.toLocaleDateString(locale, { weekday: 'short' }));
    }
    weekdaysNode.innerHTML = '';
    labels.forEach(l => {
      const el = document.createElement('div');
      el.className = 'calendar-weekday';
      el.textContent = l;
      weekdaysNode.appendChild(el);
    });
  }

  // compute first day index and number of days in month
  function daysInMonth(y, m) {
    return new Date(y, m, 0).getDate(); // m is 1-12, month param as next month zero day trick: new Date(y, m, 0)
  }

  function firstDayOfMonthIndex(y, m) {
    // returns 0-6 (0=Sunday)
    return new Date(y, m - 1, 1).getDay();
  }

  // render header
  function renderTitle() {
    const d = new Date(viewYear, viewMonth - 1, 1);
    titleNode.textContent = d.toLocaleDateString(locale, { month: 'long', year: 'numeric' });
  }

  // flatten eventsMap keys to a Set for quick lookup
  function eventsForDate(isoDate) {
    return Array.isArray(eventsMap[isoDate]) ? eventsMap[isoDate] : [];
  }

  function renderGrid() {
    grid.innerHTML = '';
    grid.setAttribute('role','grid');

    const daysInView = daysInMonth(viewYear, viewMonth);
    const firstIndex = firstDayOfMonthIndex(viewYear, viewMonth);

    // compute offset based on startWeekOn
    // If startWeekOn=1 (Monday), shift index: monday=0 => convert sunday-based index
    const offset = (firstIndex - startWeekOn + 7) % 7;

    // previous month filler
    const prevMonth = viewMonth === 1 ? 12 : viewMonth - 1;
    const prevYear = viewMonth === 1 ? viewYear - 1 : viewYear;
    const prevDays = daysInMonth(prevYear, prevMonth);

    // total cells to render (6 rows of 7 = 42 to keep consistent)
    const totalCells = 42;

    for (let cell = 0; cell < totalCells; cell++) {
      const el = document.createElement('div');
      el.className = 'calendar-cell';
      el.setAttribute('role','gridcell');
      el.tabIndex = 0;

      // compute date for this cell
      const dayIndex = cell - offset; // 0-based index into current month
      let cellYear = viewYear;
      let cellMonth = viewMonth;
      let cellDay = 1;

      if (dayIndex < 0) {
        // previous month
        cellMonth = prevMonth;
        cellYear = prevYear;
        cellDay = prevDays + dayIndex + 1;
        el.classList.add('calendar-cell--muted');
      } else if (dayIndex >= daysInView) {
        // next month
        const nextMonth = viewMonth === 12 ? 1 : viewMonth + 1;
        const nextYear = viewMonth === 12 ? viewYear + 1 : viewYear;
        cellMonth = nextMonth;
        cellYear = nextYear;
        cellDay = dayIndex - daysInView + 1;
        el.classList.add('calendar-cell--muted');
      } else {
        cellDay = dayIndex + 1;
      }

      const iso = toISO(cellYear, cellMonth, cellDay);
      el.dataset.date = iso;

      // head with date number
      const head = document.createElement('div');
      head.className = 'calendar-cell-head';
      const num = document.createElement('div');
      num.className = 'calendar-day-num';
      num.textContent = cellDay;
      head.appendChild(num);

      // markers container
      const markers = document.createElement('div');
      markers.className = 'calendar-markers';

      const evs = eventsForDate(iso);

      if (evs.length === 0) {
        el.classList.add('calendar-cell--empty');
      } else {
        // show up to two label markers; if more, show dots
        const maxShow = 2;
        const toShow = evs.slice(0, maxShow);
        toShow.forEach((e) => {
          const m = document.createElement('span');
          // choose marker style depending on e.color or default
          m.className = 'cal-marker';
          if (e.color) {
            m.style.background = e.color;
            m.style.color = '#fff';
          }
          m.textContent = e.title || (e.items && e.items.length ? `${e.items.length} item${e.items.length>1?'s':''}` : '•');
          markers.appendChild(m);
        });
        if (evs.length > maxShow) {
          // small dot indicator for overflow
          const more = document.createElement('span');
          more.className = 'cal-dot';
          more.style.background = 'var(--cal-accent)';
          markers.appendChild(more);
        }
      }

      el.appendChild(head);
      el.appendChild(markers);

      // highlight today
      const today = new Date();
      const todayIso = toISO(today.getFullYear(), today.getMonth() + 1, today.getDate());
      if (iso === todayIso) el.classList.add('calendar-cell--today');

      // click handler: open modal or dispatch event
      el.addEventListener('click', (ev) => {
        ev.stopPropagation();
        onDateClicked(iso);
      });

      // keyboard: Enter/Space to activate
      el.addEventListener('keydown', (ev) => {
        if (ev.key === 'Enter' || ev.key === ' ') {
          ev.preventDefault();
          onDateClicked(el.dataset.date);
        }
      });

      grid.appendChild(el);
    }

    renderTitle();
  }

  // Open modal (if modalId provided) or dispatch event
  function onDateClicked(isoDate) {
    const events = eventsForDate(isoDate);
    const detail = { date: isoDate, events };

    // If modalId provided and ModalControls exists, attempt to populate modal and open
    if (modalId && window.ModalControls && typeof window.ModalControls[modalId] !== 'undefined') {
      // find modal source container (where .modal exists before portal)
      const modalWrapper = document.querySelector(`[data-modal-id="${modalId}"]`);
      let modalInner = null;
      if (modalWrapper) {
        modalInner = modalWrapper.querySelector('.modal') || modalWrapper.querySelector('.modal-src .modal') || modalWrapper.querySelector('.modal-src-hidden .modal');
      }

      // fallback: attempt to find any element with id = modalId (if user used id attr)
      if (!modalInner) modalInner = document.getElementById(modalId);

      // If we found modalInner, write content into its modal-body (preserve header/footer)
      if (modalInner) {
        const body = modalInner.querySelector('.modal-body');
        if (body) {
          // build simple markup (you can customize)
          let html = `<div class="calendar-modal-date"><h3>${isoDate}</h3>`;
          if (events.length === 0) {
            html += `<p>No items scheduled for this date.</p>`;
          } else {
            html += `<ul class="calendar-modal-list">`;
            events.forEach(ev => {
              html += `<li class="calendar-modal-item"><strong>${escapeHtml(ev.title || '')}</strong>`;
              if (Array.isArray(ev.items) && ev.items.length) {
                html += '<ul>';
                ev.items.forEach(it => {
                  html += `<li>${escapeHtml(it.child || it.name || '')} ${it.time ? (' — ' + escapeHtml(it.time)) : ''} ${it.vaccine ? (' — ' + escapeHtml(it.vaccine)) : ''}</li>`;
                });
                html += '</ul>';
              } else if (ev.description) {
                html += `<div>${escapeHtml(ev.description)}</div>`;
              }
              html += `</li>`;
            });
            html += `</ul>`;
          }
          html += `</div>`;
          body.innerHTML = html;
        }
      }

      // open modal programmatically
      try {
        window.ModalControls[modalId].open();
      } catch (err) {
        // fallback dispatch if can't open
        document.dispatchEvent(new CustomEvent('calendar:date-click', { detail }));
      }
    } else {
      // no modalId: emit event for consumer to handle
      document.dispatchEvent(new CustomEvent('calendar:date-click', { detail }));
    }
  }

  // helper to escape text
  function escapeHtml(s) {
    if (s === null || s === undefined) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  // controls
  function gotoPrev() {
    viewMonth -= 1;
    if (viewMonth < 1) { viewMonth = 12; viewYear -= 1; }
    renderGrid();
  }
  function gotoNext() {
    viewMonth += 1;
    if (viewMonth > 12) { viewMonth = 1; viewYear += 1; }
    renderGrid();
  }
  function gotoToday() {
    const t = new Date();
    viewMonth = t.getMonth() + 1;
    viewYear = t.getFullYear();
    renderGrid();
  }

  // wire buttons
  root.querySelector('.calendar-prev').addEventListener('click', gotoPrev);
  root.querySelector('.calendar-next').addEventListener('click', gotoNext);
  root.querySelector('.calendar-today').addEventListener('click', gotoToday);

  // initial render
  renderWeekdays();
  renderGrid();

  // expose small API if desired
  window.CalendarControls = window.CalendarControls || {};
  window.CalendarControls[`cal_${Math.random().toString(36).slice(2,8)}`] = {
    goto(month, year) {
      viewMonth = month;
      viewYear = year;
      renderGrid();
    },
    refresh(newMap) {
      // accept object map { 'YYYY-MM-DD': [ ... ] }
      Object.assign(eventsMap, newMap || {});
      renderGrid();
    }
  };
})();
</script>
