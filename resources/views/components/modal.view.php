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
                @if (!empty($slots['header']))
                    <div class="modal-title" id="{{ $id }}_title">
                        {{ $slots['header'] }}
                    </div>
                @else
                    <div class="modal-title" id="{{ $id }}_title"></div>
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

        // append to body
        document.body.appendChild(portalBackdrop);
        const cloned = modalSrc.cloneNode(true);
        // ensure cloned modal has unique ids replaced (aria-labeledby/desc should still match id)
        portalRoot.appendChild(cloned);
        document.body.appendChild(portalRoot);

        // show backdrop + dialog
        requestAnimationFrame(() => {
            portalBackdrop.classList.add('open');
            cloned.classList.add('open');
            portalRoot.style.pointerEvents = 'auto';
        });

        // remember focus
        previouslyFocused = document.activeElement;

        // focus first focusable inside modal (or close btn)
        const firstFocusable = cloned.querySelector(FOCUSABLE) || cloned.querySelector('.modal-close');
        if (firstFocusable) {
            firstFocusable.focus();
        }

        // attach handlers
        portalBackdrop.addEventListener('click', (ev) => {
            if (!closeOnOverlay) {
                return;
            }
            closeModal();
        });

        const onBackdropClick = (ev) => {
            if (!closeOnOverlay) return;
            closeModal(true);
        };
        portalBackdrop.addEventListener('click', onBackdropClick);
        portalBackdrop._onBackdropClick = onBackdropClick;

        // handle clicks on the portal root: if click target is portalRoot (i.e. outside dialog), close
        const onPortalClick = (ev) => {
            if (!closeOnOverlay) return;
            // If the click target is the portal root itself (not inside dialog), close.
            // This handles cases where portalRoot sits above the backdrop and intercepts clicks.
            if (ev.target === portalRoot) {
            closeModal(true);
            }
        };

        portalRoot.addEventListener('click', onPortalClick);
        portalRoot._onPortalClick = onPortalClick;

        cloned.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', (ev) => {
                ev.preventDefault();
                closeModal(true);
            });
        });

        cloned.querySelectorAll('[data-modal-confirm]').forEach(btn => {
            btn.addEventListener('click', (ev) => {
                // emit a custom event for consumer to handle (eg. submit)
                const evt = new CustomEvent('modal:confirm', { detail: { id } });
                document.dispatchEvent(evt);
            });
        });

        // keyboard: Escape closes
        function onKey(e) {
            if (e.key === 'Escape') {
                if (closeOnOverlay) {
                    closeModal(true);
                }
            } else if (e.key === 'Tab') {
                // rudimentary focus trap
                const focusables = Array
                    .from(cloned.querySelectorAll(FOCUSABLE))
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

        // store handlers for cleanup
        portalRoot._modal_onKey = onKey;
        open = true;
        // add body class for styling if desired
        document.body.classList.add('modal-open');

        // dispatch event
        document.dispatchEvent(new CustomEvent('modal:open', { detail: { id } }));
    }

    function closeModal(refocus = true) {
        if (!open) {
            return;
        }

        const currentBackdrop = document.querySelector(`[data-modal-backdrop="${id}"]`);
        const currentPortal = document.querySelector(`[data-modal-portal="${id}"]`);

        if (currentPortal) {
            const dialog = currentPortal.querySelector('.modal');
            if (dialog) {
                dialog.classList.remove('open');
            }
        }

        if (currentBackdrop) {
            currentBackdrop.classList.remove('open');
        }

        // cleanup after transition frame
        setTimeout(() => {
            if (currentPortal) {
                if (currentPortal._onPortalClick) {
                    currentPortal.removeEventListener('click', currentPortal._onPortalClick);
                    delete currentPortal._onPortalClick;
                }
                // remove keyboard handler if stored
                if (currentPortal._modal_onKey) {
                    document.removeEventListener('keydown', currentPortal._modal_onKey);
                    delete currentPortal._modal_onKey;
                }
                // finally remove the portal node
                if (currentPortal.parentNode) {
                    currentPortal.parentNode.removeChild(currentPortal);
                }
            }

            // remove backdrop and its handler
            if (currentBackdrop) {
                if (currentBackdrop._onBackdropClick) {
                    currentBackdrop.removeEventListener('click', currentBackdrop._onBackdropClick);
                    delete currentBackdrop._onBackdropClick;
                }
                if (currentBackdrop.parentNode) {
                    currentBackdrop.parentNode.removeChild(currentBackdrop);
                }
            }

            document.body.classList.remove('modal-open');
            open = false;

            // restore focus
            if (refocus && previouslyFocused && previouslyFocused.focus) {
                previouslyFocused.focus();
            }

            document.dispatchEvent(new CustomEvent('modal:close', { detail: { id } }));
        }, 160);
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
