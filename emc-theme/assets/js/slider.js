/**
 * EMC Theme — slider.js
 * Phase 10: Lightweight auto-cycling image/content carousel.
 *
 * Targets: .emc-slider (wrapper) containing:
 *   .emc-slider-track  → the sliding strip
 *   .emc-slide         → each individual slide
 *   .emc-slider-prev / .emc-slider-next → navigation arrows
 *   .emc-slider-dots   → auto-generated dot indicators
 *
 * Supports: auto-play, pause-on-hover, keyboard navigation,
 *           swipe/touch, reduced-motion, ARIA live regions.
 *
 * @package emc-theme
 */

(function () {
    'use strict';

    const AUTOPLAY_DELAY = 5000;
    const TRANSITION_MS  = 600;

    /**
     * Initialise one slider instance.
     * @param {HTMLElement} sliderEl
     */
    function initSlider(sliderEl) {
        const track  = sliderEl.querySelector('.emc-slider-track');
        const slides = sliderEl.querySelectorAll('.emc-slide');
        if (!track || slides.length < 2) return;

        const prevBtn  = sliderEl.querySelector('.emc-slider-prev');
        const nextBtn  = sliderEl.querySelector('.emc-slider-next');
        const dotsWrap = sliderEl.querySelector('.emc-slider-dots');
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        let current    = 0;
        let autoTimer  = null;
        let isAnimating = false;

        const total = slides.length;

        /* ── Build dot indicators ──────────────────────────────────────────── */
        let dots = [];
        if (dotsWrap) {
            slides.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.className = 'emc-slider-dot';
                dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
                dot.addEventListener('click', () => goTo(i));
                dotsWrap.appendChild(dot);
                dots.push(dot);
            });
        }

        /* ── ARIA setup ─────────────────────────────────────────────────────── */
        sliderEl.setAttribute('role', 'region');
        sliderEl.setAttribute('aria-roledescription', 'carousel');
        slides.forEach((slide, i) => {
            slide.setAttribute('role', 'group');
            slide.setAttribute('aria-roledescription', 'slide');
            slide.setAttribute('aria-label', `${i + 1} of ${total}`);
            slide.setAttribute('aria-hidden', i === 0 ? 'false' : 'true');
        });

        /* ── Core navigation ────────────────────────────────────────────────── */
        function updateSlider(index, direction) {
            if (isAnimating) return;
            isAnimating = true;

            const prev = current;
            current = (index + total) % total;

            // Update ARIA
            slides[prev].setAttribute('aria-hidden', 'true');
            slides[current].setAttribute('aria-hidden', 'false');

            // CSS transform slide
            if (!prefersReducedMotion) {
                track.style.transition = `transform ${TRANSITION_MS}ms cubic-bezier(0.25, 1, 0.5, 1)`;
            }
            track.style.transform = `translateX(-${current * 100}%)`;

            // Update dots
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === current);
                dot.setAttribute('aria-current', i === current ? 'true' : 'false');
            });

            setTimeout(() => { isAnimating = false; }, TRANSITION_MS);
        }

        function goTo(index) { updateSlider(index, index > current ? 1 : -1); }
        function next()      { goTo(current + 1); }
        function prev()      { goTo(current - 1); }

        /* ── Auto-play ──────────────────────────────────────────────────────── */
        function startAutoPlay() {
            if (prefersReducedMotion) return;
            stopAutoPlay();
            autoTimer = setInterval(next, AUTOPLAY_DELAY);
        }

        function stopAutoPlay() {
            clearInterval(autoTimer);
        }

        /* ── Button listeners ───────────────────────────────────────────────── */
        prevBtn?.addEventListener('click', () => { prev(); startAutoPlay(); });
        nextBtn?.addEventListener('click', () => { next(); startAutoPlay(); });

        /* ── Pause on hover/focus ───────────────────────────────────────────── */
        sliderEl.addEventListener('mouseenter', stopAutoPlay);
        sliderEl.addEventListener('mouseleave', startAutoPlay);
        sliderEl.addEventListener('focusin',    stopAutoPlay);
        sliderEl.addEventListener('focusout',   startAutoPlay);

        /* ── Keyboard ───────────────────────────────────────────────────────── */
        sliderEl.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft')  { prev(); startAutoPlay(); }
            if (e.key === 'ArrowRight') { next(); startAutoPlay(); }
        });

        /* ── Touch / Swipe ──────────────────────────────────────────────────── */
        let touchStartX = 0;
        let touchEndX   = 0;

        sliderEl.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        sliderEl.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const delta = touchStartX - touchEndX;
            if (Math.abs(delta) > 50) {
                delta > 0 ? next() : prev();
                startAutoPlay();
            }
        }, { passive: true });

        /* ── Init state ─────────────────────────────────────────────────────── */
        track.style.transform = 'translateX(0)';
        dots.forEach((dot, i) => dot.classList.toggle('active', i === 0));
        if (dots[0]) dots[0].setAttribute('aria-current', 'true');
        startAutoPlay();
    }

    /* ── Bootstrap all sliders on the page ─────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.emc-slider').forEach(initSlider);
    });

    /* ── Public API ─────────────────────────────────────────────────────────── */
    window.emcSlider = { init: initSlider };

})();
