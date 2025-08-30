<?php
// resources/views/components/table.wrapper.view.php
// Attributes: $class (extra classes), $card (bool) if true wrap in .table-card, $controls (slot)
$uid = 'tablewrap_' . bin2hex(random_bytes(5));
$wrapperClass = 'table-wrapper';
$cardClass = !empty($card) ? 'table-card' : '';
if (!empty($class)) $cardClass .= ' ' . $class;
?>
<div id="{{ $uid }}" class="{{ $wrapperClass }}">
  @if(!empty($card))
    <div class="{{ $cardClass }}">
      {{ $slot }}
    </div>
  @else
    {{ $slot }}
  @endif
</div>
