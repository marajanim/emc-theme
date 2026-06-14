/**
 * EMC Prayer Times Top Bar — prayer-topbar.js
 * Populates the fixed top bar with today's prayer times from prayer-data.json.
 */
document.addEventListener('DOMContentLoaded', () => {

    const PRAYERS = ['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'];

    function parseTime(str) {
        if (!str || !str.trim()) return null;
        const parts = str.trim().split(':');
        if (parts.length < 2) return null;
        return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }

    function fmt24(str) {
        if (!str || !str.trim()) return '--:--';
        return str.trim().substring(0, 5); // return HH:MM directly (24hr)
    }

    function dateKey(d) {
        const dd = String(d.getDate()).padStart(2, '0');
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        return `${dd}/${mm}/${d.getFullYear()}`;
    }

    const dataUrl = (typeof emcPrayer !== 'undefined' && emcPrayer.dataUrl)
        ? emcPrayer.dataUrl
        : '/wp-content/themes/emc-theme/assets/js/prayer-data.json';

    fetch(dataUrl)
        .then(r => r.json())
        .then(rawData => {
            const dataMap = {};
            rawData.forEach(e => { dataMap[e.date] = e; });

            const today = new Date();
            const key   = dateKey(today);
            const entry = dataMap[key];

            if (!entry) return;

            const adhan  = entry.adhan  || {};
            const iqamah = entry.iqamah || {};

            // ── Hijri date ──────────────────────────────────────────────────
            const hijriEl = document.getElementById('ptb-hijri');
            if (hijriEl && entry.hijri) {
                hijriEl.textContent = entry.hijri;
            }

            // ── Jumuah (only relevant on Fridays, but always show the time) ──
            const jumuahEl = document.getElementById('ptb-jumuah');
            if (jumuahEl) {
                const jTime = fmt24(adhan.jumuah);
                jumuahEl.textContent = jTime !== '--:--' ? jTime : '13:15';
                // Hide jumu'ah row on non-Fridays
                const wrap = document.getElementById('ptb-jumuah-wrap');
                if (wrap && today.getDay() !== 5) {
                    wrap.style.display = 'none';
                }
            }

            // ── Fill each prayer column ──────────────────────────────────────
            PRAYERS.forEach(prayer => {
                const adhanEl  = document.getElementById(`ptb-adhan-${prayer}`);
                const iqamahEl = document.getElementById(`ptb-iqamah-${prayer}`);
                if (adhanEl)  adhanEl.textContent  = fmt24(adhan[prayer]);
                if (iqamahEl) iqamahEl.textContent = fmt24(iqamah[prayer]);
            });

            // ── Highlight the next / current prayer column ───────────────────
            function highlightActive() {
                const now = new Date();
                const nowMins = now.getHours() * 60 + now.getMinutes();

                let nextKey = null;
                let minDiff = Infinity;

                PRAYERS.forEach(prayer => {
                    const mins = parseTime(adhan[prayer]);
                    if (mins === null) return;
                    const diff = mins - nowMins;
                    // Find the soonest upcoming prayer (diff > 0), or closest past
                    if (diff >= 0 && diff < minDiff) {
                        minDiff = diff;
                        nextKey = prayer;
                    }
                });

                // If all prayers have passed today, highlight Isha (last)
                if (!nextKey) nextKey = 'isha';

                document.querySelectorAll('.ptb-prayer-col').forEach(col => {
                    const p = col.getAttribute('data-prayer');
                    col.classList.toggle('ptb-active', p === nextKey);
                });
            }

            highlightActive();
            setInterval(highlightActive, 60000); // update every minute
        })
        .catch(err => console.warn('[EMC TopBar] Could not load prayer data:', err));
});
