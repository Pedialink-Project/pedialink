<?php
// resources/views/components/td.view.php
// attributes: $class, $align ('left'|'center'|'right'), $col (optional data-col)
$uid = 'td_' . bin2hex(random_bytes(6));
$classes = 'table__td';

if (!empty($class)){
  $classes .= " {$class}";
}

$align = !empty($align) ? $align : 'left';

if ($align === 'center') {
  $classes .= ' table__td--center';
}

if ($align === 'right')  {
  $classes .= ' table__td--right';
}

if ($align === 'left') {
  $classes .= ' table__td--left';
}

?>
<td
    id="{{ $uid }}"
    class="{{ $classes }}"
    @if (!empty($col))
        data-col="{{ $col }}"
    @endif
>
  {{ $slot }}
</td>
