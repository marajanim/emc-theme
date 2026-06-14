<?php
/**
 * Template Name: Donate
 * Template Post Type: page
 *
 * EMC Theme — Donate page template.
 * Hero, impact stats, and campaign sidebar editable via ACF.
 *
 * @package emc-theme
 */

get_header();

wp_enqueue_style( 'emc-page-donate', EMC_ASSETS . '/css/donate.css', array( 'emc-style' ), EMC_VERSION );

$donate_js_path = EMC_DIR . '/assets/js/donate.js';
if ( file_exists( $donate_js_path ) ) {
    wp_enqueue_script( 'emc-page-donate', EMC_ASSETS . '/js/donate.js', array( 'emc-script' ), filemtime( $donate_js_path ), true );
}
?>

<!-- Page Hero -->
<section class="page-hero donate-hero">
    <div class="container">
        <div class="page-hero-content">
            <span class="badge"><i class="fas fa-heart"></i> <?php echo esc_html( emc_acf( 'donate_hero_badge', __( 'Make a Difference', 'emc-theme' ) ) ); ?></span>
            <h1><?php echo esc_html( emc_acf( 'donate_hero_title', __( 'Support Our Centre', 'emc-theme' ) ) ); ?></h1>
            <p><?php echo esc_html( emc_acf( 'donate_hero_desc', __( 'Your generosity funds Friday prayers, youth programmes, reversion support, and vital community welfare in Chelmsford.', 'emc-theme' ) ) ); ?></p>
            <div class="trust-signals">
                <span><i class="fas fa-shield-alt"></i> <?php echo esc_html( emc_acf( 'donate_trust_stripe', 'Secured by Stripe' ) ); ?></span>
                <span><i class="fas fa-certificate"></i> <?php printf( esc_html__( 'Charity No. %s', 'emc-theme' ), esc_html( emc_option( 'emc_charity_number', '1209815' ) ) ); ?></span>
                <span><i class="fas fa-check-circle"></i> <?php echo esc_html( emc_acf( 'donate_trust_giftaid', 'Gift Aid Eligible' ) ); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- Donation Tabs -->
<section class="donate-section">
    <div class="container">
        <div class="donate-layout">

            <!-- Left: Donation Form -->
            <div class="donate-form-col">
                <div class="tab-nav">
                    <button class="tab-btn active" data-tab="one-off"><i class="fas fa-hand-holding-heart"></i> <?php echo esc_html( emc_acf( 'donate_tab_oneoff', 'One-Off' ) ); ?></button>
                    <button class="tab-btn" data-tab="regular"><i class="fas fa-sync-alt"></i> <?php echo esc_html( emc_acf( 'donate_tab_regular', 'Regular' ) ); ?></button>
                    <button class="tab-btn" data-tab="ramadan-tab"><i class="fas fa-moon"></i> <?php echo esc_html( emc_acf( 'donate_tab_ramadan', 'Ramadan' ) ); ?></button>
                    <button class="tab-btn" data-tab="zakat"><i class="fas fa-calculator"></i> <?php echo esc_html( emc_acf( 'donate_tab_zakat', 'Zakat' ) ); ?></button>
                </div>

                <!-- ONE-OFF TAB -->
                <div class="tab-panel active" id="tab-one-off">
                    <div class="form-card glass-card">
                        <h3><?php echo esc_html( emc_acf( 'donate_oneoff_heading', 'One-Off Donation' ) ); ?></h3>
                        <p class="form-desc"><?php echo esc_html( emc_acf( 'donate_oneoff_desc', 'Every amount makes a real difference to our community.' ) ); ?></p>
                        <div class="amount-grid">
                            <button class="amount-btn">£5</button>
                            <button class="amount-btn">£10</button>
                            <button class="amount-btn active">£25</button>
                            <button class="amount-btn">£50</button>
                            <button class="amount-btn">£100</button>
                            <button class="amount-btn custom-other"><?php esc_html_e( 'Other', 'emc-theme' ); ?></button>
                        </div>
                        <div class="custom-amount-wrapper" id="custom-amount-wrapper" style="display:none;">
                            <label><?php esc_html_e( 'Enter Amount (£)', 'emc-theme' ); ?></label>
                            <div class="input-prefix-wrap"><span class="input-prefix">£</span><input type="number" id="custom-amount-input" class="form-control" placeholder="0.00" min="1"></div>
                        </div>
                        <div class="form-group">
                            <label><?php echo esc_html( emc_acf( 'donate_fund_label', 'Donation Fund' ) ); ?></label>
                            <div class="category-grid">
                                <button class="cat-btn active" data-cat="General Fund"><i class="fas fa-mosque"></i> <?php echo esc_html( emc_acf( 'donate_fund_general', 'General Fund' ) ); ?></button>
                                <button class="cat-btn" data-cat="Building Fund"><i class="fas fa-building"></i> <?php echo esc_html( emc_acf( 'donate_fund_building', 'Building Fund' ) ); ?></button>
                                <button class="cat-btn" data-cat="Education"><i class="fas fa-book-open"></i> <?php echo esc_html( emc_acf( 'donate_fund_education', 'Education' ) ); ?></button>
                                <button class="cat-btn" data-cat="Zakat"><i class="fas fa-hand-holding-usd"></i> <?php echo esc_html( emc_acf( 'donate_fund_zakat', 'Zakat' ) ); ?></button>
                                <button class="cat-btn" data-cat="Sadaqah"><i class="fas fa-heart"></i> <?php echo esc_html( emc_acf( 'donate_fund_sadaqah', 'Sadaqah' ) ); ?></button>
                                <button class="cat-btn" data-cat="Lillah"><i class="fas fa-star-and-crescent"></i> <?php echo esc_html( emc_acf( 'donate_fund_lillah', 'Lillah' ) ); ?></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="donor-message"><?php esc_html_e( 'Personal Message', 'emc-theme' ); ?> <span style="font-weight:400; color:var(--text-muted)">(<?php esc_html_e( 'Optional', 'emc-theme' ); ?>)</span></label>
                            <textarea id="donor-message" class="form-control" rows="3" placeholder="<?php esc_attr_e( 'Add a personal message or dedication...', 'emc-theme' ); ?>"></textarea>
                        </div>
                        <div class="gift-aid-box">
                            <label class="gift-aid-label">
                                <input type="checkbox" id="gift-aid-one" class="gift-aid-check">
                                <div class="gift-aid-content">
                                    <strong><?php echo esc_html( emc_acf( 'donate_giftaid_heading', 'Claim Gift Aid' ) ); ?></strong>
                                    <p><?php echo esc_html( emc_acf( 'donate_giftaid_text', 'I am a UK taxpayer and understand that if I pay less Income Tax / Capital Gains Tax than the amount of Gift Aid claimed on all my donations, it is my responsibility to pay any difference. EMC can reclaim 25p of tax on every £1 I give.' ) ); ?></p>
                                </div>
                            </label>
                        </div>
                        <button class="btn btn-primary donate-submit"><i class="fas fa-lock"></i> <?php echo esc_html( emc_acf( 'donate_oneoff_btn', 'Donate Securely' ) ); ?></button>
                        <p class="secure-note"><i class="fas fa-lock"></i> <?php echo esc_html( emc_acf( 'donate_secure_note', 'Encrypted & secured by Stripe. Your card details are never stored on our servers.' ) ); ?></p>
                    </div>
                </div>

                <!-- REGULAR TAB -->
                <div class="tab-panel" id="tab-regular">
                    <div class="form-card glass-card">
                        <h3><?php echo esc_html( emc_acf( 'donate_regular_heading', 'Regular Donation' ) ); ?></h3>
                        <p class="form-desc"><?php echo esc_html( emc_acf( 'donate_regular_desc', 'Set up a recurring gift to provide ongoing support to the community.' ) ); ?></p>
                        <div class="amount-grid">
                            <button class="amount-btn">£5</button><button class="amount-btn">£10</button><button class="amount-btn active">£20</button><button class="amount-btn">£50</button>
                            <button class="amount-btn custom-other"><?php esc_html_e( 'Other', 'emc-theme' ); ?></button>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label><?php esc_html_e( 'Frequency', 'emc-theme' ); ?></label><select class="form-control"><option><?php esc_html_e( 'Monthly', 'emc-theme' ); ?></option><option><?php esc_html_e( 'Weekly', 'emc-theme' ); ?></option><option><?php esc_html_e( 'Quarterly', 'emc-theme' ); ?></option><option><?php esc_html_e( 'Annually', 'emc-theme' ); ?></option></select></div>
                            <div class="form-group"><label><?php esc_html_e( 'Start Date', 'emc-theme' ); ?></label><input type="date" class="form-control"></div>
                        </div>
                        <div class="form-group"><label><?php esc_html_e( 'Donation Fund', 'emc-theme' ); ?></label><select class="form-control"><option><?php esc_html_e( 'General Fund', 'emc-theme' ); ?></option><option><?php esc_html_e( 'Building Fund', 'emc-theme' ); ?></option><option><?php esc_html_e( 'Education', 'emc-theme' ); ?></option><option><?php esc_html_e( 'Sadaqah', 'emc-theme' ); ?></option></select></div>
                        <div class="gift-aid-box"><label class="gift-aid-label"><input type="checkbox" id="gift-aid-regular" class="gift-aid-check"><div class="gift-aid-content"><strong><?php echo esc_html( emc_acf( 'donate_giftaid_heading', 'Claim Gift Aid' ) ); ?></strong><p><?php echo esc_html( emc_acf( 'donate_regular_giftaid_text', 'I am a UK taxpayer. EMC can reclaim 25p of tax on every £1 I give at no extra cost to me.' ) ); ?></p></div></label></div>
                        <button class="btn btn-primary donate-submit"><i class="fas fa-sync-alt"></i> <?php echo esc_html( emc_acf( 'donate_regular_btn', 'Set Up Monthly Giving' ) ); ?></button>
                        <?php
                        $portal_url  = emc_acf( 'donate_portal_url', '#' );
                        $portal_text = emc_acf( 'donate_portal_text', 'Already a regular donor? Access your Donor Portal to view, pause, or cancel your giving schedule.' );
                        ?>
                        <div class="donor-portal-box"><i class="fas fa-user-circle"></i><p><?php echo wp_kses( $portal_text, array( 'a' => array( 'href' => true ) ) ); ?></p></div>
                    </div>
                </div>

                <!-- RAMADAN TAB -->
                <div class="tab-panel" id="tab-ramadan-tab">
                    <div class="form-card glass-card ramadan-card">
                        <div class="ramadan-header">
                            <span class="ramadan-badge"><i class="fas fa-moon"></i> <?php echo esc_html( emc_acf( 'donate_ramadan_badge', 'Ramadan 1447 AH' ) ); ?></span>
                            <h3><?php echo esc_html( emc_acf( 'donate_ramadan_heading', 'Ramadan Daily Giving' ) ); ?></h3>
                            <p class="form-desc"><?php echo esc_html( emc_acf( 'donate_ramadan_desc', 'Schedule daily automatic donations for the blessed month of Ramadan.' ) ); ?></p>
                        </div>
                        <div class="ramadan-countdown"><p><?php esc_html_e( 'Next Ramadan begins in', 'emc-theme' ); ?></p><div class="countdown-grid"><div class="countdown-cell"><span id="r-days">285</span><small><?php esc_html_e( 'Days', 'emc-theme' ); ?></small></div><div class="countdown-cell"><span id="r-hours">14</span><small><?php esc_html_e( 'Hours', 'emc-theme' ); ?></small></div><div class="countdown-cell"><span id="r-mins">32</span><small><?php esc_html_e( 'Mins', 'emc-theme' ); ?></small></div></div></div>
                        <div class="form-group"><label><?php esc_html_e( 'Daily Amount', 'emc-theme' ); ?></label><div class="amount-grid"><button class="amount-btn">£1</button><button class="amount-btn active">£3</button><button class="amount-btn">£5</button><button class="amount-btn">£10</button><button class="amount-btn custom-other"><?php esc_html_e( 'Other', 'emc-theme' ); ?></button></div></div>
                        <div class="form-group"><label><?php esc_html_e( 'Giving Period', 'emc-theme' ); ?></label><div class="giving-period-grid"><label class="period-option"><input type="radio" name="period" value="full" checked><div class="period-card"><i class="fas fa-calendar-check"></i><strong><?php echo esc_html( emc_acf( 'donate_ramadan_period_full', 'Full Ramadan' ) ); ?></strong><small><?php esc_html_e( '29–30 days', 'emc-theme' ); ?></small></div></label><label class="period-option"><input type="radio" name="period" value="last10"><div class="period-card"><i class="fas fa-star"></i><strong><?php echo esc_html( emc_acf( 'donate_ramadan_period_10', 'Last 10 Nights' ) ); ?></strong><small><?php esc_html_e( 'Final 10 days', 'emc-theme' ); ?></small></div></label><label class="period-option"><input type="radio" name="period" value="odd"><div class="period-card"><i class="fas fa-moon"></i><strong><?php echo esc_html( emc_acf( 'donate_ramadan_period_odd', 'Odd Nights' ) ); ?></strong><small><?php esc_html_e( 'Nights 21,23,25,27,29', 'emc-theme' ); ?></small></div></label></div></div>
                        <div class="schedule-summary"><p><?php esc_html_e( 'Your total scheduled giving:', 'emc-theme' ); ?></p><div class="total-amount">£90 <span>(30 × £3)</span></div></div>
                        <button class="btn btn-primary donate-submit" style="background: linear-gradient(135deg, #2C3E7A, #7A3C2C);"><i class="fas fa-moon"></i> <?php echo esc_html( emc_acf( 'donate_ramadan_btn', 'Schedule Ramadan Giving' ) ); ?></button>
                    </div>
                </div>
                        <div class="ramadan-countdown"><p><?php esc_html_e( 'Next Ramadan begins in', 'emc-theme' ); ?></p><div class="countdown-grid"><div class="countdown-cell"><span id="r-days">285</span><small><?php esc_html_e( 'Days', 'emc-theme' ); ?></small></div><div class="countdown-cell"><span id="r-hours">14</span><small><?php esc_html_e( 'Hours', 'emc-theme' ); ?></small></div><div class="countdown-cell"><span id="r-mins">32</span><small><?php esc_html_e( 'Mins', 'emc-theme' ); ?></small></div></div></div>
                        <div class="form-group"><label><?php esc_html_e( 'Daily Amount', 'emc-theme' ); ?></label><div class="amount-grid"><button class="amount-btn">£1</button><button class="amount-btn active">£3</button><button class="amount-btn">£5</button><button class="amount-btn">£10</button><button class="amount-btn custom-other"><?php esc_html_e( 'Other', 'emc-theme' ); ?></button></div></div>
                        <div class="form-group"><label><?php esc_html_e( 'Giving Period', 'emc-theme' ); ?></label><div class="giving-period-grid"><label class="period-option"><input type="radio" name="period" value="full" checked><div class="period-card"><i class="fas fa-calendar-check"></i><strong><?php esc_html_e( 'Full Ramadan', 'emc-theme' ); ?></strong><small><?php esc_html_e( '29–30 days', 'emc-theme' ); ?></small></div></label><label class="period-option"><input type="radio" name="period" value="last10"><div class="period-card"><i class="fas fa-star"></i><strong><?php esc_html_e( 'Last 10 Nights', 'emc-theme' ); ?></strong><small><?php esc_html_e( 'Final 10 days', 'emc-theme' ); ?></small></div></label><label class="period-option"><input type="radio" name="period" value="odd"><div class="period-card"><i class="fas fa-moon"></i><strong><?php esc_html_e( 'Odd Nights', 'emc-theme' ); ?></strong><small><?php esc_html_e( 'Nights 21,23,25,27,29', 'emc-theme' ); ?></small></div></label></div></div>
                        <div class="schedule-summary"><p><?php esc_html_e( 'Your total scheduled giving:', 'emc-theme' ); ?></p><div class="total-amount">£90 <span>(30 × £3)</span></div></div>
                        <button class="btn btn-primary donate-submit" style="background: linear-gradient(135deg, #2C3E7A, #7A3C2C);"><i class="fas fa-moon"></i> <?php esc_html_e( 'Schedule Ramadan Giving', 'emc-theme' ); ?></button>
                    </div>
                </div>

                <!-- ZAKAT TAB -->
                <div class="tab-panel" id="tab-zakat">
                    <div class="form-card glass-card">
                        <h3><?php echo esc_html( emc_acf( 'donate_zakat_heading', 'Zakat Calculator' ) ); ?></h3>
                        <p class="form-desc"><?php echo esc_html( emc_acf( 'donate_zakat_nisab', 'Nisab (Silver): £452.06 | Zakat rate: 2.5%' ) ); ?></p>
                        <div class="zakat-form">
                            <?php
                            $zakat_fields = array(
                                array( 'id' => 'z-cash',     'key' => 'donate_zakat_label_cash',   'default' => 'Cash & Bank Savings (£)' ),
                                array( 'id' => 'z-gold',     'key' => 'donate_zakat_label_gold',   'default' => 'Gold & Silver Value (£)' ),
                                array( 'id' => 'z-business', 'key' => 'donate_zakat_label_biz',    'default' => 'Business / Trade Assets (£)' ),
                                array( 'id' => 'z-owed',     'key' => 'donate_zakat_label_owed',   'default' => 'Money Owed to You (£)' ),
                                array( 'id' => 'z-deduct',   'key' => 'donate_zakat_label_deduct', 'default' => 'Money You Owe (£) — Deduct' ),
                            );
                            foreach ( $zakat_fields as $zf ) : ?>
                            <div class="form-group"><label><?php echo esc_html( emc_acf( $zf['key'], $zf['default'] ) ); ?></label><div class="input-prefix-wrap"><span class="input-prefix">£</span><input type="number" class="form-control zakat-input" id="<?php echo esc_attr( $zf['id'] ); ?>" placeholder="0.00"></div></div>
                            <?php endforeach; ?>
                            <div class="zakat-result" id="zakat-result"><div class="zakat-result-inner"><p><?php echo esc_html( emc_acf( 'donate_zakat_result_label', 'Your Estimated Zakat' ) ); ?></p><div class="zakat-amount" id="zakat-amount">£0.00</div><p class="zakat-note" id="zakat-note"><?php esc_html_e( 'Enter your assets above to calculate.', 'emc-theme' ); ?></p></div></div>
                            <button class="btn btn-primary donate-submit" id="donate-zakat-btn" style="display:none;"><i class="fas fa-hand-holding-usd"></i> <?php echo esc_html( emc_acf( 'donate_zakat_btn', 'Donate My Zakat' ) ); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar -->
            <div class="donate-sidebar">
                <div class="sidebar-card glass-card">
                    <h4><i class="fas fa-chart-line"></i> <?php echo esc_html( emc_acf( 'donate_impact_heading', 'Your Impact' ) ); ?></h4>
                    <div class="impact-stats">
                        <?php
                        for ( $i = 1; $i <= 4; $i++ ) :
                            $amount = emc_acf( 'donate_impact_' . $i . '_amount', '' );
                            $desc   = emc_acf( 'donate_impact_' . $i . '_desc',   '' );
                            if ( empty( $amount ) ) continue;
                        ?>
                        <div class="impact-item">
                            <span class="impact-amount"><?php echo esc_html( $amount ); ?></span>
                            <span><?php echo esc_html( $desc ); ?></span>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="sidebar-card glass-card campaign-sidebar">
                    <h4><i class="fas fa-mosque"></i> <?php echo esc_html( emc_acf( 'donate_campaign_sidebar_heading', 'Building Campaign' ) ); ?></h4>
                    <p class="campaign-name">"<?php echo esc_html( emc_acf( 'donate_campaign_name', 'Be One of the 313' ) ); ?>"</p>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar-track">
                            <div class="progress-bar-fill" style="width: <?php echo esc_attr( emc_acf( 'donate_campaign_percent', '63' ) ); ?>%"></div>
                        </div>
                        <div class="progress-labels">
                            <span><?php echo esc_html( emc_acf( 'donate_campaign_raised', '£62,500' ) ); ?> raised</span>
                            <span>Goal: <?php echo esc_html( emc_acf( 'donate_campaign_goal', '£100,000' ) ); ?></span>
                        </div>
                    </div>
                    <?php
                    $campaign_page = get_page_by_path( 'campaign' );
                    $campaign_url  = $campaign_page ? get_permalink( $campaign_page ) : home_url( '/campaign/' );
                    ?>
                    <a href="<?php echo esc_url( $campaign_url ); ?>" class="btn btn-outline" style="width:100%; margin-top:1rem; justify-content:center;"><?php echo esc_html( emc_acf( 'donate_campaign_btn', 'View Campaign' ) ); ?></a>
                </div>

                <div class="sidebar-card trust-card">
                    <div class="trust-badge-grid">
                        <div class="trust-item"><i class="fab fa-stripe"></i> <?php echo esc_html( emc_acf( 'donate_trust_badge_stripe', 'Stripe Secured' ) ); ?></div>
                        <div class="trust-item"><i class="fas fa-user-shield"></i> <?php echo esc_html( emc_acf( 'donate_trust_badge_gdpr', 'GDPR Compliant' ) ); ?></div>
                        <div class="trust-item"><i class="fas fa-hand-holding-heart"></i> <?php echo esc_html( emc_acf( 'donate_trust_badge_ga', 'Gift Aid Registered' ) ); ?></div>
                        <div class="trust-item"><i class="fas fa-certificate"></i> <?php printf( esc_html__( 'Charity No. %s', 'emc-theme' ), esc_html( emc_option( 'emc_charity_number', '1209815' ) ) ); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
