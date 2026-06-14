document.addEventListener('DOMContentLoaded', () => {

    /* =====================
       Generate Twinkling Stars
       ===================== */
    const starsBg = document.getElementById('stars-bg');
    if (starsBg) {
        for (let i = 0; i < 180; i++) {
            const star = document.createElement('div');
            star.classList.add('star');
            const size = Math.random() * 3 + 0.5;
            star.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                animation-duration: ${Math.random() * 4 + 2}s;
                animation-delay: ${Math.random() * 4}s;
                opacity: ${Math.random() * 0.7 + 0.2};
            `;
            starsBg.appendChild(star);
        }
    }

    /* =====================
       Live Countdown to Ramadan 2026
       Approx: 17 February 2026
       ===================== */
    const ramadanDate = new Date('2026-02-17T00:00:00').getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const diff = ramadanDate - now;

        if (diff <= 0) {
            document.querySelectorAll('.cdown-num, .c-num').forEach(el => el.textContent = '00');
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const secs = Math.floor((diff % (1000 * 60)) / 1000);

        const pad = n => String(n).padStart(2, '0');

        const el = (id) => document.getElementById(id);
        if (el('c-days'))  el('c-days').textContent  = pad(days);
        if (el('c-hours')) el('c-hours').textContent = pad(hours);
        if (el('c-mins'))  el('c-mins').textContent  = pad(mins);
        if (el('c-secs'))  el('c-secs').textContent  = pad(secs);

        // Also update the hero tab countdown on donate page
        if (el('r-days'))  el('r-days').textContent  = pad(days);
        if (el('r-hours')) el('r-hours').textContent = pad(hours);
        if (el('r-mins'))  el('r-mins').textContent  = pad(mins);
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();

    /* =====================
       Ramadan Schedule Calculator
       ===================== */
    const periodOptions = document.querySelectorAll('input[name="r-period"]');
    const amountBtns = document.querySelectorAll('.giving-form-col .amount-btn');
    const customInput = document.getElementById('r-custom-amount');

    const sumDaily  = document.getElementById('sum-daily');
    const sumPeriod = document.getElementById('sum-period');
    const sumTotal  = document.getElementById('sum-total');

    let currentDays = 30;
    let currentAmount = 3;

    function getSelectedAmount() {
        const activeBtn = document.querySelector('.giving-form-col .amount-btn.active');
        if (!activeBtn) return 3;
        if (activeBtn.classList.contains('custom-other')) {
            return parseFloat(customInput?.value || 3);
        }
        return parseFloat(activeBtn.textContent.replace('£', '')) || 3;
    }

    function updateSummary() {
        currentAmount = getSelectedAmount();
        const total = currentAmount * currentDays;
        if (sumDaily)  sumDaily.textContent  = `£${currentAmount}`;
        if (sumTotal)  sumTotal.textContent  = `£${total}`;

        const submitBtn = document.getElementById('ramadan-submit');
        if (submitBtn) {
            submitBtn.innerHTML = `<i class="fas fa-moon"></i> Schedule Giving — £${total} total`;
        }
    }

    periodOptions.forEach(radio => {
        radio.addEventListener('change', () => {
            const parent = radio.closest('.period-option');
            currentDays = parseInt(parent?.dataset.days || 30);
            const label  = parent?.dataset.label || '';
            if (sumPeriod) sumPeriod.textContent = label;
            updateSummary();
        });
    });

    amountBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            amountBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const customWrapper = document.querySelector('.giving-form-col .custom-amount-wrapper');
            if (btn.classList.contains('custom-other')) {
                if (customWrapper) customWrapper.style.display = 'block';
            } else {
                if (customWrapper) customWrapper.style.display = 'none';
            }
            updateSummary();
        });
    });

    if (customInput) customInput.addEventListener('input', updateSummary);

    updateSummary();

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
