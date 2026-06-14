<?php
/**
 * Template Part: Hero Section — Phase 4 dynamic version.
 * All text and CTAs are driven by the Customizer.
 * @package emc-theme
 */

$title      = emc_option( 'emc_hero_title',    __( 'Advancing Faith & Community', 'emc-theme' ) );
$subtitle   = emc_option( 'emc_hero_subtitle', __( 'Welcome to the Essex Muslim Centre. A hub for spiritual growth, education, and community welfare in the heart of Chelmsford.', 'emc-theme' ) );
$cta1_label = emc_option( 'emc_hero_cta1_label', __( 'Donate Now', 'emc-theme' ) );
$cta1_url   = emc_option( 'emc_hero_cta1_url', '' ) ?: ( get_permalink( get_page_by_path( 'donate' ) ) ?: home_url( '/donate/' ) );
$cta2_label = emc_option( 'emc_hero_cta2_label', __( 'Prayer Times', 'emc-theme' ) );
$cta2_url   = emc_option( 'emc_hero_cta2_url', '' ) ?: ( get_permalink( get_page_by_path( 'prayer-times' ) ) ?: home_url( '/prayer-times/' ) );

// Background image: custom upload takes priority, then bundled asset
$bg_image_id  = emc_option( 'emc_hero_bg_image', 0 );
$hero_img_url = $bg_image_id ? wp_get_attachment_image_url( $bg_image_id, 'emc-hero' ) : EMC_ASSETS . '/images/building-featured.jpg';
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Welcome', 'emc-theme' ); ?>">
    <div class="hero-bg-wrapper">
        <img
            src="<?php echo esc_url( $hero_img_url ); ?>"
            alt="<?php esc_attr_e( 'Essex Muslim Centre building', 'emc-theme' ); ?>"
            class="hero-img"
            loading="eager"
            fetchpriority="high"
        >
        <!-- Reflection of the building -->
        <img
            src="<?php echo esc_url( $hero_img_url ); ?>"
            alt=""
            class="hero-img-reflection"
            aria-hidden="true"
            loading="eager"
        >
        <div class="hero-reflection-ripple" aria-hidden="true"></div>
        <div class="hero-overlay" aria-hidden="true"></div>
        <div class="blob blob-1" aria-hidden="true"></div>
        <div class="blob blob-2" aria-hidden="true"></div>
    </div>

    <div class="container hero-container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $title ); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html( $subtitle ); ?></p>

            <div class="hero-actions">
                <div class="magnetic-btn">
                    <a href="<?php echo esc_url( $cta1_url ); ?>" class="btn btn-primary">
                        <?php echo esc_html( $cta1_label ); ?>
                    </a>
                </div>
                <div class="magnetic-btn">
                    <a href="<?php echo esc_url( $cta2_url ); ?>" class="btn btn-outline">
                        <i class="fas fa-clock" aria-hidden="true"></i>
                        <?php echo esc_html( $cta2_label ); ?>
                    </a>
                </div>
            </div>

            <!-- Quick Donation Card -->
            <?php
            $donate_heading  = emc_option( 'emc_hero_donate_heading',  __( 'Support Our Centre', 'emc-theme' ) );
            $donate_amounts  = array_map( 'trim', explode( ',', emc_option( 'emc_hero_donate_amounts', '5,10,25,50' ) ) );
            $donate_default  = emc_option( 'emc_hero_donate_default_amount', '25' );
            $donate_proceed  = emc_option( 'emc_hero_donate_proceed_label', __( 'Proceed Securely', 'emc-theme' ) );
            $donate_cats     = array_map( 'trim', explode( ',', emc_option( 'emc_hero_donate_categories', __( 'General Fund,Building Fund,Zakat,Sadaqah', 'emc-theme' ) ) ) );
            ?>
            <div class="quick-donate-card glass-card">
                <h3><?php echo esc_html( $donate_heading ); ?></h3>
                <div class="donate-amounts" role="group" aria-label="<?php esc_attr_e( 'Select donation amount', 'emc-theme' ); ?>">
                    <?php foreach ( $donate_amounts as $amt ) :
                        $is_default = ( trim( $amt ) === trim( $donate_default ) );
                    ?>
                    <button class="amount-btn<?php echo $is_default ? ' active' : ''; ?>" data-amount="<?php echo esc_attr( $amt ); ?>">
                        £<?php echo esc_html( $amt ); ?>
                    </button>
                    <?php endforeach; ?>
                    <button class="amount-btn custom" data-amount="custom"><?php esc_html_e( 'Other', 'emc-theme' ); ?></button>
                </div>
                <div class="custom-amount-row" id="custom-amount-row" aria-hidden="true">
                    <div class="custom-amount-input-wrap">
                        <span class="custom-amount-prefix" aria-hidden="true">£</span>
                        <input
                            type="number"
                            id="custom-amount-input"
                            class="custom-amount-input"
                            placeholder="<?php esc_attr_e( 'Enter amount', 'emc-theme' ); ?>"
                            min="1"
                            step="1"
                            aria-label="<?php esc_attr_e( 'Custom donation amount in pounds', 'emc-theme' ); ?>"
                        >
                    </div>
                    <p class="custom-amount-hint">
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                        <?php esc_html_e( 'Minimum donation £1', 'emc-theme' ); ?>
                    </p>
                </div>
                <form class="quick-donate-form" id="quick-donate-form">
                    <div class="form-group">
                        <label for="donate-category"><?php esc_html_e( 'Donation Category', 'emc-theme' ); ?></label>
                        <select name="category" id="donate-category" class="form-control">
                            <?php foreach ( $donate_cats as $cat ) : ?>
                            <option><?php echo esc_html( $cat ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" style="width:100%;margin-top:1rem;" id="quick-donate-proceed">
                        <?php echo esc_html( $donate_proceed ); ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Prayer Times Widget -->
        <div class="hero-widget" aria-label="<?php esc_attr_e( 'Daily prayer times', 'emc-theme' ); ?>">
            <div class="prayer-times-widget glass-card" id="hero-prayer-widget">
                <div class="widget-header">
                    <h2 style="color:var(--primary-green)">
                        <i class="fas fa-mosque" aria-hidden="true"></i>
                        <?php esc_html_e( 'Daily Salah', 'emc-theme' ); ?>
                    </h2>
                    <p style="color:var(--text-muted);font-size:var(--step--1)">
                        <?php echo esc_html( emc_option( 'emc_location', 'Chelmsford, Essex' ) ); ?>
                    </p>
                </div>
                <div class="countdown-timer">
                    <p style="font-size:var(--step--1);opacity:.9" id="next-prayer-label">
                        <?php esc_html_e( 'Next Prayer:', 'emc-theme' ); ?>
                    </p>
                    <div class="time-left" id="prayer-countdown" aria-live="polite">--:--:--</div>
                </div>
                <ul class="prayer-list" id="prayer-list">
                    <?php
                    foreach ( array( 'Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha' ) as $prayer ) :
                    ?>
                    <li class="prayer-row" data-prayer="<?php echo esc_attr( strtolower( $prayer ) ); ?>">
                        <span class="prayer-name"><?php echo esc_html( $prayer ); ?></span>
                        <span class="prayer-time adhan">--:--</span>
                        <span class="prayer-time iqama">--:--</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url( $cta2_url ); ?>" class="prayer-full-link" style="display:block;text-align:center;margin-top:1rem;font-size:var(--step--2);color:var(--primary-green);">
                    <?php esc_html_e( 'View full timetable', 'emc-theme' ); ?> →
                </a>
            </div>
        </div>
    </div>

    <!-- Shape Divider -->
    <div class="custom-shape-divider-bottom" aria-hidden="true">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,119.5,191.56,98.5,235.83,83.16,281.33,67.6,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
</section>
