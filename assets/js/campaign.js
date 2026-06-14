document.addEventListener('DOMContentLoaded', () => {

    /* =====================
       Animate Campaign Progress Bar on Load
       ===================== */
    const fill = document.getElementById('campaign-fill');
    // Slight delay so the animation is visible to the user
    if (fill) {
        setTimeout(() => {
            fill.style.width = '63%';
        }, 400);
    }

    /* =====================
       Animate Counter Number
       ===================== */
    function animateCount(el, target, prefix = '', suffix = '') {
        if (!el) return;
        const duration = 2000;
        const start = performance.now();

        function update(time) {
            const elapsed = time - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);
            el.textContent = prefix + current.toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(update);
        }

        requestAnimationFrame(update);
    }

    // Animate the raised amount & donor count when hero is in view
    const progressBox = document.querySelector('.campaign-progress-box');
    if (progressBox) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCount(document.getElementById('amount-raised'), 62500, '£');
                    animateCount(document.getElementById('donor-count'), 142);
                    obs.disconnect();
                }
            });
        }, { threshold: 0.2 });
        obs.observe(progressBox);
    }

    /* =====================
       Tab Switching (Campaign Page)
       ===================== */
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(`tab-${target}`)?.classList.add('active');
        });
    });

    /* =====================
       Amount Button Selection
       ===================== */
    document.querySelectorAll('.tab-panel').forEach(panel => {
        const btns = panel.querySelectorAll('.amount-btn');
        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                btns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    });

    /* =====================
       Donor Wall — Empty slots link to donate section
       ===================== */
    document.querySelectorAll('.donor-slot.empty').forEach(slot => {
        slot.addEventListener('click', () => {
            document.getElementById('campaign-donate')?.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
