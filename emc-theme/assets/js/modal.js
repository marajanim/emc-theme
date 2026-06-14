/**
 * EMC Theme — modal.js
 * Phase 10: Global reusable modal/popup system.
 * 
 * Usage:
 *   Open:  emcModal.open('#my-modal')  OR data-modal-target="#my-modal" on any button
 *   Close: emcModal.close()            OR data-modal-close on any element inside the modal
 *
 * @package emc-theme
 */

(function () {
    'use strict';

    const FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
    let activeModal = null;
    let previousFocus = null;

    /* ── Open ──────────────────────────────────────────────────────────────── */
    function openModal(modalEl) {
        if (!modalEl) return;

        // Close any already-open modal first
        if (activeModal) closeModal();

        activeModal = modalEl;
        previousFocus = document.activeElement;

        modalEl.removeAttribute('hidden');
        modalEl.classList.add('is-open');
        document.body.classList.add('modal-open');

        // Trap focus inside the modal
        const focusables = modalEl.querySelectorAll(FOCUSABLE);
        if (focusables.length) focusables[0].focus();

        // Announce to screen readers
        modalEl.setAttribute('aria-modal', 'true');
        modalEl.setAttribute('aria-hidden', 'false');

        // Animate in (CSS handles the transition via .is-open class)
        requestAnimationFrame(() => {
            const inner = modalEl.querySelector('.modal-inner, .emc-modal-inner');
            if (inner) inner.style.transform = 'translateY(0) scale(1)';
        });
    }

    /* ── Close ─────────────────────────────────────────────────────────────── */
    function closeModal() {
        if (!activeModal) return;

        const modal = activeModal;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');

        // Wait for CSS transition before hiding
        modal.addEventListener('transitionend', () => {
            modal.setAttribute('hidden', '');
        }, { once: true });

        // Restore focus
        if (previousFocus) previousFocus.focus();
        activeModal = null;
    }

    /* ── Focus Trap ────────────────────────────────────────────────────────── */
    document.addEventListener('keydown', (e) => {
        if (!activeModal) return;

        if (e.key === 'Escape') {
            closeModal();
            return;
        }

        if (e.key === 'Tab') {
            const focusables = Array.from(activeModal.querySelectorAll(FOCUSABLE));
            if (!focusables.length) { e.preventDefault(); return; }

            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
    });

    /* ── Event Delegation ──────────────────────────────────────────────────── */
    document.addEventListener('click', (e) => {

        // Open triggers: [data-modal-target="#id"]
        const opener = e.target.closest('[data-modal-target]');
        if (opener) {
            e.preventDefault();
            const target = document.querySelector(opener.dataset.modalTarget);
            if (target) openModal(target);
            return;
        }

        // Close triggers: [data-modal-close] inside a modal
        if (e.target.closest('[data-modal-close]')) {
            closeModal();
            return;
        }

        // Click on backdrop (the modal overlay itself, not the inner panel)
        if (activeModal && e.target === activeModal) {
            closeModal();
        }
    });

    /* ── Public API ────────────────────────────────────────────────────────── */
    window.emcModal = {
        open(selectorOrEl) {
            const el = typeof selectorOrEl === 'string'
                ? document.querySelector(selectorOrEl)
                : selectorOrEl;
            openModal(el);
        },
        close: closeModal,
    };

    /* ── Init: Pre-existing [data-modal-init] modals ──────────────────────── */
    // Any modal that should open on page load can use data-modal-init="1"
    document.querySelectorAll('[data-modal-init]').forEach(modal => {
        setTimeout(() => openModal(modal), 600);
    });

})();
