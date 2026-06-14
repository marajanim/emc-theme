/**
 * EMC Theme — events.js
 * Phase 10: Events page interactions with URL hash sync.
 *
 * @package emc-theme
 */

document.addEventListener('DOMContentLoaded', () => {

    /* =====================
       Scroll Reveal
       ===================== */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });

    document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

    /* =====================
       Category Filter Chips + URL Hash Sync
       ===================== */
    const chips     = document.querySelectorAll('.filter-chip');
    const cards     = document.querySelectorAll('.event-hub-card');
    const noResults = document.getElementById('no-results');

    function applyFilter(filter) {
        let visibleCount = 0;

        // Update active chip
        chips.forEach(c => {
            const isActive = c.dataset.filter === filter;
            c.classList.toggle('active', isActive);
            c.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });

        // Filter cards with smooth transition
        cards.forEach(card => {
            const category = card.dataset.category || 'all';
            const isVisible = filter === 'all' || category === filter;

            if (isVisible) {
                card.classList.remove('filtered-out');
                visibleCount++;
            } else {
                card.classList.add('filtered-out');
            }
        });

        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        // Sync URL hash (without triggering scroll)
        const newUrl = filter === 'all'
            ? window.location.pathname + window.location.search
            : `${window.location.pathname}${window.location.search}#${filter}`;
        history.replaceState(null, '', newUrl);
    }

    // Add ARIA pressed state to chips
    chips.forEach(chip => {
        chip.setAttribute('role', 'button');
        chip.setAttribute('aria-pressed', chip.classList.contains('active') ? 'true' : 'false');

        chip.addEventListener('click', () => {
            applyFilter(chip.dataset.filter);
        });

        // Keyboard support
        chip.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                chip.click();
            }
        });
    });

    // Read URL hash on load
    const hashFilter = window.location.hash.replace('#', '');
    if (hashFilter && document.querySelector(`.filter-chip[data-filter="${hashFilter}"]`)) {
        applyFilter(hashFilter);
    }

    /* =====================
       Grid / List View Toggle
       ===================== */
    const gridBtn   = document.getElementById('grid-view-btn');
    const listBtn   = document.getElementById('list-view-btn');
    const container = document.getElementById('events-container');
    const VIEW_KEY  = 'emc_events_view';

    function setView(mode) {
        if (mode === 'list') {
            container?.classList.add('list-view');
            listBtn?.classList.add('active');
            gridBtn?.classList.remove('active');
            listBtn?.setAttribute('aria-pressed', 'true');
            gridBtn?.setAttribute('aria-pressed', 'false');
        } else {
            container?.classList.remove('list-view');
            gridBtn?.classList.add('active');
            listBtn?.classList.remove('active');
            gridBtn?.setAttribute('aria-pressed', 'true');
            listBtn?.setAttribute('aria-pressed', 'false');
        }
        try { localStorage.setItem(VIEW_KEY, mode); } catch (_) {}
    }

    // Restore last-used view preference
    const savedView = localStorage.getItem(VIEW_KEY) || 'grid';
    setView(savedView);

    gridBtn?.addEventListener('click', () => setView('grid'));
    listBtn?.addEventListener('click', () => setView('list'));

    // ARIA
    gridBtn?.setAttribute('role', 'button');
    listBtn?.setAttribute('role', 'button');
});
