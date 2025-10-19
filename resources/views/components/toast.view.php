<?php
// resources/views/components/toast.view.php
$uid = 'toast_' . bin2hex(random_bytes(6));
$initToasts = $initToasts ?? []; // array of toasts passed from server
// sanitize/prepare JSON for data attribute
$initJson = json_encode($initToasts, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
?>
<div id="{{ $uid }}" class="toast-component" data-toast-init='<?= htmlspecialchars($initJson, ENT_QUOTES, 'UTF-8') ?>' style="display:none;"></div>

<script>
(() => {
    const rootId = `{{ $uid }}`;
    const component = document.getElementById(rootId);
    if (!component) {
        return;
    }

    // Create portal container and attach to body
    let toastRoot = document.querySelector('.toast-root');
    if (!toastRoot) {
        toastRoot = document.createElement('div');
        toastRoot.className = 'toast-root';
        document.body.appendChild(toastRoot);
    }

    // Utility: generate id
    const genId = (prefix='t') => `${prefix}_${Math.random().toString(36).slice(2,9)}`;

    // Default options
    const DEFAULT_DURATION = 4000;

    // Exposed API
    window.ToastControls = window.ToastControls || {};
    const API = {
        show(opts = {}) {
            const id = opts.id || genId('toast');
            const type = opts.type || 'info';
            const title = opts.title || '';
            const message = opts.message || '';
            const duration = (typeof opts.duration === 'number') ? opts.duration : DEFAULT_DURATION;
            const action = opts.action || null;
            createToast({ id, type, title, message, duration, action });
            return id;
        },
        success(opts = {}) { return this.show(Object.assign({ type:'success' }, opts)); },
        error(opts = {}) { return this.show(Object.assign({ type:'error' }, opts)); },
        info(opts = {}) { return this.show(Object.assign({ type:'info' }, opts)); },
        clear(id) {
            if (id) {
                const el = toastRoot.querySelector(`[data-toast-id="${id}"]`);
                if (el) removeToast(el);
            } else {
                // clear all
                Array.from(toastRoot.querySelectorAll('.toast')).forEach(removeToast);
            }
        }
    };
    window.ToastControls = Object.assign(window.ToastControls, API);

    // Create toast DOM and logic
    function createToast({ id, type, title, message, duration, action }) {
        // protect against duplicates
        if (toastRoot.querySelector(`[data-toast-id="${id}"]`)) {
            return;
        }

        const el = document.createElement('div');
        el.className = `toast toast--${type}`;
        el.setAttribute('role', 'status');
        el.setAttribute('aria-live', 'polite');
        el.setAttribute('data-toast-id', id);

        el.innerHTML = `
        <div class="toast-body">
            ${ title ? `<div class="toast-title">${escapeHtml(title)}</div>` : '' }
            ${ message ? `<div class="toast-message">${escapeHtml(message)}</div>` : '' }
        </div>
        <div class="toast-actions">
            ${ action ? `<a href="${ action.link }" class="toast-action" type="button">${escapeHtml(action.text || 'Action')}</a>` : '' }
            <button class="toast-close" type="button" aria-label="Dismiss" data-toast-close>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M6 18L18 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </button>
        </div>
        `;

        // insert at top (newest on top)
        toastRoot.insertBefore(el, toastRoot.firstChild);

        // show animation
        requestAnimationFrame(() => el.classList.add('show'));

        // timers & handlers
        let timer = null;
        let remaining = duration;
        let startTime = null;

        function startTimer() {
            if (!duration || duration <= 0) {
                return;
            }
            startTime = Date.now();
            timer = setTimeout(() => removeToast(el), remaining);
        }

        function pauseTimer() {
            if (!timer) {
                return;
            }

            clearTimeout(timer);
            timer = null;
            
            if (startTime) {
                remaining = Math.max(0, remaining - (Date.now() - startTime));
            }
        }

        // interactions
        el.addEventListener('mouseenter', pauseTimer);
        el.addEventListener('focusin', pauseTimer);
        el.addEventListener('mouseleave', startTimer);
        el.addEventListener('focusout', startTimer);

        // close button
        const closeBtn = el.querySelector('[data-toast-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => removeToast(el));
        }

        // action button (custom)
        // const actionBtn = el.querySelector('[data-toast-action]');
        // if (actionBtn) {
        //     actionBtn.addEventListener('click', (ev) => {
        //         ev.preventDefault();
        //         // dispatch custom event: 'toast:action' with detail { id, actionId }
        //         const actionId = actionBtn.getAttribute('data-toast-action') || '';
        //         document.dispatchEvent(new CustomEvent('toast:action', { detail: { toastId: id, actionId } }));
        //     });
        // }

        // remove after duration unless persistent
        if (duration && duration > 0) {
            startTimer();
        }

        return el;
    }

    function removeToast(el) {
            if (!el) {
                return;
            }
            el.classList.remove('show');
            // wait transition (match CSS ~120ms)
            setTimeout(() => {
                if (el && el.parentNode) {
                    el.parentNode.removeChild(el);
                }
            }, 140);
    }

    // escape helper (textContent-safe)
    function escapeHtml(s) {
        if (s === null || s === undefined) {
            return '';
        }
        
        return String(s)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;');
    }

    // handle initial toasts passed from server via data attribute
    try {
        const init = component.getAttribute('data-toast-init');
        console.log(typeof init);
        if (init) {
            const msg = JSON.parse(init);
            console.log(msg);

            if (msg && !(Array.isArray(msg)) && msg.text) {
                const d = (typeof msg.duration === 'number') ? msg.duration : DEFAULT_DURATION;
                API.show(
                    {
                        type: msg.type || 'info' ,
                        title: msg.title || 'Alert',
                        message: msg.text,
                        duration: d,
                        action: msg.action || null,
                    }
                );
            }
        }
    } catch (err) {
        console.log("Failed");
    }

    // helpful: open toast programmatically if window.localStorage has key (post-redirect pattern)
    // This is optional — you can set localStorage before redirect, then it will open on load
    try {
        const pending = localStorage.getItem('pendingToast');
        if (pending) {
            const p = JSON.parse(pending);

            if (p && (p.message || p.title)) {
                API.show(p);
            }

            localStorage.removeItem('pendingToast');
        }
    } catch (e) {}

})();
</script>
