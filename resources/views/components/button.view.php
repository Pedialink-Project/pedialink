<?php
$classes = 'btn';

if (!empty($class)) {
    $classes .= ' ' . $class;
}

$type = $type ?? '';
if ($type === 'primary') {
    $classes .= ' btn-primary';
} else if ($type === 'ghost') {
    $classes .= ' btn-ghost';
} else if ($type === 'outline'){
    $classes .= ' btn-outline';
} else if ($type === 'subtle') {
    $classes .= ' btn-subtle';
}

$size = $size ?? '';
if ($size === 'sm') {
    $classes .= ' btn-sm';
} else if ($size === 'lg') {
    $classes .= ' btn-lg';
}

// if icon-only
if (!empty($icon_only)) $classes .= ' btn-icon';
?>

<button class="{{ $classes }}">
  {{ $slot }}
</button>