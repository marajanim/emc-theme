document.addEventListener('DOMContentLoaded', () => {

    /* =====================
       Event Countdown (to 15 May 2026 10:00 AM)
       ===================== */
    const eventDate = new Date('2026-05-15T10:00:00').getTime();

    function updateEventCD() {
        const now = Date.now();
        let diff = eventDate - now;
        if (diff < 0) diff = 0;

        const days  = Math.floor(diff / 86400000);
        const hours = Math.floor((diff % 86400000) / 3600000);
        const mins  = Math.floor((diff % 3600000) / 60000);

        const pad = n => String(n).padStart(2, '0');

        const el = id => document.getElementById(id);
        if (el('ev-days'))  el('ev-days').textContent  = pad(days);
        if (el('ev-hours')) el('ev-hours').textContent = pad(hours);
        if (el('ev-mins'))  el('ev-mins').textContent  = pad(mins);
    }

    setInterval(updateEventCD, 60000);
    updateEventCD();

    /* =====================
       RSVP Form Submission
       ===================== */
    const rsvpForm = document.getElementById('rsvp-form');
    const rsvpSuccess = document.getElementById('rsvp-success');

    if (rsvpForm) {
        rsvpForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const name  = document.getElementById('rsvp-name')?.value.trim();
            const email = document.getElementById('rsvp-email')?.value.trim();

            if (!name || !email) {
                alert('Please fill in your name and email address.');
                return;
            }

            // Simulate form submission
            const submitBtn = rsvpForm.querySelector('[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            setTimeout(() => {
                rsvpForm.querySelectorAll('input, select, textarea').forEach(el => el.style.display = 'none');
                submitBtn.style.display = 'none';
                if (rsvpSuccess) rsvpSuccess.style.display = 'flex';
            }, 1500);
        });
    }

    /* =====================
       Copy Link Button
       ===================== */
    const copyBtn = document.getElementById('copy-link-btn');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(window.location.href).then(() => {
                copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                copyBtn.style.background = 'var(--primary-green)';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-link"></i>';
                    copyBtn.style.background = '';
                }, 2000);
            });
        });
    }

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
    }, { threshold: 0.1 });

    document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
});
