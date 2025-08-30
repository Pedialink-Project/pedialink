<?php
// resources/views/components/dropdown.item.view.php
// Attributes: $href (optional), $class, $disabled (bool), $variant ('default'|'danger'|'muted'), $type ('button'|'submit') for button
$uid = 'dditem_' . bin2hex(random_bytes(5));
$classes = 'dropdown-item';
if (!empty($class)) {
    $classes .= " {$class}";
}
if (!empty($variant) && in_array($variant, ['danger','muted'])) {
    $classes .= ' dropdown-item--' . $variant;
}

?>
@if (!empty($href))
    <a
        id="{{ $uid }}"
        class="{{ $classes }}"
        role="menuitem"
        href="{{ $href }}"
        @if (!empty($disabled))
            disabled
        @endif
    >
        @if (!empty($slots['icon']))
            <span class="dropdown-item__icon">{{ $slots['icon'] }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button
        id="{{ $uid }}"
        type="{{ $type ?? 'button' }}"
        class="{{ $classes }}"
        role="menuitem"
        @if(!empty($disabled))
            aria-disabled="true"
            disabled
        @endif
    >
        @if (!empty($slots['icon']))
            <span class="dropdown-item__icon">{{ $slots['icon'] }}</span>
        @endif
        {{ $slot }}
    </button>
@endif
