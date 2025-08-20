<?php
$id = !empty($id) ? "id='{$id}'" : "";

$classes = 'btn';

if (!empty($class)) {
    $classes .= ' ' . $class;
}

if (!empty($type)) {
    $classes .= " btn-{$type}";
}

if (!empty($size)) {
    $classes .= " btn-{$size}";
}

// if icon-only
if (!empty($icon_only)) $classes .= ' btn-icon';
?>

<button {{ $id }} class="{{ $classes }}">
  {{ $slot }}
</button>