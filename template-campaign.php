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
$bank_pay_url = 'https://paymentrequest.natwestpayit.com/reusable-link/39ee348b-8fe1-41fe-aa6b-9109dc847445';
$phone_digits = preg_replace( '/\D+/', '', emc_option( 'emc_phone', '' ) );
$pledge_text  = rawurlencode( 'Assalamu alaikum, I would like to pledge towards the Badr Wall building fund.' );
$whatsapp_url = $phone_digits ? 'https://wa.me/' . $phone_digits . '?text=' . $pledge_text : ( get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact/' ) );
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

<!-- Badr Wall — Tiered Donor Recognition -->
<section class="donor-wall section-padding" aria-label="<?php esc_attr_e( 'Badr Wall of Honour', 'emc-theme' ); ?>">
    <div class="container">
        <div class="text-center" style="margin-bottom:1.5rem;">
            <span class="campaign-tag" style="margin-bottom:1rem;"><i class="fas fa-star-and-crescent"></i> <?php esc_html_e( 'The Badr Wall', 'emc-theme' ); ?></span>
            <h2><?php esc_html_e( 'Wall of Honour', 'emc-theme' ); ?></h2>
            <p style="color:rgba(255,255,255,0.65);max-width:600px;margin:0 auto;">
                <?php esc_html_e( 'Inspired by the 313 companions of Badr — join our founding donors and be honoured permanently on this wall.', 'emc-theme' ); ?>
            </p>
        </div>

        <!-- Tier Legend -->
        <div class="badr-tier-legend">
            <div class="tier-legend-item tier-founder">
                <i class="fas fa-crown"></i>
                <div>
                    <strong><?php esc_html_e( 'Founder of the Mosque', 'emc-theme' ); ?></strong>
                    <span><?php esc_html_e( '£1,000+ or £313/month pledge', 'emc-theme' ); ?></span>
                </div>
            </div>
            <div class="tier-legend-item tier-cofunder">
                <i class="fas fa-award"></i>
                <div>
                    <strong><?php esc_html_e( 'Co-Founder of the Mosque', 'emc-theme' ); ?></strong>
                    <span><?php esc_html_e( '£313 – £999 contribution', 'emc-theme' ); ?></span>
                </div>
            </div>
            <div class="tier-legend-item tier-supporter">
                <i class="fas fa-medal"></i>
                <div>
                    <strong><?php esc_html_e( 'Supporter', 'emc-theme' ); ?></strong>
                    <span><?php esc_html_e( '£100 – £312 contribution', 'emc-theme' ); ?></span>
                </div>
            </div>
            <div class="tier-legend-item tier-friend">
                <i class="fas fa-heart"></i>
                <div>
                    <strong><?php esc_html_e( 'Friend of the Mosque', 'emc-theme' ); ?></strong>
                    <span><?php esc_html_e( 'Any generous contribution', 'emc-theme' ); ?></span>
                </div>
            </div>
        </div>

        <div class="badr-payment-options">
            <?php
            $badr_options = array(
                array( 'title' => 'Founder of the Mosque', 'amount' => '1000', 'desc' => 'One-off £1,000+ gift or £313 monthly pledge.' ),
                array( 'title' => 'Co-Founder of the Mosque', 'amount' => '313', 'desc' => 'A £313-£999 contribution towards the Badr Wall.' ),
                array( 'title' => 'Supporter', 'amount' => '100', 'desc' => 'A £100-£312 contribution to secure a supporter place.' ),
            );
            foreach ( $badr_options as $option ) :
                $stripe_link = add_query_arg(
                    array(
                        'fund'   => 'Building Fund',
                        'amount' => $option['amount'],
                    ),
                    $cta_url
                );
            ?>
            <div class="badr-payment-card">
                <h3><?php echo esc_html( $option['title'] ); ?></h3>
                <p><?php echo esc_html( $option['desc'] ); ?></p>
                <div class="badr-payment-actions">
                    <a href="<?php echo esc_url( $stripe_link ); ?>" class="btn btn-primary">
                        <i class="fas fa-credit-card" aria-hidden="true"></i>
                        <?php esc_html_e( 'Pay by Card', 'emc-theme' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $bank_pay_url ); ?>" class="btn btn-outline" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-university" aria-hidden="true"></i>
                        <?php esc_html_e( 'Pay by Bank', 'emc-theme' ); ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="badr-payment-card badr-pledge-card">
                <h3><?php esc_html_e( 'Need Instalments?', 'emc-theme' ); ?></h3>
                <p><?php esc_html_e( 'Speak to the team about a payment schedule or pledge before paying.', 'emc-theme' ); ?></p>
                <a href="<?php echo esc_url( $whatsapp_url ); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                    <?php esc_html_e( 'WhatsApp to Pledge', 'emc-theme' ); ?>
                </a>
            </div>
        </div>

        <?php
        $tiers = array(
            array(
                'id'       => 'founder',
                'label'    => __( 'Founder of the Mosque', 'emc-theme' ),
                'icon'     => 'fas fa-crown',
                'class'    => 'tier-founder',
                'total'    => 10,
                'filled'   => min( (int) emc_option( 'emc_campaign_tier1_filled', 2 ), 10 ),
                'desc'     => __( '£1,000+ — 10 founding places', 'emc-theme' ),
            ),
            array(
                'id'       => 'cofunder',
                'label'    => __( 'Co-Founder of the Mosque', 'emc-theme' ),
                'icon'     => 'fas fa-award',
                'class'    => 'tier-cofunder',
                'total'    => 30,
                'filled'   => min( (int) emc_option( 'emc_campaign_tier2_filled', 8 ), 30 ),
                'desc'     => __( '£313–£999 — 30 places', 'emc-theme' ),
            ),
            array(
                'id'       => 'supporter',
                'label'    => __( 'Supporter', 'emc-theme' ),
                'icon'     => 'fas fa-medal',
                'class'    => 'tier-supporter',
                'total'    => 100,
                'filled'   => min( (int) emc_option( 'emc_campaign_tier3_filled', 35 ), 100 ),
                'desc'     => __( '£100–£312 — 100 places', 'emc-theme' ),
            ),
            array(
                'id'       => 'friend',
                'label'    => __( 'Friend of the Mosque', 'emc-theme' ),
                'icon'     => 'fas fa-heart',
                'class'    => 'tier-friend',
                'total'    => 173,
                'filled'   => min( max( 0, $donors - 45 ), 173 ),
                'desc'     => __( 'Any amount — 173 places', 'emc-theme' ),
            ),
        );
        ?>

        <?php foreach ( $tiers as $tier ) :
            $empty = $tier['total'] - $tier['filled'];
            $show_filled = min( $tier['filled'], 12 );
            $show_empty  = min( $empty, 6 );
        ?>
        <div class="badr-tier-section" id="badr-<?php echo esc_attr( $tier['id'] ); ?>">
            <div class="badr-tier-header">
                <div class="badr-tier-title <?php echo esc_attr( $tier['class'] ); ?>">
                    <i class="<?php echo esc_attr( $tier['icon'] ); ?>" aria-hidden="true"></i>
                    <div>
                        <h3><?php echo esc_html( $tier['label'] ); ?></h3>
                        <p><?php echo esc_html( $tier['desc'] ); ?></p>
                    </div>
                </div>
                <div class="badr-tier-count">
                    <span class="filled-count"><?php echo esc_html( $tier['filled'] ); ?></span>
                    <span class="total-count">/ <?php echo esc_html( $tier['total'] ); ?> <?php esc_html_e( 'filled', 'emc-theme' ); ?></span>
                </div>
            </div>
            <div class="donor-slots-grid badr-slots">
                <?php for ( $i = 0; $i < $show_filled; $i++ ) : ?>
                <div class="donor-slot filled <?php echo esc_attr( $tier['class'] ); ?>">
                    <i class="<?php echo esc_attr( $tier['icon'] ); ?>" aria-hidden="true"></i>
                    <span><?php printf( esc_html__( 'Donor #%d', 'emc-theme' ), $i + 1 ); ?></span>
                </div>
                <?php endfor; ?>
                <?php for ( $i = 0; $i < $show_empty; $i++ ) : ?>
                <a href="<?php echo esc_url( $cta_url ); ?>" class="donor-slot empty <?php echo esc_attr( $tier['class'] ); ?>-empty">
                    <i class="fas fa-plus-circle" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Claim your place', 'emc-theme' ); ?></span>
                </a>
                <?php endfor; ?>
                <?php if ( $tier['filled'] > $show_filled || $empty > $show_empty ) : ?>
                <div class="badr-more-slots">
                    <i class="fas fa-ellipsis-h"></i>
                    <span><?php printf( esc_html__( '%d more places available', 'emc-theme' ), max( 0, $empty ) ); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="text-center" style="margin-top:3rem;">
            <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary" style="font-size:var(--step-0);padding:1rem 2.5rem;">
                <i class="fas fa-heart" aria-hidden="true"></i>
                <?php esc_html_e( 'Secure Your Place on the Badr Wall', 'emc-theme' ); ?>
            </a>
        </div>
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
