<?php

$classes = 'textarea-row';

if (!empty($class)) {
    $classes .= " {$class}";
}

if (!empty($size)) {
    $classes .= ' textarea--' . $size;
}
if (!empty($error)) {
    $classes .= ' textarea--error';
}

?>

<div class="textarea-wrapper">
    @if(!empty($label))
        <label class="textarea-label"
            @if (!empty($id))
                for="{{ $id }}"
            @endif
        >
            {{ $label }}
            @if (isset($required))
                <span class="asterik">*</span>
            @endif
        </label>
    @endif

    <div 
        class="{{ $classes }}"
        @if (!empty($disabled))
            aria-disabled="true"
        @endif
    >
        <textarea
            class="textarea-field"

            @if (!empty($id))
                id="{{ $id }}"
            @endif

            @if (!empty($name))
                name="{{ $name }}"
            @endif

            @if (!empty($rows))
                rows="{{ $rows }}"
            @endif

            @if (!empty($placeholder))
                placeholder="{{ $placeholder }}"
            @endif

            @if (!empty($required))
                required
            @endif

            @if (!empty($disabled))
                disabled
            @endif

            @if (!empty($maxLength))
                maxlength="{{ $maxLength }}"
            @endif
        >{{ $slot ?? "" }}</textarea>
    </div>

    @if(empty($error) && !empty($help))
        <div class="textarea-help">{{ $attributes['help'] }}</div>
    @endif

    @if(!empty($error))
        <div class="textarea-error-text">{{ $error ?? "" }}</div>
    @endif
</div>