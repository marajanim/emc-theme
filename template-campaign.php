<?php
/**
 * Template Name: Campaign
 * Template Post Type: page
 *
 * EMC Theme — Building Campaign page template.
 * Full campaign landing page with hero, progress, donor wall, and donate section.
 *
 * @package emc-theme
 */

get_header();

wp_enqueue_style( 'emc-page-campaign', EMC_ASSETS . '/css/campaign.css', array( 'emc-style' ), EMC_VERSION );

$campaign_js_path = EMC_DIR . '/assets/js/campaign.js';
if ( file_exists( $campaign_js_path ) ) {
    wp_enqueue_script( 'emc-page-campaign', EMC_ASSETS . '/js/campaign.js', array( 'emc-script' ), filemtime( $campaign_js_path ), true );
}

// Campaign data from Customizer
$badge      = emc_option( 'emc_campaign_badge',   __( 'Building Fund', 'emc-theme' ) );
$heading    = emc_option( 'emc_campaign_heading',  __( 'Be One of the 313', 'emc-theme' ) );
$desc       = emc_option( 'emc_campaign_desc',     __( 'Help us build a lasting place of worship for future generations. Our building campaign needs your generous support. Every pound brings us closer to our goal.', 'emc-theme' ) );
$raised     = (int) emc_option( 'emc_campaign_raised', 68400 );
$target     = (int) emc_option( 'emc_campaign_target', 100000 );
$donors     = (int) emc_option( 'emc_campaign_donors', 247 );
$cta_label  = emc_option( 'emc_campaign_cta_label', __( 'Donate to Campaign', 'emc-theme' ) );
$cta_url    = emc_option( 'emc_campaign_cta_url', '' ) ?: ( get_permalink( get_page_by_path( 'donate' ) ) ?: home_url( '/donate/' ) );
$percent    = $target > 0 ? min( 100, round( ( $raised / $target ) * 100 ) ) : 0;
?>

<!-- Campaign Hero -->
<section class="campaign-hero" aria-labelledby="campaign-hero-heading">
    <div class="container">
        <div class="campaign-hero-inner">

            <!-- Text Column -->
            <div class="campaign-hero-text">
                <span class="campaign-tag">
                    <i class="fas fa-star-and-crescent" aria-hidden="true"></i>
                    <?php echo esc_html( $badge ); ?>
                </span>
                <h1 id="campaign-hero-heading">
                    <?php echo esc_html( $heading ); ?>
                </h1>
                <p><?php echo esc_html( $desc ); ?></p>

                <!-- Progress Box -->
                <div class="campaign-progress-box">
                    <div class="progress-stat-row">
                        <div class="progress-stat">
                            <span class="stat-value">£<?php echo esc_html( number_format( $raised ) ); ?></span>
                            <span class="stat-label"><?php esc_html_e( 'Raised', 'emc-theme' ); ?></span>
                        </div>
                        <div class="progress-stat center">
                            <span class="stat-value"><?php echo esc_html( $percent ); ?>%</span>
                            <span class="stat-label"><?php esc_html_e( 'Funded', 'emc-theme' ); ?></span>
                        </div>
                        <div class="progress-stat right">
                            <span class="stat-value">£<?php echo esc_html( number_format( $target ) ); ?></span>
                            <span class="stat-label"><?php esc_html_e( 'Target', 'emc-theme' ); ?></span>
                        </div>
                    </div>
                    <div class="campaign-track">
                        <div class="campaign-fill" style="width: <?php echo esc_attr( $percent ); ?>%">
                            <span class="campaign-pulse"></span>
                        </div>
                    </div>
                    <div class="donor-count-row">
                        <i class="fas fa-users" aria-hidden="true"></i>
                        <?php printf(
                            esc_html__( '%s donors have contributed', 'emc-theme' ),
                            '<strong>' . esc_html( number_format( $donors ) ) . '</strong>'
                        ); ?>
                    </div>
                </div>

                <div class="campaign-cta-row" style="display:flex;gap:1rem;flex-wrap:wrap;">
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary">
                        <i class="fas fa-heart" aria-hidden="true"></i>
                        <?php echo esc_html( $cta_label ); ?>
                    </a>
                </div>
            </div>

            <!-- Visual Column -->
            <div class="campaign-hero-visual" aria-hidden="true">
                <div class="campaign-icon-tower">
                    <i class="fas fa-mosque"></i>
                    <div class="tower-rings">
                        <div class="tower-ring r1"></div>
                        <div class="tower-ring r2"></div>
                        <div class="tower-ring r3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donor Wall -->
<section class="donor-wall section-padding" aria-label="<?php esc_attr_e( 'Donor wall', 'emc-theme' ); ?>">
    <div class="container">
        <div class="text-center" style="margin-bottom:3rem;">
            <h2><?php esc_html_e( 'The 313 Wall of Honour', 'emc-theme' ); ?></h2>
            <p style="color:var(--text-muted);max-width:600px;margin:0 auto;">
                <?php esc_html_e( 'Join our growing community of supporters. Each slot represents one of the 313 founding donors.', 'emc-theme' ); ?>
            </p>
        </div>
        <div class="donor-slots-grid">
            <?php
            $filled_count = min( $donors, 313 );
            $empty_count  = 313 - $filled_count;

            // Show first 24 filled slots visually
            $display_filled = min( $filled_count, 24 );
            $display_empty  = min( $empty_count, 12 );

            for ( $i = 0; $i < $display_filled; $i++ ) :
            ?>
            <div class="donor-slot filled">
                <i class="fas fa-user-check" aria-hidden="true"></i>
                <span><?php printf( esc_html__( 'Donor #%d', 'emc-theme' ), $i + 1 ); ?></span>
            </div>
            <?php endfor; ?>

            <?php for ( $i = 0; $i < $display_empty; $i++ ) : ?>
            <a href="<?php echo esc_url( $cta_url ); ?>" class="donor-slot empty">
                <i class="fas fa-plus-circle" aria-hidden="true"></i>
                <span><?php esc_html_e( 'Be a donor', 'emc-theme' ); ?></span>
            </a>
            <?php endfor; ?>
        </div>
        <?php if ( $filled_count > $display_filled || $empty_count > $display_empty ) : ?>
        <p class="text-center" style="margin-top:2rem;color:var(--text-muted);font-size:var(--step--1);">
            <strong><?php echo esc_html( number_format( $filled_count ) ); ?></strong>
            <?php printf( esc_html__( 'of 313 slots filled — %d remaining', 'emc-theme' ), 313 - $filled_count ); ?>
        </p>
        <?php endif; ?>
    </div>
</section>

<!-- Campaign Donate Section -->
<section class="section-padding" style="background:var(--light-bg);" aria-label="<?php esc_attr_e( 'Campaign donation', 'emc-theme' ); ?>">
    <div class="container">
        <div class="campaign-donate-layout">

            <!-- Left: Donate Card -->
            <div>
                <div class="form-card glass-card">
                    <h3><?php esc_html_e( 'Support the Building Campaign', 'emc-theme' ); ?></h3>
                    <p class="form-desc"><?php esc_html_e( 'Choose an amount to contribute towards our new centre.', 'emc-theme' ); ?></p>
                    <div class="amount-grid" style="grid-template-columns: repeat(4, 1fr);">
                        <button class="amount-btn">£25</button>
                        <button class="amount-btn">£50</button>
                        <button class="amount-btn active">£100</button>
                        <button class="amount-btn">£313</button>
                    </div>

                    <div class="monthly-313-badge">
                        <i class="fas fa-award" aria-hidden="true"></i>
                        <div>
                            <strong><?php esc_html_e( 'Become a "313 Founding Donor"', 'emc-theme' ); ?></strong>
                            <p style="margin:0;color:var(--text-muted);font-size:var(--step--2);">
                                <?php esc_html_e( 'Donate £313 or set up a monthly pledge to be honoured on our permanent donor wall.', 'emc-theme' ); ?>
                            </p>
                        </div>
                    </div>

                    <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary donate-submit">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <?php echo esc_html( $cta_label ); ?>
                    </a>
                    <p class="secure-note" style="text-align:center;margin-top:1rem;font-size:var(--step--2);color:var(--text-muted);">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <?php esc_html_e( 'Encrypted & secured by Stripe. 100% of your donation goes to the building fund.', 'emc-theme' ); ?>
                    </p>
                </div>
            </div>

            <!-- Right: Why It Matters -->
            <div>
                <div class="campaign-why-card glass-card">
                    <h4><i class="fas fa-mosque" aria-hidden="true"></i> <?php esc_html_e( 'Why This Matters', 'emc-theme' ); ?></h4>
                    <div class="why-list">
                        <div class="why-item">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Purpose-Built Centre', 'emc-theme' ); ?></strong>
                                <p><?php esc_html_e( 'A permanent, purpose-built centre for Chelmsford\'s Muslim community.', 'emc-theme' ); ?></p>
                            </div>
                        </div>
                        <div class="why-item">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Capacity for 500+', 'emc-theme' ); ?></strong>
                                <p><?php esc_html_e( 'Space for over 500 worshippers during Jumu\'ah prayers.', 'emc-theme' ); ?></p>
                            </div>
                        </div>
                        <div class="why-item">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Community Wings', 'emc-theme' ); ?></strong>
                                <p><?php esc_html_e( 'Dedicated youth, women\'s, and education wings for all age groups.', 'emc-theme' ); ?></p>
                            </div>
                        </div>
                        <div class="why-item">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Gift Aid Eligible', 'emc-theme' ); ?></strong>
                                <p><?php esc_html_e( 'All donations qualify for Gift Aid, adding 25% at no extra cost to you.', 'emc-theme' ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// If the page has additional editor content, render it below
$content = get_the_content();
if ( $content && trim( strip_tags( $content ) ) ) :
?>
<section class="section-padding">
    <div class="container">
        <div class="entry-content prose" style="max-width:800px;margin:0 auto;">
            <?php the_content(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
