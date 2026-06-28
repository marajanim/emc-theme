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

    /* =====================
       Full Flyer Preview
       ===================== */
    const flyerModal = document.createElement('div');
    flyerModal.className = 'event-flyer-modal';
    flyerModal.innerHTML = `
        <button class="event-flyer-close" type="button" aria-label="Close flyer">&times;</button>
        <img src="" alt="Event flyer">
    `;
    document.body.appendChild(flyerModal);

    const flyerImg = flyerModal.querySelector('img');
    const closeFlyer = () => {
        flyerModal.classList.remove('is-visible');
        document.body.style.overflow = '';
        if (flyerImg) flyerImg.src = '';
    };

    document.querySelectorAll('.event-hub-img[data-flyer-url]').forEach(img => {
        img.setAttribute('role', 'button');
        img.setAttribute('tabindex', '0');
        img.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();
            if (!flyerImg) return;
            flyerImg.src = img.dataset.flyerUrl;
            flyerModal.classList.add('is-visible');
            document.body.style.overflow = 'hidden';
        });
        img.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                img.click();
            }
        });
    });

    flyerModal.addEventListener('click', event => {
        if (event.target === flyerModal || event.target.classList.contains('event-flyer-close')) {
            closeFlyer();
        }
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && flyerModal.classList.contains('is-visible')) closeFlyer();
    });
});
