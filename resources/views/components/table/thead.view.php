<?php
// resources/views/components/thead.view.php
$uid = 'thead_' . bin2hex(random_bytes(5));
$classes = 'table__thead';
if (!empty($class)) $classes .= ' ' . $class;
?>
<thead 
    id="{{ $uid }}"
    class="{{ $classes }}"
    role="rowgroup"
>
  {{ $slot }}
</thead>
