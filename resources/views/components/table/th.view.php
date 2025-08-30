<?php
// resources/views/components/th.view.php
// attributes accepted: $class, $align ('left'|'center'|'right'), $sortable (bool), $width
$uid = 'th_' . bin2hex(random_bytes(5));
$classes = 'table__th';
if (!empty($class)) {
    $classes .= " {$class}";
}
$align = !empty($align) ? $align : 'left';

if ($align === 'center') {
    $classes .= ' table__th--center';
}

if ($align === 'right') {
    $classes .= ' table__th--right';
}

if ($align === 'left') {
    $classes .= ' table__th--left';
}

?>
<th
    id="{{ $uid }}"
    class="{{ $classes }}"
    scope="col"
    @if (!empty($width))
        style="width: {{ $width }}"
    @endif

    @if (!empty($sortable))
        aria-sortable="true"
    @endif
>
    <span class="th-label"
        @if(!empty($sortable))
            aria-sortable="true"
        @endif
    >
        {{ $slot }}
        @if(!empty($sortable))
            <img src="{{ asset('assets/icons/sort-up-down.svg') }}" alt="">
        @endif
  </span>
</th>

