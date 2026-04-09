<?php
$error = !empty($error) ? $error : null;

$classes = 'fileinput-row';
$classes .= isset($class) ? ' ' . $class : '';

if (!empty($size)) {
    $classes .= " fileinput--{$size}";
}

if ($error) {
    $classes .= " fileinput--error";
}
?>

<div class="fileinput-wrapper">
    @if(!empty($label))
        <label class="fileinput-label">
            {{ $label }}
            @if (isset($required))
                <span class="asterik">*</span>
            @endif
        </label>
    @endif

    <div 
        class="{{ $classes }}"
        @if (!empty($ariaDisabled))
            aria-disabled="true"
        @endif
    >
        <div class="fileinput-dropzone" data-fileinput-zone>
            <input
                type="file"
                class="fileinput-field"

                @if (!empty($id))
                    id="{{ $id }}"
                @endif

                @if (!empty($name))
                    name="{{ $name }}"
                @endif

                @if (!empty($accept))
                    accept="{{ $accept }}"
                @endif

                @if (!empty($required))
                    required
                @endif

                @if (!empty($disabled))
                    disabled
                @endif

                @if (!empty($multiple))
                    multiple
                @endif

                @if (!empty($ariaInvalid))
                    aria-invalid="true"
                @endif
            />

            <div class="fileinput-content">
                <svg class="fileinput-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" fill="currentColor"></path>
                </svg>

                <div class="fileinput-text">
                    <p class="fileinput-main">
                        <span class="fileinput-highlight">Click to upload</span> or drag and drop
                    </p>
                    @if(!empty($help))
                        <p class="fileinput-help">{{ $help }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($slots['fileList']))
            <div class="fileinput-list">
                {{ $slots['fileList'] }}
            </div>
        @endif
    </div>

    @if(!empty($error))
        <div class="fileinput-error-text">{{ $error ?? "" }}</div>
    @endif
</div>

<script>
    (function() {
        const initializeFileInput = function(element) {
            const dropzone = element?.querySelector('[data-fileinput-zone]');
            const fileInput = dropzone?.querySelector('input[type="file"]');

            if (!dropzone || !fileInput) return;

            // Prevent default drag behaviors
            const preventDefaults = (e) => {
                e.preventDefault();
                e.stopPropagation();
            };

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight on drag
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('drag-active');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('drag-active');
                }, false);
            });

            // Handle drop
            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change', { bubbles: true }));
            }, false);

            // Click to upload
            dropzone.addEventListener('click', (e) => {
                if (e.target !== fileInput && !fileInput.disabled) {
                    fileInput.click();
                }
            });
        };

        // Initialize on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.fileinput-wrapper').forEach(initializeFileInput);
            });
        } else {
            document.querySelectorAll('.fileinput-wrapper').forEach(initializeFileInput);
        }
    })();
</script>
