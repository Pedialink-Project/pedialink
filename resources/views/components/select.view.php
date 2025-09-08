<?php

/** 
 * Select Component:
 * 
 * Component for drop down box. Please read this before using the component!
 * 
 * Features:
 *  1. Single select
 *  2. Multi Select 
 *  3. Built-in Search bar (optional)
 *  4. Options parsing:
 *    a. Attribute options parsing (not working currently)
 *    b. Component option parsing
 *  5. Default select values
 *  6. Label (optional)
 * 
*/

$uid = 'select_' . bin2hex(random_bytes(6)); // unique id per component

// Normalize options into array of ['value'=>'', 'label'=>'']
$optionsList = [];
if (!empty($options) && is_array($options)) {
  foreach ($options as $k => $v) {
    if (is_array($v)) {
      $optionsList[] = [
        'value' => (string)($v['value'] ?? $v[0] ?? $k),
        'label' => (string)($v['label'] ?? $v[1] ?? $v)
      ];
    } else {
      if (is_int($k)) {
        $optionsList[] = ['value' => (string)$v, 'label' => (string)$v];
      } else {
        $optionsList[] = ['value' => (string)$k, 'label' => (string)$v];
      }
    }
  }
} elseif (!empty($options) && is_string($options)) {
  $decoded = json_decode($options, true);
  if (is_array($decoded)) {
    foreach ($decoded as $k => $v) {
      if (is_array($v)) {
        $optionsList[] = [
          'value' => (string)($v['value'] ?? $v[0] ?? $k),
          'label' => (string)($v['label'] ?? $v[1] ?? $v)
        ];
      } else {
        if (is_int($k)) {
          $optionsList[] = ['value' => (string)$v, 'label' => (string)$v];
        } else {
          $optionsList[] = ['value' => (string)$k, 'label' => (string)$v];
        }
      }
    }
  }
}

// Normalize initial selected values
$selectedValues = [];
if (!empty($value) && $value !== null) {
  if (!empty($multiple) && $multiple && is_array($value)) {
    $selectedValues = array_map('strval', $value);
  } elseif (!empty($multiple) && $multiple && is_string($value)) {
    $selectedValues = array_filter(
      array_map('trim', explode(',', $value))
    );
  } else {
    $selectedValues = [ (string)$value ];
  }
}

// convenience map: value => label from options for initial display
$valueLabelMap = [];
foreach ($optionsList as $opt) {
  $valueLabelMap[$opt['value']] = $opt['label'];
}

// classes
$classes = 'select';

if (!empty($class)) {
  $classes .= " {$class}";
}

if (!empty($size)) {
  $classes .= ' select--' . $size;
}

if (!empty($error) && trim($error) !== '') {
  $classes .= ' select--error';
}

?>

<div class="select-wrapper">
  @if (!empty($label))
    <label
      id="{{ $uid . '_label' }}"
      class="select-field-label"
      for="{{ $uid . '_trigger' }}"
    >
      {{ $label }}
    </label>
  @endif

  <div 
    id="{{ $uid }}" 
    class="{{ $classes }}"
    @if (!empty($disabled))
      aria-disabled="true"
    @endif
  >
    <input
      type="hidden"

      @if (!empty($name))
        name="{{ $name }}"
      @endif

      @if (!empty($value))
        value="{{ $value }}"
      @endif
    />

    <button
      id="{{ $uid . '_trigger'}}"
      type="button"
      class="select-trigger"
      aria-haspopup="listbox"
      aria-expanded="false"
      aria-controls="{{ $uid . '_list'}}"
      @if (!empty($error) && trim($error) !== '')
        aria-invalid="true"
        aria-describedby="{{ $uid . '_error'}}"
      @endif
      @if (!empty($disabled))
        disabled
      @endif
    >
      <span class="select-label">
        @if (count($selectedValues) === 0)
          <span class="select-placeholder">{{ !empty($placeholder) ? $placeholder : 'Select..' }}</span>
        @elseif (!empty($multiple) && $multiple)
          <span class="select-pills">
            @foreach ($selectedValues as $sv)
              <span class="select-pill">{{ $valueLabelMap[$sv] ?? $sv }}</span>
            @endforeach
          </span>
        @else
          {{ $valueLabelMap[$selectedValues[0]] ?? $selectedValues[0] }}
        @endif
      </span>

      <span class="select-chevron" aria-hidden="true">
        <!-- simple chevron -->
        <svg width="25px" height="25px" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </span>
    </button>

    <div class="select-panel" style="display:none;" role="dialog" aria-modal="false" aria-hidden="true">
      @if (!empty($searchable))
        <input type="search" class="select-search" placeholder="Search..." aria-label="Search options" />
      @endif

      <ul 
        id="{{ $uid . '_list' }}"
        class="select-list"
        role="listbox"
        tabindex="-1"
        aria-multiselectable="{{ !empty($multiple) && $multiple ? 'true' : 'false' }}"
      >
        @if (!empty($optionsList))
          @foreach($optionsList as $opt)
            <li
              class="select-item"
              role="option"
              data-value="{{ $opt['value'] }}"
              aria-selected="{{ in_array($opt['value'], $selectedValues) ? 'true' : 'false' }}">
              {{ $opt['label'] }}
            </li>
          @endforeach
        @else 
          {{ $slot }}
        @endif
      </ul>

      <div class="select-empty" style="display:none;">No options</div>
    </div>

    @if (!empty($error) && trim($error) !== '')
      <div id="{{ $uid . '_error' }}" class="select-error-text">
        {{ $error }}
      </div>
    @endif
  </div>

</div>

<script>
(() => {
  const uid = `{{ $uid }}`;
  const root = document.getElementById(uid);
  if (!root) {
    return;
  }

  const trigger = root.querySelector('.select-trigger');
  const panel = root.querySelector('.select-panel');
  const list = root.querySelector('.select-list');
  const search = root.querySelector('.select-search');
  const hiddenInput = root.querySelector('input[type="hidden"]');
  const initialSelected = <?php echo json_encode(array_values($selectedValues)) ?> || [];
  const selected = new Set(initialSelected);
  const multiple = {{ (!empty($multiple) && $multiple) ? 'true' : 'false'; }};
  const disabled = {{ (!empty($disabled) && $disabled) ? 'true' : 'false'; }};

  // CSS.escape fallback
  const cssEscape = (s) => {
    if (window.CSS && CSS.escape) return CSS.escape(s);
    return String(s).replace(/([^\w-])/g, '\\$1');
  };

  const updateTriggerLabel = () => {
    const labelWrap = root.querySelector('.select-label');
    if (!labelWrap) {
      return;
    }

    if (selected.size === 0) {
      labelWrap.innerHTML = `<span class="select-placeholder">` + `{{ (!empty($placeholder)) ? $placeholder : 'Select..'; }}` + `</span>`;
    } else if (multiple) {
      const pills = document.createElement('span');
      pills.className = 'select-pills';
      for (const v of selected) {
        const li = list.querySelector(`.select-item[data-value="${cssEscape(v)}"]`);
        const text = li ? li.textContent.trim() : String(v);
        const pill = document.createElement('span');
        pill.className = 'select-pill';
        pill.textContent = text;
        pills.appendChild(pill);
      }
      labelWrap.innerHTML = '';
      labelWrap.appendChild(pills);
    } else {
      const v = Array.from(selected)[0];
      const li = list.querySelector(`.select-item[data-value="${cssEscape(v)}"]`);
      labelWrap.textContent = li ? li.textContent.trim() : String(v);
    }

    if (hiddenInput) {
      hiddenInput.value = Array.from(selected).join(',');
    }
  };

  const openPanel = () => {
    if (disabled) {
      return;
    }
    panel.style.display = '';
    panel.setAttribute('aria-hidden', 'false');
    root.classList.add('open');
    trigger.setAttribute('aria-expanded', 'true');
    if (search) {
      search.focus();
    } else {
      list.focus();
    }
    checkEmpty();
  };

  const closePanel = (shouldRefocus = true) => {
    panel.style.display = 'none';
    panel.setAttribute('aria-hidden', 'true');
    root.classList.remove('open');
    trigger.setAttribute('aria-expanded', 'false');

    if (shouldRefocus) {
      // small timeout to avoid racing with other focus events
      setTimeout(() => trigger.focus(), 0);
    }
  };

  const togglePanel = () => {
    const isHidden = panel.style.display === 'none' || panel.style.display === '';
    if (isHidden) {
      openPanel();
    } else {
      closePanel(true);
    }
  };

  const filterItems = (q) => {
    const items = list.querySelectorAll('.select-item');
    let visible = 0;
    items.forEach((it) => {
      const txt = it.textContent.trim().toLowerCase();
      if (!q || txt.indexOf(q.toLowerCase()) !== -1) {
        it.style.display = '';
        visible++;
      }
      else {
        it.style.display = 'none';
      }
    });
    checkEmpty(visible);
  };

  const checkEmpty = (visibleCount) => {
    const empty = root.querySelector('.select-empty');
    if (!empty) {
      return;
    }
    if (typeof visibleCount === 'undefined') {
      visibleCount = list.querySelectorAll('.select-item:not([style*="display: none"])').length;
    }
    empty.style.display = visibleCount === 0 ? '' : 'none';
  };

  const onItemActivate = (item) => {
    const value = item.getAttribute('data-value');
    const isSelected = item.getAttribute('aria-selected') === 'true';
    if (multiple) {
      if (selected.has(value)) {
        selected.delete(value); item.setAttribute('aria-selected', 'false');
      } else {
        selected.add(value); item.setAttribute('aria-selected', 'true');
      }
      updateTriggerLabel();
    } else {
      selected.clear(); selected.add(value);
      list.querySelectorAll('.select-item').forEach((it) => it.setAttribute('aria-selected', it === item ? 'true' : 'false'));
      updateTriggerLabel();
      closePanel(true);
    }
  };

  // Event bindings
  trigger.addEventListener('click', (e) => { e.stopPropagation(); togglePanel(); });

  list.addEventListener('click', (e) => {
    const it = e.target.closest('.select-item');
    if (!it) {
      return;
    }
    onItemActivate(it);
  });

  // keyboard navigation inside list
  list.addEventListener('keydown', (e) => {
    const focusable = Array.from(list.querySelectorAll('.select-item')).filter((it) => it.style.display !== 'none');
    if (!focusable.length) {
      return;
    }
    const idx = focusable.indexOf(document.activeElement);
    if (e.key === 'ArrowDown') {
      e.preventDefault(); const next = focusable[(idx + 1) % focusable.length]; next.focus();
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      const prev = focusable[(idx - 1 + focusable.length) % focusable.length];
      prev.focus();
    } else if (e.key === 'Enter') {
      e.preventDefault();
      
      if (document.activeElement.classList.contains('select-item')) {
        onItemActivate(document.activeElement);
      } 
    } else if (e.key === 'Escape') {
      e.preventDefault(); closePanel(true);
    }
  });

  // make items focusable
  list.querySelectorAll('.select-item').forEach((it) => { it.tabIndex = 0; });

  // search handlers
  if (search) {
    search.addEventListener('input', (ev) => { filterItems(ev.target.value); });
    search.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        const first = list.querySelector('.select-item:not([style*="display: none"])');
        if (first) {
          first.focus();
        }
      }
      if (e.key === 'Escape') {
        closePanel(true);
      }
    });
  }

  // close on outside click
  document.addEventListener('click', (ev) => { if (!root.contains(ev.target)) closePanel(false); });

  // initialize selected state visually
  (function initSelected() {
    for (const v of initialSelected) {
      const li = list.querySelector(`.select-item[data-value="${cssEscape(v)}"]`);
      if (li) {
        li.setAttribute('aria-selected', 'true');
      }
    }
    updateTriggerLabel();
  })();
})();
</script>