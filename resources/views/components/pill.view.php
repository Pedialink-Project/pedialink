<?php
$classes = 'pill';

if (!empty($class)) {
    $classes .= ' ' . $class;
}


?>

<span class="{{ $classes }}">
    <div class="text-section">
    @if(!empty($slots['title']))
    <span class="pill-title">{{ $slots['title'] }}</span> 
    @endif
 @if(!empty($slots['number']))
    <span class="pill-number">{{ $slots['number'] }}</span> 
    @endif

    </div>

    @if(!empty($slots['icon']))
    <div class="pill-icon">
        {{ $slots['icon'] }}
    </div>
    @endif
</span>