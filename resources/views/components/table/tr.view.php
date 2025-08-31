<?php
// resources/views/components/tr.view.php
$uid = 'tr_' . bin2hex(random_bytes(5));
$classes = 'table__tr';
if (!empty($class)) $classes .= ' ' . $class;
?>
<tr 
    id="{{ $uid }}"
    class="{{ $classes }}"
    role="row" 
    @if(!empty($clickable)) 
        tabindex="0"
    @endif
>
  {{ $slot }}
</tr>
