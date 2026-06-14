/* ==========================================================================
   Donate Page JavaScript
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

    /* =====================
       Tab Switching
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
       Amount Button Selection (all groups)
       ===================== */
    document.querySelectorAll('.tab-panel').forEach(panel => {
        const amountBtns = panel.querySelectorAll('.amount-btn');
        const customWrapper = panel.querySelector('.custom-amount-wrapper');

        amountBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                amountBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                if (btn.classList.contains('custom-other')) {
                    if (customWrapper) customWrapper.style.display = 'block';
                    btn.textContent = 'Other';
                } else {
                    if (customWrapper) customWrapper.style.display = 'none';
                    const customBtn = panel.querySelector('.custom-other');
                    if (customBtn) customBtn.textContent = 'Other';
                }

                updateDonateButtonLabel(panel);
            });
        });
    });

    function getActiveAmount(panel) {
        const activeBtn = panel.querySelector('.amount-btn.active');
        if (!activeBtn) return '?';
        if (activeBtn.classList.contains('custom-other')) {
            const input = panel.querySelector('#custom-amount-input');
            return input?.value ? `£${input.value}` : '£?';
        }
        return activeBtn.textContent.trim();
    }

    function updateDonateButtonLabel(panel) {
        const submitBtn = panel.querySelector('.donate-submit');
        if (!submitBtn) return;
        const amount = getActiveAmount(panel);
        if (panel.id === 'tab-one-off') {
            submitBtn.innerHTML = `<i class="fas fa-lock"></i> Donate ${amount} Securely`;
        }
    }

    /* =====================
       Category Buttons
       ===================== */
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.category-grid');
            parent.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });

    /* =====================
       Zakat Calculator
       ===================== */
    const zakatInputs = document.querySelectorAll('.zakat-input');
    const zakatAmountEl = document.getElementById('zakat-amount');
    const zakatNoteEl = document.getElementById('zakat-note');
    const zakatDonateBtn = document.getElementById('donate-zakat-btn');
    const NISAB = 452.06;
    const ZAKAT_RATE = 0.025;

    function calcZakat() {
        const cash = parseFloat(document.getElementById('z-cash')?.value || 0);
        const gold = parseFloat(document.getElementById('z-gold')?.value || 0);
        const business = parseFloat(document.getElementById('z-business')?.value || 0);
        const owed = parseFloat(document.getElementById('z-owed')?.value || 0);
        const deduct = parseFloat(document.getElementById('z-deduct')?.value || 0);

        const totalAssets = cash + gold + business + owed - deduct;
        if (totalAssets <= 0) {
            zakatAmountEl.textContent = '£0.00';
            zakatNoteEl.textContent = 'Enter your assets above to calculate.';
            if (zakatDonateBtn) zakatDonateBtn.style.display = 'none';
            return;
        }

        if (totalAssets < NISAB) {
            zakatAmountEl.textContent = '£0.00';
            zakatNoteEl.textContent = `Your total assets (£${totalAssets.toFixed(2)}) are below the Nisab threshold of £${NISAB}. No Zakat is due.`;
            if (zakatDonateBtn) zakatDonateBtn.style.display = 'none';
        } else {
            const zakat = totalAssets * ZAKAT_RATE;
            zakatAmountEl.textContent = `£${zakat.toFixed(2)}`;
            zakatNoteEl.textContent = `2.5% of your zakatable assets (£${totalAssets.toFixed(2)}). Zakat is due.`;
            if (zakatDonateBtn) {
                zakatDonateBtn.style.display = 'flex';
                zakatDonateBtn.innerHTML = `<i class="fas fa-hand-holding-usd"></i> Donate My Zakat (£${zakat.toFixed(2)})`;
            }
        }
    }

    zakatInputs.forEach(input => input.addEventListener('input', calcZakat));
});
