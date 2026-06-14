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
       Active Tab Highlight based on scroll position
       ===================== */
    const tabs = document.querySelectorAll('.svc-tab');
    const sections = ['friday-prayers', 'youth', 'reversion', 'wellbeing'].map(id => document.getElementById(id));

    function updateActiveTab() {
        let current = 'friday-prayers';
        const scrollY = window.scrollY + 200;

        sections.forEach(sec => {
            if (sec && sec.offsetTop <= scrollY) {
                current = sec.id;
            }
        });

        tabs.forEach(tab => {
            const href = tab.getAttribute('href')?.replace('#', '');
            tab.classList.toggle('active', href === current);
        });
    }

    window.addEventListener('scroll', updateActiveTab, { passive: true });
    updateActiveTab();
});
