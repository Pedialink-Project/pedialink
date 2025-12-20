<?php
// checkbox.view.php
    $id = isset($id) && is_string($id) ? $id : ('chk_' . bin2hex(random_bytes(5)));
    $name = isset($name) ? $name : null;
    $value = isset($value) ? $value : '1';
    $checked = !empty($checked) ? $checked : false;
    $disabled = !empty($disabled) ? $disabled : false;
    $class = isset($class) ? $class : '';
    $size = isset($size) && $size === 'sm' ? 'sm' : '';
    $clickableWrapper = !isset($clickableWrapper) ? true : (bool)$clickableWrapper;
?>

<div
    class="checkbox-wrapper {{ $class }} {{ $checked ? 'checked' : '' }} {{ $size ? 'checkbox--' . $size : '' }} {{ $clickableWrapper ? 'clickable' : '' }}"
    data-checkbox-wrapper="{{ $id }}"
>
    <label class="checkbox" for="{{ $id }}">
        <input
            id="{{ $id }}"
            class="checkbox-input"
            type="checkbox"
            @if ($name)
                name="{{ $name }}"
            @endif
            value="{{ $value }}"
            @if ($checked)
                checked
            @endif
            @if ($disabled)
                disabled
                aria-disabled="true"
            @endif
        />

        <span class="checkbox-box" aria-hidden="true">
            <!-- simple check icon -->
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12.5l4 4 10-10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

        <span class="checkbox-text">
            @if (!empty($slot) || trim((string)$slot) !== '')
                {{ $slot }}
            @elseif (!empty($label))
                {{ $label }}
            @endif
        </span>
    </label>
</div>

<script>
(() => {
    const id = `{{ $id }}`;
    const wrapper = document.querySelector(`[data-checkbox-wrapper="${id}"]`);
    if (!wrapper) {
        return;
    }

    const input = wrapper.querySelector(`#${id}`);
    const label = wrapper.querySelector('label.checkbox');

    // keep wrapper.checked class in-sync with input state (initial)
    const syncWrapperState = () => {
        if (!wrapper || !input) {
            return;
        }

        if (input.checked) {
            wrapper.classList.add('checked');
        } else {
            wrapper.classList.remove('checked');
        }
    };
    syncWrapperState();

    // update wrapper class on change (keyboard or programmatic)
    input.addEventListener('change', syncWrapperState);

    // if clickableWrapper is enabled, clicking outside the label but inside wrapper toggles checkbox.
    // We must avoid double toggles: if the click is inside the label we do nothing because native label behavior toggles.
    const clickable = {{ $clickableWrapper ? 'true' : 'false' }};

    if (clickable) {
        wrapper.addEventListener('click', (ev) => {
        // ignore if disabled
        if (input.disabled) {
            return;
        }

        // if click target is inside the label element, do nothing (label will handle toggle)
        if (ev.target && ev.target.closest && ev.target.closest('label.checkbox')) {
            return;
        }

        // otherwise toggle the input
        input.focus();
        input.checked = !input.checked;
        // trigger change event so other handlers listen in
        const changeEvt = new Event('change', { bubbles: true });
            input.dispatchEvent(changeEvt);
        });
    }
})();
</script>
