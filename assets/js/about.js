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
    }, { threshold: 0.1 });

    document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

    /* =====================
       Animated Stats Counter
       ===================== */
    const statNums = document.querySelectorAll('.stat-num[data-target]');

    function animateNum(el, target) {
        if (isNaN(target)) return; // skip non-numeric like "#1209815"
        const duration = 2000;
        const start = performance.now();
        const from = 0;

        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(from + eased * (target - from)).toLocaleString() + (el.dataset.suffix || '');
            if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
    }

    const statsObs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const rawTarget = el.dataset.target;
                const numericTarget = parseInt(rawTarget.replace(/\D/g, ''), 10);
                if (!isNaN(numericTarget) && numericTarget < 9999) {
                    animateNum(el, numericTarget);
                }
                statsObs.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    statNums.forEach(el => statsObs.observe(el));
});
