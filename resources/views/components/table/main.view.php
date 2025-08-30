<?php
// resources/views/components/table.view.php
// Attributes: $class, $striped, $sticky, $size ('compact'|'comfortable')
$uid = 'table_' . bin2hex(random_bytes(6));
$classes = 'table';
if (!empty($striped)) $classes .= ' table--striped';
if (!empty($sticky)) $classes .= ' table--sticky';
if (!empty($size) && in_array($size, ['compact','comfortable'])) $classes .= ' table--' . $size;
if (!empty($class)) $classes .= ' ' . $class;
?>
<table id="{{ $uid }}" class="{{ $classes }}" role="table" aria-describedby="{{ $uid . '_desc' }}">
  {{ $slot }}
</table>
<div id="{{ $uid . '_desc' }}" style="display:none;">Table</div>
