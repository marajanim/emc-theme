document.addEventListener('DOMContentLoaded', () => {
    /* ==========================================================================
       Header & Navigation (Spring Animation)
       ========================================================================== */
    const header = document.getElementById('header');
    const mobileToggle = document.getElementById('mobile-toggle');
    const closeToggle = document.getElementById('close-toggle');
    const mobileNav = document.getElementById('mobile-nav');

    // Sticky Header
    const handleScroll = () => {
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };
    
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Init

    // Mobile Menu Toggle
    if (mobileToggle && mobileNav && closeToggle) {
        const toggleMenu = () => {
            const isActive = mobileNav.classList.contains('active');
            if (!isActive) {
                mobileNav.classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                mobileNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        };

        mobileToggle.addEventListener('click', toggleMenu);
        closeToggle.addEventListener('click', toggleMenu);

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (mobileNav.classList.contains('active') && !mobileNav.contains(e.target) && !mobileToggle.contains(e.target)) {
                toggleMenu();
            }
        });
    }

    /* ==========================================================================
       Magnetic Buttons Effect
       ========================================================================== */
    const magneticBtns = document.querySelectorAll('.magnetic-btn');
    
    magneticBtns.forEach(wrapper => {
        const btn = wrapper.querySelector('.btn');
        
        wrapper.addEventListener('mousemove', (e) => {
            const rect = wrapper.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            // Subtle pull
            btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
        });
        
        wrapper.addEventListener('mouseleave', () => {
            btn.style.transform = `translate(0px, 0px)`;
        });
    });

    /* ==========================================================================
       Quick Donate Interaction â€” Inline Custom Amount
       ========================================================================== */
    const amountBtns = document.querySelectorAll('.amount-btn');
    const customRow  = document.getElementById('custom-amount-row');
    const customInput = document.getElementById('custom-amount-input');

    if (amountBtns.length > 0) {
        amountBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();

                // Deactivate all buttons
                amountBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                if (btn.classList.contains('custom')) {
                    // Show inline input
                    if (customRow) {
                        customRow.classList.add('open');
                        customRow.setAttribute('aria-hidden', 'false');
                        // Focus the input after the transition starts
                        setTimeout(() => customInput && customInput.focus(), 100);
                    }
                } else {
                    // Hide inline input & clear it
                    if (customRow) {
                        customRow.classList.remove('open');
                        customRow.setAttribute('aria-hidden', 'true');
                    }
                    if (customInput) customInput.value = '';
                }
            });
        });

        // Typing in the custom input keeps "Other" active
        if (customInput) {
            customInput.addEventListener('input', () => {
                const customBtn = document.querySelector('.amount-btn.custom');
                if (customBtn) {
                    amountBtns.forEach(b => b.classList.remove('active'));
                    customBtn.classList.add('active');
                }
            });
        }
    }

    /* ==========================================================================
       Scroll Reveal Animations (Intersection Observer fallback/enhancement)
       ========================================================================== */
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
                observer.unobserve(entry.target); // Only animate once
            }
        });
    }, observerOptions);

    const revealElements = document.querySelectorAll('.scroll-reveal');
    revealElements.forEach(el => observer.observe(el));

    /* ==========================================================================
       Campaign: Animated Stat Counters + Progress Bar
       ========================================================================== */
    function animateCampaignNum(el, target) {
        const prefix = el.dataset.prefix || '';
        const suffix = el.dataset.suffix || '';
        const duration = 2000;
        const start = performance.now();

        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(eased * target);
            el.textContent = prefix + current.toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
    }

    function animateCampaignBar(fill, pctEl, percent) {
        const duration = 1800;
        const start = performance.now();

        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(eased * percent);
            fill.style.width = current + '%';
            if (pctEl) pctEl.textContent = current + '%';
            if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
    }

    // Observe campaign section to trigger on scroll
    const campaignSection = document.getElementById('campaign');
    if (campaignSection) {
        let campaignAnimated = false;
        const campaignObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !campaignAnimated) {
                    campaignAnimated = true;

                    // Animate stat numbers
                    document.querySelectorAll('.campaign-stat-num[data-target]').forEach(el => {
                        animateCampaignNum(el, parseInt(el.dataset.target, 10));
                    });

                    // Animate progress bar
                    const fill = document.querySelector('.campaign-progress-bar-fill');
                    const pctEl = document.getElementById('campaign-pct');
                    if (fill) {
                        const pct = parseInt(fill.dataset.percent, 10) || 0;
                        animateCampaignBar(fill, pctEl, pct);
                    }

                    campaignObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        campaignObs.observe(campaignSection);
    }

    /* ==========================================================================
       Newsletter Form
       ========================================================================== */
    const newsletterForm = document.getElementById('newsletter-form');
    const nlSuccess = document.getElementById('nl-success');
    const nlEmail = document.getElementById('nl-email');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();

            if (!nlEmail || !nlEmail.value.trim() || !nlEmail.value.includes('@')) {
                nlEmail.style.borderColor = '#E53935';
                nlEmail.focus();
                return;
            }

            const btn = newsletterForm.querySelector('[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
            btn.disabled = true;

            setTimeout(() => {
                newsletterForm.querySelector('.newsletter-input-wrap').style.display = 'none';
                btn.style.display = 'none';
                newsletterForm.querySelector('.newsletter-disclaimer').style.display = 'none';
                if (nlSuccess) nlSuccess.style.display = 'flex';
            }, 1200);
        });

        if (nlEmail) {
            nlEmail.addEventListener('input', () => {
                nlEmail.style.borderColor = '';
            });
        }
    }

    /* ==========================================================================
       Cookie Consent Banner
       ========================================================================== */
    const COOKIE_KEY = 'emc_cookie_consent';

    if (!localStorage.getItem(COOKIE_KEY)) {
        const banner = document.createElement('div');
        banner.className = 'cookie-banner';
        banner.id = 'cookie-banner';
        banner.setAttribute('role', 'dialog');
        banner.setAttribute('aria-label', 'Cookie consent');
        banner.innerHTML = `
            <div class="cookie-banner-text">
                <i class="fas fa-cookie-bite"></i>
                <p>We use cookies to improve your experience and analyse site traffic.
                   By clicking "Accept", you consent to our use of cookies as described in our
                   <a href="privacy-policy.html">Privacy Policy</a>.</p>
            </div>
            <div class="cookie-banner-actions">
                <button class="cookie-decline" id="cookie-decline">Decline</button>
                <button class="cookie-accept" id="cookie-accept">Accept All</button>
            </div>
        `;
        document.body.appendChild(banner);

        requestAnimationFrame(() => {
            setTimeout(() => banner.classList.add('show'), 700);
        });

        const dismissBanner = (choice) => {
            banner.style.transition = 'transform 0.4s ease';
            banner.style.transform = 'translateY(110%)';
            localStorage.setItem(COOKIE_KEY, choice);
            setTimeout(() => banner.remove(), 450);
        };

        document.getElementById('cookie-accept').addEventListener('click', () => dismissBanner('accepted'));
        document.getElementById('cookie-decline').addEventListener('click', () => dismissBanner('declined'));
    }

    /* ==========================================================================
       Prayer Strip / Widgets — Load real today's times from prayer-data.json
       Updates:  .prayer-strip-item[data-prayer] .prayer-strip-time
                 .pwc-item[data-prayer]           .pwc-time
                 .pwf-row[data-prayer]            .pwf-adhan / .pwf-iqamah
       ========================================================================== */
    const prayerStripItems = document.querySelectorAll('.prayer-strip-item, .pwc-item, .pwf-row');
    if (prayerStripItems.length > 0 && typeof emcData !== 'undefined') {
        // Derive JSON URL from theme URI
        const dataUrl = emcData.themeUri + '/assets/js/prayer-data.json';

        // Format "HH:MM" (24h) -> "H:MM AM/PM"
        function fmtT(str) {
            if (!str || str === '') return '—';
            const [hh, mm] = str.split(':').map(Number);
            const ampm = hh < 12 ? 'AM' : 'PM';
            const h12  = hh % 12 || 12;
            return `${h12}:${String(mm).padStart(2,'0')} ${ampm}`;
        }

        // Build today's key "DD/MM/YYYY"
        const now = new Date();
        const dd  = String(now.getDate()).padStart(2,'0');
        const mm  = String(now.getMonth()+1).padStart(2,'0');
        const todayKey = `${dd}/${mm}/${now.getFullYear()}`;

        fetch(dataUrl)
            .then(r => r.json())
            .then(data => {
                const entry = data.find(e => e.date === todayKey);
                if (!entry) return;

                const adhan  = entry.adhan;
                const iqamah = entry.iqamah;

                // Prayer strip (homepage section)
                document.querySelectorAll('.prayer-strip-item[data-prayer]').forEach(el => {
                    const key  = el.getAttribute('data-prayer');
                    const timeEl = el.querySelector('.prayer-strip-time');
                    if (timeEl && adhan[key]) timeEl.textContent = fmtT(adhan[key]);
                });

                // Compact widget
                document.querySelectorAll('.pwc-item[data-prayer]').forEach(el => {
                    const key  = el.getAttribute('data-prayer');
                    const timeEl = el.querySelector('.pwc-time');
                    if (timeEl && adhan[key]) timeEl.textContent = fmtT(adhan[key]);
                });

                // Full widget
                document.querySelectorAll('.pwf-row[data-prayer]').forEach(el => {
                    const key     = el.getAttribute('data-prayer');
                    const adhanEl = el.querySelector('.pwf-adhan');
                    const iqamaEl = el.querySelector('.pwf-iqamah');
                    if (adhanEl && adhan[key])  adhanEl.textContent  = fmtT(adhan[key]);
                    if (iqamaEl && iqamah[key]) iqamaEl.textContent  = fmtT(iqamah[key]);
                });
            })
            .catch(() => { /* silently fail — times remain as --:-- */ });
    }

});


