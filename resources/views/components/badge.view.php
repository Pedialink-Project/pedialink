<?php
$classes = 'badge';

if (!empty($class)) {
    $classes .= ' ' . $class;
}

$type = $type ?? '';

if (!empty($type)) {
    $classes .= " badge-{$type}";
}

if (!empty($size)) {
    $classes .= " badge-{$size}";
}

// if icon-only
if (!empty($icon_only)) $classes .= ' badge-icon';
?>

<span class="{{ $classes }}">
  {{ $slot }}
</span>