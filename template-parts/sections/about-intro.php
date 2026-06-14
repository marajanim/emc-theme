<?php
/**
 * Template Part: About Intro — Phase 4 dynamic version.
 * All content driven by Customizer (emc_hp_about section).
 * @package emc-theme
 */

$heading     = emc_option( 'emc_about_heading',    __( 'Who We Are', 'emc-theme' ) );
$subheading  = emc_option( 'emc_about_subheading', __( 'About Us', 'emc-theme' ) );
$body        = emc_option( 'emc_about_body',       __( 'Essex Muslim Centre is a registered UK charity dedicated to advancing Islamic faith, education, and community welfare in the heart of Chelmsford, Essex. Founded in 2018, we serve over 500 families across the region.', 'emc-theme' ) );
$stat1_num   = emc_option( 'emc_about_stat1_num',   '2018' );
$stat1_label = emc_option( 'emc_about_stat1_label', __( 'Founded', 'emc-theme' ) );
$stat2_num   = emc_option( 'emc_about_stat2_num',   '500+' );
$stat2_label = emc_option( 'emc_about_stat2_label', __( 'Families Served', 'emc-theme' ) );
$stat3_num   = emc_option( 'emc_about_stat3_num',   '10+' );
$stat3_label = emc_option( 'emc_about_stat3_label', __( 'Weekly Services', 'emc-theme' ) );
$cta_label   = emc_option( 'emc_about_cta_label', __( 'Learn More About Us', 'emc-theme' ) );
$about_url   = get_permalink( get_page_by_path( 'about' ) )
               ?: get_permalink( get_page_by_path( 'about-us' ) )
               ?: get_permalink( get_page_by_path( 'about-emc' ) )
               ?: home_url( '/about/' );
?>
<section class="about-intro section-padding" aria-labelledby="about-intro-heading">
    <div class="container">
        <div class="about-intro-layout">

            <!-- Text Column -->
            <div class="about-intro-text scroll-reveal">
                <span class="subtitle"><?php echo esc_html( $subheading ); ?></span>
                <h2 id="about-intro-heading"><?php echo esc_html( $heading ); ?></h2>
                <p><?php echo esc_html( $body ); ?></p>

                <div class="about-intro-stats" aria-label="<?php esc_attr_e( 'Key figures', 'emc-theme' ); ?>">
                    <div class="about-intro-stat">
                        <span class="stat-num" data-target="<?php echo esc_attr( preg_replace( '/\D/', '', $stat1_num ) ); ?>">
                            <?php echo esc_html( $stat1_num ); ?>
                        </span>
                        <span class="stat-label"><?php echo esc_html( $stat1_label ); ?></span>
                    </div>
                    <div class="about-intro-stat">
                        <span class="stat-num"><?php echo esc_html( $stat2_num ); ?></span>
                        <span class="stat-label"><?php echo esc_html( $stat2_label ); ?></span>
                    </div>
                    <div class="about-intro-stat">
                        <span class="stat-num"><?php echo esc_html( $stat3_num ); ?></span>
                        <span class="stat-label"><?php echo esc_html( $stat3_label ); ?></span>
                    </div>
                </div>

                <a href="<?php echo esc_url( $about_url ); ?>" class="btn btn-primary">
                    <?php echo esc_html( $cta_label ); ?>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <!-- Visual Column -->
            <div class="about-intro-visual scroll-reveal" style="transition-delay:.15s;" aria-hidden="true">
                <?php
                $bullet1    = emc_option( 'emc_about_bullet1', __( 'Faith-centred community hub', 'emc-theme' ) );
                $bullet2    = emc_option( 'emc_about_bullet2', __( 'Islamic education for all ages', 'emc-theme' ) );
                $bullet3    = emc_option( 'emc_about_bullet3', __( 'Active welfare & support services', 'emc-theme' ) );
                $about_img  = emc_option( 'emc_about_image', '' );
                ?>
                <div class="about-intro-card glass-card">
                    <?php if ( $about_img ) : ?>
                    <img src="<?php echo esc_url( $about_img ); ?>" alt="<?php echo esc_attr( $heading ); ?>" class="about-card-img" style="width:100%;border-radius:var(--border-radius);margin-bottom:1rem;">
                    <?php else : ?>
                    <div class="about-card-icon">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <?php endif; ?>
                    <ul class="about-values-list">
                        <?php if ( $bullet1 ) : ?><li><i class="fas fa-check-circle"></i> <?php echo esc_html( $bullet1 ); ?></li><?php endif; ?>
                        <?php if ( $bullet2 ) : ?><li><i class="fas fa-check-circle"></i> <?php echo esc_html( $bullet2 ); ?></li><?php endif; ?>
                        <?php if ( $bullet3 ) : ?><li><i class="fas fa-check-circle"></i> <?php echo esc_html( $bullet3 ); ?></li><?php endif; ?>
                        <li><i class="fas fa-check-circle"></i> <?php echo wp_kses_post( emc_charity_badge() ); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
