<?php
/**
 * EMC Theme — page.php
 * Generic WordPress page template.
 * Renders a page hero banner + post content.
 * For pages built with Elementor, Elementor handles rendering automatically.
 * @package emc-theme
 */

get_header();

while ( have_posts() ) :
    the_post();

    // Check if this page is built with Elementor
    $is_elementor = defined( 'ELEMENTOR_VERSION' ) &&
        class_exists( '\Elementor\Plugin' ) &&
        \Elementor\Plugin::$instance->db->is_built_with_elementor( get_the_ID() );

    if ( ! $is_elementor ) :
?>

<!-- ── Page Hero ─────────────────────────────────────────────────────── -->
<section class="page-hero inner-page-hero" aria-labelledby="page-title">
    <?php
    // Hero background: featured image → page-specific fallback → gradient
    $hero_img = get_the_post_thumbnail_url( get_the_ID(), 'emc-hero' );
    ?>
    <div class="page-hero-bg"<?php echo $hero_img ? ' style="background-image:url(\'' . esc_url( $hero_img ) . '\')"' : ''; ?>>
        <div class="page-hero-overlay"></div>
    </div>

    <div class="container">
        <div class="page-hero-content">
            <?php
            // Breadcrumb
            $parent = get_post_field( 'post_parent', get_the_ID() );
            if ( $parent ) :
                $parent_url   = get_permalink( $parent );
                $parent_title = get_the_title( $parent );
            ?>
            <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'emc-theme' ); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'emc-theme' ); ?></a>
                <span aria-hidden="true"> / </span>
                <a href="<?php echo esc_url( $parent_url ); ?>"><?php echo esc_html( $parent_title ); ?></a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page"><?php the_title(); ?></span>
            </nav>
            <?php else : ?>
            <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'emc-theme' ); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'emc-theme' ); ?></a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page"><?php the_title(); ?></span>
            </nav>
            <?php endif; ?>

            <h1 id="page-title"><?php the_title(); ?></h1>

            <?php
            // Show excerpt as subtitle if available
            $excerpt = get_the_excerpt();
            if ( $excerpt && ! has_blocks() ) :
            ?>
            <p class="page-hero-subtitle"><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── Page Content ──────────────────────────────────────────────────── -->
<?php if ( has_blocks( get_the_content() ) || strip_tags( get_the_content() ) ) : ?>
<section class="section-padding">
    <div class="container">
        <div class="entry-content page-content">
            <?php the_content(); ?>
        </div>
    </div>
</section>
<?php else : ?>
<!-- No content yet — show a friendly placeholder -->
<section class="section-padding">
    <div class="container">
        <div class="page-empty-state">
            <div class="page-empty-icon" aria-hidden="true">
                <i class="fas fa-mosque"></i>
            </div>
            <h2><?php echo esc_html( sprintf( __( 'Welcome to our %s page', 'emc-theme' ), get_the_title() ) ); ?></h2>
            <p><?php esc_html_e( 'This page is being set up. Please check back soon for more information, or contact us if you have any questions.', 'emc-theme' ); ?></p>
            <div class="page-empty-actions">
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact/' ) ); ?>" class="btn btn-primary">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <?php esc_html_e( 'Contact Us', 'emc-theme' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline">
                    <i class="fas fa-home" aria-hidden="true"></i>
                    <?php esc_html_e( 'Back to Home', 'emc-theme' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
    endif; // end non-Elementor check
endwhile;

get_footer();
