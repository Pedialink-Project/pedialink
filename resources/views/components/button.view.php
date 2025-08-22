<?php
$classes = 'btn';

if (!empty($class)) {
    $classes .= ' ' . $class;
}

if (!empty($variant)) {
    $classes .= " btn-{$variant}";
}

if (!empty($size)) {
    $classes .= " btn-{$size}";
}

// if icon-only
if (!empty($icon_only)) $classes .= ' btn-icon';
?>

<button
    @if (!empty($id))
        id="{{ $id }}"
    @endif

    @if (!empty($type))
        type="{{ $type }}"
    @else 
        type="submit"
    @endif

    @if (!empty($form))
        form="{{ $form }}"
    @endif

    @if (!empty($disabled))
        disabled
    @endif
    
    class="{{ $classes }}"
>
  {{ $slot }}
</button>