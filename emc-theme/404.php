<?php
/**
 * EMC Theme — 404.php
 * @package emc-theme
 */

get_header();
?>

<section class="page-hero" style="background: linear-gradient(135deg, var(--deep-blue) 0%, #1A2B4C 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="badge" style="background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.2);color:var(--white);">
                <i class="fas fa-exclamation-triangle"></i> Error 404
            </span>
            <h1><?php esc_html_e( 'Page Not Found', 'emc-theme' ); ?></h1>
            <p><?php esc_html_e( 'The page you are looking for may have been moved, deleted, or may never have existed.', 'emc-theme' ); ?></p>
            <div class="hero-actions" style="justify-content:center;margin-top:2rem;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> <?php esc_html_e( 'Return Home', 'emc-theme' ); ?>
                </a>
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn btn-outline">
                    <?php esc_html_e( 'Contact Us', 'emc-theme' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
