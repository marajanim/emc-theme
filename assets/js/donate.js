/* ==========================================================================
   Donate Page JavaScript — Stripe Payment Integration
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

    /* =====================================================================
       Tab Switching
    ===================================================================== */
    const tabBtns   = document.querySelectorAll('.tab-btn');
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

    /* =====================================================================
       Amount Button Selection
    ===================================================================== */
    document.querySelectorAll('.tab-panel').forEach(panel => {
        const amountBtns   = panel.querySelectorAll('.amount-btn');
        const customWrapper = panel.querySelector('.custom-amount-wrapper');

        amountBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                amountBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                if (btn.classList.contains('custom-other')) {
                    if (customWrapper) customWrapper.style.display = 'block';
                } else {
                    if (customWrapper) customWrapper.style.display = 'none';
                    const customBtn = panel.querySelector('.custom-other');
                    if (customBtn) customBtn.textContent = 'Other';
                }
                updateDonateButtonLabel(panel);
            });
        });

        // Update label when typing custom amount
        const customInput = panel.querySelector('.custom-amount-input, #custom-amount-input');
        if (customInput) {
            customInput.addEventListener('input', () => updateDonateButtonLabel(panel));
        }
    });

    function getActiveAmountPence(panel) {
        const activeBtn = panel.querySelector('.amount-btn.active');
        if (!activeBtn) return 0;
        if (activeBtn.classList.contains('custom-other')) {
            const input = panel.querySelector('.custom-amount-input, #custom-amount-input');
            const val   = parseFloat(input?.value || 0);
            return Math.round(val * 100);
        }
        const text = activeBtn.textContent.replace(/[^0-9.]/g, '');
        return Math.round(parseFloat(text || 0) * 100);
    }

    function getActiveAmountDisplay(panel) {
        const pence = getActiveAmountPence(panel);
        return pence > 0 ? '£' + (pence / 100).toFixed(2) : '£?';
    }

    function updateDonateButtonLabel(panel) {
        const submitBtn = panel.querySelector('.donate-submit');
        if (!submitBtn) return;
        const amount = getActiveAmountDisplay(panel);
        const icon   = panel.id === 'tab-regular'
            ? '<i class="fas fa-sync-alt"></i>'
            : '<i class="fas fa-lock"></i>';
        if (panel.id === 'tab-zakat') return; // Zakat manages its own button
        submitBtn.innerHTML = `${icon} Donate ${amount} Securely`;
    }

    /* =====================================================================
       Category Buttons
    ===================================================================== */
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.category-grid');
            parent?.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });

    /* =====================================================================
       Zakat Calculator
    ===================================================================== */
    const zakatInputs   = document.querySelectorAll('.zakat-input');
    const zakatAmountEl = document.getElementById('zakat-amount');
    const zakatNoteEl   = document.getElementById('zakat-note');
    const zakatDonateBtn = document.getElementById('donate-zakat-btn');
    const NISAB      = 452.06;
    const ZAKAT_RATE = 0.025;

    function calcZakat() {
        const cash     = parseFloat(document.getElementById('z-cash')?.value     || 0);
        const gold     = parseFloat(document.getElementById('z-gold')?.value     || 0);
        const business = parseFloat(document.getElementById('z-business')?.value || 0);
        const owed     = parseFloat(document.getElementById('z-owed')?.value     || 0);
        const deduct   = parseFloat(document.getElementById('z-deduct')?.value   || 0);
        const total    = cash + gold + business + owed - deduct;

        if (total <= 0) {
            if (zakatAmountEl) zakatAmountEl.textContent = '£0.00';
            if (zakatNoteEl)   zakatNoteEl.textContent   = 'Enter your assets above to calculate.';
            if (zakatDonateBtn) zakatDonateBtn.style.display = 'none';
            return;
        }
        if (total < NISAB) {
            if (zakatAmountEl) zakatAmountEl.textContent = '£0.00';
            if (zakatNoteEl)   zakatNoteEl.textContent   = `Your assets (£${total.toFixed(2)}) are below the Nisab (£${NISAB}). No Zakat is due.`;
            if (zakatDonateBtn) zakatDonateBtn.style.display = 'none';
        } else {
            const zakat = total * ZAKAT_RATE;
            if (zakatAmountEl) zakatAmountEl.textContent = `£${zakat.toFixed(2)}`;
            if (zakatNoteEl)   zakatNoteEl.textContent   = `2.5% of £${total.toFixed(2)}. Zakat is due.`;
            if (zakatDonateBtn) {
                zakatDonateBtn.style.display = 'flex';
                zakatDonateBtn.dataset.zakatAmount = Math.round(zakat * 100); // store pence
                zakatDonateBtn.innerHTML = `<i class="fas fa-hand-holding-usd"></i> Donate My Zakat (£${zakat.toFixed(2)})`;
            }
        }
    }
    zakatInputs.forEach(input => input.addEventListener('input', calcZakat));

    /* =====================================================================
       STRIPE PAYMENT INTEGRATION
    ===================================================================== */
    if (typeof emcStripeConfig === 'undefined' || typeof Stripe === 'undefined') {
        console.warn('EMC: Stripe config or Stripe.js not loaded.');
        return;
    }

    const { publishableKey, ajaxUrl, nonce } = emcStripeConfig;
    const stripe = Stripe(publishableKey);

    // ── State ──────────────────────────────────────────────────────────────
    let stripeElements  = null;
    let paymentElement  = null;
    let currentPiId     = null;
    let currentAmount   = 0;     // pence
    let currentFund     = 'General Fund';
    let currentGiftAid  = false;
    let currentMessage  = '';
    let currentName     = '';
    let currentEmail    = '';
    let currentTab      = 'one-off';

    // ── Inject Payment Modal ───────────────────────────────────────────────
    const modal = document.createElement('div');
    modal.id        = 'emc-stripe-modal';
    modal.className = 'emc-stripe-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-label', 'Secure payment');
    // modal starts hidden via CSS default (display:none — no is-visible class)
    modal.innerHTML = `
        <div class="emc-stripe-modal-backdrop"></div>
        <div class="emc-stripe-modal-card">
            <button class="emc-stripe-modal-close" aria-label="Close payment">&times;</button>

            <!-- Header -->
            <div class="esm-header">
                <div class="esm-lock"><i class="fas fa-lock"></i></div>
                <div>
                    <h3 class="esm-title">Complete Your Donation</h3>
                    <p class="esm-subtitle">Secured by <strong>Stripe</strong> · 256-bit encryption</p>
                </div>
            </div>

            <!-- Amount pill -->
            <div class="esm-amount-bar">
                <span class="esm-fund-name" id="esm-fund-name">General Fund</span>
                <span class="esm-amount-num" id="esm-amount-display">£0.00</span>
            </div>

            <!-- Loading state -->
            <div class="esm-loading" id="esm-loading">
                <div class="esm-spinner"></div>
                <p>Preparing secure payment…</p>
            </div>

            <!-- Stripe Payment Element -->
            <div id="payment-element" class="esm-payment-element" style="display:none;"></div>

            <!-- Error -->
            <div class="esm-error" id="esm-error" hidden></div>

            <!-- Submit -->
            <button class="btn btn-primary esm-pay-btn" id="esm-pay-btn" disabled>
                <i class="fas fa-lock"></i>
                <span id="esm-pay-label">Pay Now</span>
            </button>

            <!-- Success -->
            <div class="esm-success" id="esm-success" hidden>
                <div class="esm-success-icon"><i class="fas fa-check-circle"></i></div>
                <h3>JazakAllahu Khayran!</h3>
                <p>Your donation of <strong id="esm-success-amount"></strong> has been received.</p>
                <p class="esm-receipt-note">A receipt has been emailed to you.</p>
                <button class="btn btn-outline esm-close-btn" id="esm-success-close">Close</button>
            </div>

            <p class="esm-secure-note">
                <i class="fas fa-shield-alt"></i> Your card details are never stored on our servers.
                Powered by <a href="https://stripe.com" target="_blank" rel="noopener">Stripe</a>.
            </p>
        </div>`;
    document.body.appendChild(modal);

    // ── Modal helpers ──────────────────────────────────────────────────────
    function openModal() {
        modal.classList.add('is-visible');
        document.body.style.overflow = 'hidden';
        setTimeout(() => modal.classList.add('is-open'), 10);
    }

    function closeModal() {
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
        setTimeout(() => {
            modal.classList.remove('is-visible');
            resetModal();
        }, 300);
    }

    function resetModal() {
        document.getElementById('esm-loading').style.display   = 'flex';
        document.getElementById('payment-element').style.display = 'none';
        document.getElementById('esm-error').hidden             = true;
        document.getElementById('esm-success').hidden           = true;
        document.getElementById('esm-pay-btn').disabled         = true;
        document.getElementById('esm-pay-btn').style.display    = 'flex';
        document.getElementById('esm-pay-btn').innerHTML        = '<i class="fas fa-lock"></i><span id="esm-pay-label">Pay Now</span>';
        document.querySelector('.esm-secure-note').hidden       = false;
        if (stripeElements) { stripeElements = null; paymentElement = null; }
    }

    // Close triggers
    modal.querySelector('.emc-stripe-modal-close').addEventListener('click', closeModal);
    modal.querySelector('.emc-stripe-modal-backdrop').addEventListener('click', closeModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-visible')) closeModal(); });
    document.getElementById('esm-success-close')?.addEventListener('click', closeModal);

    // ── Create PaymentIntent & mount Stripe Element ────────────────────────
    async function initStripePayment(amountPence, fund, tab) {
        const body = new URLSearchParams({
            action : 'emc_stripe_create_intent',
            nonce  : nonce,
            amount : amountPence,
            fund   : fund,
            tab    : tab,
        });

        const res  = await fetch(ajaxUrl, { method: 'POST', body });
        const data = await res.json();

        if (!data.success) throw new Error(data.data?.message || 'Payment gateway error.');

        currentPiId = data.data.pi_id;

        // Initialize Stripe Elements with the client secret
        stripeElements = stripe.elements({ clientSecret: data.data.client_secret,
            appearance: {
                theme : 'stripe',
                variables: {
                    colorPrimary       : '#2aaca0',
                    colorBackground    : '#ffffff',
                    colorText          : '#0c1f2e',
                    colorDanger        : '#df1b41',
                    fontFamily         : 'Inter, system-ui, sans-serif',
                    borderRadius       : '10px',
                    spacingUnit        : '4px',
                },
            },
        });

        paymentElement = stripeElements.create('payment', {
            layout: { type: 'tabs', defaultCollapsed: false },
        });

        const mountEl = document.getElementById('payment-element');
        paymentElement.mount(mountEl);

        paymentElement.on('ready', () => {
            document.getElementById('esm-loading').style.display   = 'none';
            mountEl.style.display                                   = 'block';
            const payBtn = document.getElementById('esm-pay-btn');
            payBtn.disabled = false;
            payBtn.innerHTML = `<i class="fas fa-lock"></i><span>Pay ${getDisplayAmount(amountPence)}</span>`;
        });
    }

    function getDisplayAmount(pence) {
        return '£' + (pence / 100).toFixed(2);
    }

    // ── Collect donor details from active panel ────────────────────────────
    function collectDonorDetails(panel) {
        currentName     = panel.querySelector('#donor-name')?.value?.trim()    || '';
        currentEmail    = panel.querySelector('#donor-email')?.value?.trim()   || '';
        currentGiftAid  = !!(panel.querySelector('.gift-aid-check')?.checked);
        currentMessage  = panel.querySelector('#donor-message')?.value?.trim() || '';
        currentFund     = panel.querySelector('.cat-btn.active')?.dataset.cat  || 'General Fund';
    }

    // ── Handle "Donate Securely" button clicks (one-off & regular) ────────
    document.querySelectorAll('.tab-panel .donate-submit').forEach(btn => {
        if (btn.id === 'donate-zakat-btn') return;
        btn.addEventListener('click', async () => {
            const panel  = btn.closest('.tab-panel');
            const pence  = getActiveAmountPence(panel);
            currentTab   = panel.id.replace('tab-', '');

            if (pence < 50) {
                showInlineError(panel, 'Please select or enter a donation amount (minimum £0.50).');
                return;
            }
            collectDonorDetails(panel);
            currentAmount = pence;

            // Update modal display
            document.getElementById('esm-amount-display').textContent = getDisplayAmount(pence);
            document.getElementById('esm-fund-name').textContent       = currentFund;

            openModal();

            try {
                await initStripePayment(pence, currentFund, currentTab);
            } catch (err) {
                document.getElementById('esm-loading').style.display = 'none';
                const errEl = document.getElementById('esm-error');
                errEl.textContent = err.message;
                errEl.hidden = false;
            }
        });
    });

    // ── Zakat donate button ────────────────────────────────────────────────
    if (zakatDonateBtn) {
        zakatDonateBtn.addEventListener('click', async () => {
            const pence = parseInt(zakatDonateBtn.dataset.zakatAmount || 0);
            if (pence < 50) return;

            currentAmount  = pence;
            currentFund    = 'Zakat';
            currentTab     = 'zakat';
            currentGiftAid = false;
            currentMessage = '';

            document.getElementById('esm-amount-display').textContent = getDisplayAmount(pence);
            document.getElementById('esm-fund-name').textContent       = 'Zakat';

            openModal();
            try {
                await initStripePayment(pence, 'Zakat', 'zakat');
            } catch (err) {
                document.getElementById('esm-loading').style.display = 'none';
                const errEl = document.getElementById('esm-error');
                errEl.textContent = err.message;
                errEl.hidden = false;
            }
        });
    }

    // ── Handle "Pay Now" submission ────────────────────────────────────────
    document.getElementById('esm-pay-btn')?.addEventListener('click', async () => {
        const payBtn  = document.getElementById('esm-pay-btn');
        const errEl   = document.getElementById('esm-error');

        payBtn.disabled = true;
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';
        errEl.hidden = true;

        try {
            const { error } = await stripe.confirmPayment({
                elements  : stripeElements,
                redirect  : 'if_required',
                confirmParams: {
                    return_url: window.location.href + '?donated=1',
                    payment_method_data: {
                        billing_details: {
                            name  : currentName  || undefined,
                            email : currentEmail || undefined,
                        },
                    },
                },
            });

            if (error) {
                errEl.textContent = error.message;
                errEl.hidden      = false;
                payBtn.disabled   = false;
                payBtn.innerHTML  = `<i class="fas fa-lock"></i><span>Pay ${getDisplayAmount(currentAmount)}</span>`;
                return;
            }

            // Payment succeeded — record it
            await recordDonation();
            showSuccess();

        } catch (err) {
            errEl.textContent = err.message || 'An unexpected error occurred.';
            errEl.hidden      = false;
            payBtn.disabled   = false;
            payBtn.innerHTML  = `<i class="fas fa-lock"></i><span>Try Again</span>`;
        }
    });

    // ── Record donation server-side ────────────────────────────────────────
    async function recordDonation() {
        const body = new URLSearchParams({
            action   : 'emc_stripe_record',
            nonce    : nonce,
            pi_id    : currentPiId,
            amount   : currentAmount,
            fund     : currentFund,
            name     : currentName,
            email    : currentEmail,
            gift_aid : currentGiftAid ? '1' : '0',
            message  : currentMessage,
        });
        await fetch(ajaxUrl, { method: 'POST', body });
    }

    // ── Show success state ─────────────────────────────────────────────────
    function showSuccess() {
        document.getElementById('payment-element').style.display = 'none';
        document.getElementById('esm-pay-btn').style.display     = 'none';
        document.querySelector('.esm-secure-note').hidden         = true;
        document.getElementById('esm-error').hidden               = true;
        document.getElementById('esm-success-amount').textContent = getDisplayAmount(currentAmount);
        document.getElementById('esm-success').hidden             = false;
    }

    // ── Inline error helper ────────────────────────────────────────────────
    function showInlineError(panel, message) {
        let err = panel.querySelector('.donate-inline-error');
        if (!err) {
            err = document.createElement('p');
            err.className = 'donate-inline-error';
            panel.querySelector('.donate-submit')?.before(err);
        }
        err.textContent = message;
        err.style.display = 'block';
        setTimeout(() => { err.style.display = 'none'; }, 4000);
    }
    // ── Global bridge — lets other scripts (ramadan.js) open the modal ────
    /**
     * window.emcOpenStripeModal({ amount, fund, tab, name, email, giftAid })
     * amount  : integer pence (e.g. 3000 = £30)
     * fund    : string label shown in modal  (e.g. 'Ramadan Giving')
     * tab     : string context key          (e.g. 'ramadan', 'fidya')
     */
    window.emcOpenStripeModal = function({ amount, fund = 'General Fund', tab = 'one-off',
                                           name = '', email = '', giftAid = false }) {
        if (amount < 50) return;
        currentAmount  = amount;
        currentFund    = fund;
        currentTab     = tab;
        currentName    = name;
        currentEmail   = email;
        currentGiftAid = giftAid;
        currentMessage = '';

        document.getElementById('esm-amount-display').textContent = getDisplayAmount(amount);
        document.getElementById('esm-fund-name').textContent      = fund;

        openModal();

        initStripePayment(amount, fund, tab).catch(err => {
            document.getElementById('esm-loading').style.display = 'none';
            const errEl = document.getElementById('esm-error');
            errEl.textContent = err.message;
            errEl.hidden = false;
        });
    };

});
