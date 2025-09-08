<?php
// resources/views/components/modal.view.php
// Attributes:
//  - $id (optional string) : if you pass an id string it will be used (useful for reopening via ?modal=appointments)
//  - $class (optional) additional classes for .modal
//  - $size = 'md' | 'sm' | 'lg' (default 'md')
//  - $scrollable = true|false (default true) -> whether .modal-body is scrollable
//  - $closeOnOverlay = true|false (default true)
//  - $initOpen = true|false (open on initial render)
//

$id = isset($id) && is_string($id) ? $id : ('modal_' . bin2hex(random_bytes(5)));
$class = isset($class) ? $class : '';
$size = isset($size) ? $size : 'md';
$scrollable = isset($scrollable) ? (bool)$scrollable : true;
$closeOnOverlay = isset($closeOnOverlay) ? (bool)$closeOnOverlay : true;
$initOpen = isset($initOpen) ? (bool)$initOpen : false;

// allow opening by query param ?modal=<id>
if (isset($_GET['modal']) && $_GET['modal'] === $id) {
  $initOpen = true;
}

// expose a data-attr for JS
$initAttr = $initOpen ? 'true' : 'false';
?>

<!-- Optional inline trigger slot. If provided it will be bound to open the modal.
     If you want an external trigger (button elsewhere), give that element data-modal-trigger="{{ $id }}" -->
@if (!empty($slots['trigger']))
  <div class="modal-trigger-wrapper" data-modal-trigger-wrapper="{{ $id }}">
    {{ $slots['trigger'] }}
  </div>
@endif

<!-- Modal DOM (kept in place but portaled to body on open) -->
<div id="{{ $id }}" class="modal-component" data-modal-id="{{ $id }}" data-init-open="{{ $initAttr }}" data-close-on-overlay="{{ $closeOnOverlay ? 'true' : 'false' }}">
  <!-- backdrop and portal container are created/managed by JS on open -->
    <div class="modal-src" style="display:none;">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}_title" aria-describedby="{{ $id }}_desc" data-size="{{ $size }}" data-modal-class="{{ $class }}">
            <div class="modal-header">
                @if (!empty($slots['headerPrefix']))
                    <div class="modal-header-icon">
                        {{ $slots['headerPrefix']}}
                    </div>
                @endif

                @if (!empty($slots['header']))
                    <div class="modal-title" id="{{ $id }}_title">
                        {{ $slots['header'] }}
                    </div>
                @else
                    <div class="modal-title" id="{{ $id }}_title"></div>
                @endif

                @if (!empty($slots['headerSuffix']))
                    <div class="modal-header-icon">
                        {{ $slots['headerSuffix']}}
                    </div>
                @endif

                <button type="button" class="modal-close" aria-label="Close" data-modal-close="{{ $id }}">
                    <!-- simple X icon -->
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M6 6l8 8M6 14L14 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                </button>
            </div>

            <div class="modal-body {{ $scrollable ? '' : 'no-scroll' }}" id="{{ $id }}_desc">
                {{ $slot }}
            </div>

            <div class="modal-footer">
                @if (!empty($slots['footer']))
                    {{ $slots['footer'] }}
                @else
                    <button type="button" class="tc-btn tc-btn--outline" data-modal-close="{{ $id }}">
                        Cancel
                    </button>
                    <button type="button" class="tc-btn tc-btn--primary" data-modal-confirm="{{ $id }}">
                        OK
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const id = `{{ $id }}`;
    const component = document.querySelector(`[data-modal-id="${id}"]`);
    if (!component) {
        return;
    }

    const closeOnOverlay = component.dataset.closeOnOverlay === 'true';
    const initOpen = component.dataset.initOpen === 'true';
    const size = component.querySelector('.modal')?.dataset.size || 'md';
    const userClass = component.querySelector('.modal')?.dataset.modalClass || '';

    // find trigger(s):
    // 1) inline trigger slot (child with data-modal-trigger-wrapper)
    // 2) any external element with attribute [data-modal-trigger="<id>"]
    const inlineWrapper = document.querySelector(`[data-modal-trigger-wrapper="${id}"]`);
    const externalTriggers = Array.from(document.querySelectorAll(`[data-modal-trigger="${id}"]`));

    const modalSrc = component.querySelector('.modal-src .modal');
    if (!modalSrc) {
        return;
    }

    // create portal nodes (backdrop and portal container)
    let portalBackdrop = null;
    let portalRoot = null;

    function createPortalNodes() {
        portalBackdrop = document.createElement('div');
        portalBackdrop.className = 'modal-backdrop';
        portalBackdrop.setAttribute('data-modal-backdrop', id);

        portalRoot = document.createElement('div');
        portalRoot.className = 'modal-portal';
        portalRoot.setAttribute('data-modal-portal', id);

        // set dialog size class
        if (size === 'sm') {
            modalSrc.classList.add('modal--sm');
        } else if (size === 'lg') {
            modalSrc.classList.add('modal--lg');
        } else {
            modalSrc.classList.add('modal--md');
        }

        if (userClass) {
            modalSrc.classList.add(...userClass.split(/\s+/));
        }
    }

    // state
    let open = false;
    let previouslyFocused = null;

    // helpers to find focusable elements
    const FOCUSABLE = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';
    function focusFirst(dialog) {
        const el = dialog.querySelectorAll(FOCUSABLE);
        if (el && el.length) {
            el[0].focus();
            return true;
        }
        return false;
    }

    // open routine (creates portal, appends dialog, sets aria, traps focus)
    function openModal() {
        if (open) {
            return;
        }
        createPortalNodes();

        // append backdrop first
        document.body.appendChild(portalBackdrop);

        // Instead of cloning, MOVE the original modal element into the portal.
        // modalSrc references the original .modal inside the component's .modal-src.
        const original = modalSrc; 
        if (!original) {
            return;
        }

        // Create a placeholder so we can restore later
        const placeholder = document.createComment('modal-placeholder-' + id);
        original._modal_placeholder = placeholder;
        original.parentNode.insertBefore(placeholder, original);

        // Append the original node into our portalRoot and append portalRoot to body
        portalRoot.appendChild(original);
        document.body.appendChild(portalRoot);

        // show backdrop + dialog (use RAF for smooth transition)
        requestAnimationFrame(() => {
            portalBackdrop.classList.add('open');
            original.classList.add('open');
            portalRoot.style.pointerEvents = 'auto';
        });

        // remember previously focused element
        previouslyFocused = document.activeElement;

        // focus first focusable element inside modal (or close btn)
        const firstFocusable = original.querySelector(FOCUSABLE) || original.querySelector('.modal-close');
        if (firstFocusable) firstFocusable.focus();

        // Backdrop click handler (fallback)
        const onBackdropClick = (ev) => {
            if (!closeOnOverlay) return;
            closeModal(true);
        };
        portalBackdrop.addEventListener('click', onBackdropClick);
        portalBackdrop._onBackdropClick = onBackdropClick;

        // Portal root click: if user clicks on the portal root itself (outside dialog), close.
        const onPortalClick = (ev) => {
            if (!closeOnOverlay) {
                return;
            }
            if (ev.target === portalRoot) {
                closeModal(true);
            }
        };

        portalRoot.addEventListener('click', onPortalClick);
        portalRoot._onPortalClick = onPortalClick;

        // keyboard handler (Escape + rudimentary focus trap)
        function onKey(e) {
            if (e.key === 'Escape') {
                if (closeOnOverlay) {
                    closeModal(true);
                }
            } else if (e.key === 'Tab') {
                const focusables = Array.from(original.querySelectorAll(FOCUSABLE))
                    .filter(el => el.offsetParent !== null && !el.hasAttribute('disabled'));

                if (!focusables.length) {
                    e.preventDefault();
                    return;
                }

                const idx = focusables.indexOf(document.activeElement);

                if (e.shiftKey && idx === 0) {
                    e.preventDefault();
                    focusables[focusables.length - 1].focus();
                } else if (!e.shiftKey && idx === focusables.length - 1) {
                    e.preventDefault();
                    focusables[0].focus();
                }
            }
        }
        document.addEventListener('keydown', onKey);
        portalRoot._modal_onKey = onKey;

        // Wire up internal close/confirm buttons inside the original (moved) modal.
        // Store handlers on the buttons so we can remove them on close to avoid duplicates.
        original.querySelectorAll('[data-modal-close]').forEach(btn => {
            const fn = (ev) => { ev.preventDefault(); closeModal(true); };
            btn.addEventListener('click', fn);
            btn._modal_close_handler = fn;
        });

        original.querySelectorAll('[data-modal-confirm]').forEach(btn => {
            const fn = (ev) => {
            // emit a custom event for consumer to handle (eg. submit)
            const evt = new CustomEvent('modal:confirm', { detail: { id } });
            document.dispatchEvent(evt);
            };
            btn.addEventListener('click', fn);
            btn._modal_confirm_handler = fn;
        });

        // mark open state
        open = true;
        document.body.classList.add('modal-open');

        // dispatch open event
        document.dispatchEvent(new CustomEvent('modal:open', { detail: { id } }));
    }

    function closeModal(refocus = true) {
        if (!open) {
            return;
        }

        // Find current portal/backdrop nodes
        const currentBackdrop = document.querySelector(`[data-modal-backdrop="${id}"]`);
        const currentPortal = document.querySelector(`[data-modal-portal="${id}"]`);

        // If the original modal was moved into the portal, retrieve it
        const movedDialog = currentPortal ? currentPortal.querySelector('.modal') : null;

        // Remove 'open' classes to start CSS transitions
        if (movedDialog) {
            movedDialog.classList.remove('open');
        }

        if (currentBackdrop) {
            currentBackdrop.classList.remove('open');
        }

        // cleanup after a short delay to allow transitions to finish
        setTimeout(() => {
            // Remove portalRoot handlers & keyboard handler
            if (currentPortal) {
            // remove portal click handler
                if (currentPortal._onPortalClick) {
                    currentPortal.removeEventListener('click', currentPortal._onPortalClick);
                    delete currentPortal._onPortalClick;
                }
                // remove key handler
                if (currentPortal._modal_onKey) {
                    document.removeEventListener('keydown', currentPortal._modal_onKey);
                    delete currentPortal._modal_onKey;
                }
            }

            // Remove backdrop and its handler
            if (currentBackdrop) {
                if (currentBackdrop._onBackdropClick) {
                    currentBackdrop.removeEventListener('click', currentBackdrop._onBackdropClick);
                    delete currentBackdrop._onBackdropClick;
                }
            }

            // If we moved the original dialog, move it back to its placeholder in the original DOM
            if (movedDialog) {
                const placeholder = movedDialog._modal_placeholder;
                if (placeholder && placeholder.parentNode) {
                    // insert before placeholder and then remove placeholder
                    placeholder.parentNode.insertBefore(movedDialog, placeholder);
                    placeholder.parentNode.removeChild(placeholder);
                    delete movedDialog._modal_placeholder;
                } else {
                    // fallback: if no placeholder, append back to original container if available
                    const origContainer = component.querySelector('.modal-src');
                    if (origContainer) {
                        origContainer.appendChild(movedDialog);
                    }
                }

                // remove the click handlers we attached to internal buttons
                movedDialog.querySelectorAll('[data-modal-close]').forEach(btn => {
                    if (btn._modal_close_handler) {
                        btn.removeEventListener('click', btn._modal_close_handler);
                        delete btn._modal_close_handler;
                    }
                });
                movedDialog.querySelectorAll('[data-modal-confirm]').forEach(btn => {
                    if (btn._modal_confirm_handler) {
                        btn.removeEventListener('click', btn._modal_confirm_handler);
                        delete btn._modal_confirm_handler;
                    }
                });
            }

            // finally remove the portal DOM nodes if they still exist
            if (currentPortal && currentPortal.parentNode) {
                currentPortal.parentNode.removeChild(currentPortal);
            }

            if (currentBackdrop && currentBackdrop.parentNode) {
                currentBackdrop.parentNode.removeChild(currentBackdrop);
            }

            // restore body state & focus
            document.body.classList.remove('modal-open');
            open = false;
            if (refocus && previouslyFocused && previouslyFocused.focus) {
                previouslyFocused.focus();
            }

            // dispatch closed event
            document.dispatchEvent(new CustomEvent('modal:close', { detail: { id } }));
        }, 160); // allow CSS transition to finish
    }

    // Wire triggers
    function bindTrigger(node) {
        node.addEventListener('click', (e) => {
            e.preventDefault();
            openModal();
        });
    }

    if (inlineWrapper) {
        // bind any clickable inside inline wrapper
        inlineWrapper.querySelectorAll('*').forEach(el => {
            if (el.matches && (el.tagName === 'BUTTON' || el.matches('[role="button"]') || el.matches('a'))) {
                bindTrigger(el);
            }
        });
    }

    externalTriggers.forEach(t => bindTrigger(t));

    // also allow programmatic API via window.ModalControls
    window.ModalControls = window.ModalControls || {};
    window.ModalControls[id] = {
        open: openModal,
        close: closeModal,
        isOpen: () => open
    };

    // auto-open on init if requested
    if (initOpen) {
        // small defer so DOM ready
        setTimeout(() => openModal(), 20);
    }

    // cleanup if component removed (optional)
    window.addEventListener('unload', () => {
        try {
            closeModal(false);
        } catch(e) {}
    });
})();
</script>
