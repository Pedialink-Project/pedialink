<?php
$classes = 'link';

if (!empty($class)) {
    $classes .= ' ' . $class;
}

if (!empty($type)) {
    $classes .= " link-{$type}";
} else {
    $classes .= " link-plain";
}

if (!empty($size)) {
    $classes .= " link-{$size}";
}

if (!empty($slots['icon']) && trim($slot) === '') { 
    $classes .= ' link-icon-only'; 
}
?>

<a
    @if (!empty($id))
        id="{{ $id }}"
    @endif

    @if (!empty($target))
        target="{{ $target }}"
    @endif

    @if (!empty($rel))
        rel="{{ $rel }}"
    @endif

    @if (!empty($disabled))
        aria-disabled="true"
        tabindex="-1"
    @endif

    href="{{ $href ?? "#" }}"
    class="{{ $classes }}"
>
    @if(!empty($slots['icon']))
        <span class="link-icon">
        {{ $slots["icon"] }}
        </span>
    @endif

    @if(trim($slot) !== '')
        {{ $slot }}
    @endif
</a>
