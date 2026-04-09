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

            tabindex="-1"
        />

        <div class="fileinput-dropzone" data-fileinput-zone>
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

        <div class="fileinput-selected" data-fileinput-list style="display: none;"></div>
    </div>

    @if(!empty($error))
        <div class="fileinput-error-text">{{ $error ?? "" }}</div>
    @endif
</div>

<script>
    (function() {
        const initializeFileInput = function(element) {
            const dropzone = element?.querySelector('[data-fileinput-zone]');
            const fileInput = element?.querySelector('input[type="file"]');
            const fileListContainer = element?.querySelector('[data-fileinput-list]');

            if (!dropzone || !fileInput || !fileListContainer) return;

            // Format file size
            const formatFileSize = (bytes) => {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            };

            // Update file list display
            const updateFileList = () => {
                const files = fileInput.files;
                
                if (files.length === 0) {
                    fileListContainer.style.display = 'none';
                    dropzone.style.display = 'flex';
                    return;
                }

                fileListContainer.innerHTML = '';
                dropzone.style.display = 'none';
                fileListContainer.style.display = 'block';

                const fileList = document.createElement('ul');
                fileList.className = 'fileinput-items';

                Array.from(files).forEach((file, index) => {
                    const li = document.createElement('li');
                    li.className = 'fileinput-item';

                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'fileinput-item-info';

                    const fileName = document.createElement('span');
                    fileName.className = 'fileinput-item-name';
                    fileName.textContent = file.name;

                    const fileSize = document.createElement('span');
                    fileSize.className = 'fileinput-item-size';
                    fileSize.textContent = formatFileSize(file.size);

                    fileInfo.appendChild(fileName);
                    fileInfo.appendChild(fileSize);

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'fileinput-item-remove';
                    removeBtn.type = 'button';
                    removeBtn.setAttribute('aria-label', `Remove ${file.name}`);
                    removeBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" fill="currentColor"/></svg>';

                    removeBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const dt = new DataTransfer();
                        Array.from(files).forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        fileInput.files = dt.files;
                        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    });

                    li.appendChild(fileInfo);
                    li.appendChild(removeBtn);
                    fileList.appendChild(li);
                });

                fileListContainer.appendChild(fileList);
            };

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

            // Handle file selection
            fileInput.addEventListener('change', updateFileList);
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
