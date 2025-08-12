<?php
$classes = 'badge';

if (!empty($class)) {
    $classes .= ' ' . $class;
}

$type = $type ?? '';
if ($type === 'primary') {
    $classes .= ' badge-primary';
} elseif ($type === 'secondary') {
    $classes .= ' badge-secondary';
}else if ($type === 'destructive') {
    $classes .= ' badge-destructive';
} else if ($type === 'outline'){
    $classes .= ' badge-outline';
} else if ($type === 'success') {
    $classes .= ' badge-success';
}

$size = $size ?? '';
if ($size === 'sm') {
    $classes .= ' badge-sm';
} else if ($size === 'lg') {
    $classes .= ' badge-lg';
}

// if icon-only
if (!empty($icon_only)) $classes .= ' badge-icon';
?>

<span class="{{ $classes }}">
  {{ $slot }}
</span>