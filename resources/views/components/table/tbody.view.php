<?php
// resources/views/components/tbody.view.php
$uid = 'tbody_' . bin2hex(random_bytes(6));
$classes = 'table__tbody';
if (!empty($class)) $classes .= ' ' . $class;
?>
<tbody id="{{ $uid }}" class="{{ $classes }}" role="rowgroup">
  {{ $slot }}
</tbody>
