/**
 * EMC Theme — assets/js/wizard.js
 * Phase 11: Setup Wizard AJAX interactions.
 *
 * @package emc-theme
 */

(function ($) {
    'use strict';

    const {
        ajaxUrl,
        nonce,
        siteUrl,
        customizer,
        imported,
        i18n,
    } = window.emcWizard || {};

    /* ── Run Import ───────────────────────────────────────────────────────── */
    $('#emc-run-import').on('click', function () {
        const $btn          = $(this);
        const $progress     = $('#emc-import-progress');
        const $logList      = $('#emc-log-list');
        const $title        = $('#emc-progress-title');
        const $doneSection  = $('#emc-import-done');

        // Disable button and show progress panel
        $btn.prop('disabled', true).html(
            '<span class="emc-spinner" style="border-top-color:#fff;"></span> ' + (i18n.running || 'Importing…')
        );
        $progress.slideDown(300);

        // Clear previous log
        $logList.empty();
        $doneSection.hide();

        // Animate log appearance — fake step progression for UX
        const fakeSteps = [
            'Connecting to database…',
            'Preparing pages…',
            'Building navigation menus…',
            'Applying theme settings…',
            'Assigning homepage…',
            'Flushing rewrite rules…',
        ];
        let stepIdx = 0;
        const fakeInterval = setInterval(() => {
            if (stepIdx < fakeSteps.length) {
                appendLog(fakeSteps[stepIdx], 'pending', '');
                stepIdx++;
            } else {
                clearInterval(fakeInterval);
            }
        }, 180);

        // Run actual AJAX import
        $.ajax({
            url:    ajaxUrl,
            method: 'POST',
            data: {
                action: 'emc_demo_import_run',
                nonce:  nonce,
            },
            success(res) {
                clearInterval(fakeInterval);
                $logList.empty(); // Replace fake log with real log

                if (res.success && res.data && res.data.log) {
                    res.data.log.forEach(entry => {
                        appendLog(entry.step, entry.status, entry.detail);
                    });

                    $title.text(i18n.done || 'Import Complete!');
                    $('.emc-spinner').hide();
                    $doneSection.slideDown(300);

                    // Reload page after short delay to show success card
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    $title.text(i18n.error || 'Something went wrong.');
                    $btn.prop('disabled', false).html('⚡ Re-run Import');
                    const msg = (res.data && res.data.message) ? res.data.message : JSON.stringify(res);
                    appendLog('Error', 'error', msg);
                }
            },
            error(xhr, status, err) {
                clearInterval(fakeInterval);
                $title.text(i18n.error || 'Something went wrong.');
                $btn.prop('disabled', false).html('⚡ Re-run Import');
                appendLog('AJAX error', 'error', err);
            },
        });
    });

    /* ── Reset Import ─────────────────────────────────────────────────────── */
    $('#emc-reset-import').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true).text('Resetting…');

        $.ajax({
            url:    ajaxUrl,
            method: 'POST',
            data: {
                action: 'emc_demo_import_reset',
                nonce:  nonce,
            },
            success() {
                window.location.reload();
            },
            error() {
                $btn.prop('disabled', false).text('Reset import flag');
            },
        });
    });

    /* ── Helper: Append log item ──────────────────────────────────────────── */
    function appendLog(step, status, detail) {
        const icon = {
            ok:      '✓',
            skip:    '→',
            error:   '✗',
            pending: '…',
        }[status] || '·';

        const statusClass = `emc-log-status-${status === 'pending' ? 'skip' : status}`;

        const $item = $('<li>')
            .addClass('emc-log-item')
            .append(
                $('<span>').addClass(statusClass).text(icon)
            )
            .append(
                $('<span>').addClass('emc-log-step').text(step)
            );

        if (detail) {
            $item.append(
                $('<span>').addClass('emc-log-detail').text(detail)
            );
        }

        $('#emc-log-list').append($item);

        // Scroll log to bottom
        const $wrap = $('#emc-log-wrap');
        $wrap.scrollTop($wrap[0].scrollHeight);
    }

}(jQuery));
