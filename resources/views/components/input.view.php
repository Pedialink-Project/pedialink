<?php
$type = !empty($type) ? "type={$type}" : 'text';
$name = !empty($name) ? "name={$name}" : "";
$id = !empty($id) ? "id={$id}" : "";
$forId = !empty($id) ? "for={$id}" : "";
$value = isset($value) ? $value : "";
$placeholder = isset($placeholder) ? $placeholder : '';
$disabled = !empty($disabled) ? "disabled=true" : "";
$ariaDisabled = !empty($disabled) ? "aria-disabled=true" : "";
$required = !empty($required) ? "required" : "";
$error = !empty($error) ? $error : null;
$ariaInvalid = !empty($error) ? "aria-invalid=true" : "";

$classes = 'input-row';
$classes .= isset($class) ? ' ' . $class : '';

if (!empty($size)) {
    $classes .= " input--{$size}";
}

if ($error) {
    $classes .= " input--error";
}
?>

<div class="input-wrapper">
    @if(!empty($label))
        <label class="input-label" {{ $forId }}>{{ $label }}</label>
    @endif

    <div class="{{ $classes }}" {{ $ariaDisabled }}>
        @if(!empty($slots['prefix']))
            <span class="input-prefix">
                {{ $slots['prefix'] }}
            </span>
        @endif

        <input
            class="input-field"
            {{ $id }} {{ $type }} {{ $name }} {{ $placeholder }} {{ $required }}
            value="{{ $value }}"
            {{ $disabled }} {{ $ariaInvalid }}
        />

        @if(!empty($slots['suffix']))
            <span class="input-suffix">
                {{ $slots['suffix'] }}
            </span>
        @endif
    </div>

    @if(!empty($help) && !$error)
        <div class="input-help">{{ $help }}</div>
    @endif

    @if(!empty($error))
        <div class="input-error-text">{{ $error ?? "" }}</div>
    @endif
</div>
