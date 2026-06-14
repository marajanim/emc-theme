<?php
/**
 * EMC Theme — single-emc_service.php
 * Single service / programme template.
 * @package emc-theme
 */

get_header();

while ( have_posts() ) :
    the_post();

    $icon     = get_post_meta( get_the_ID(), '_emc_service_icon', true ) ?: 'fas fa-star';
    $cats     = get_the_terms( get_the_ID(), 'service_category' );
    $cat_name = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
?>

<section class="page-hero page-hero--service" aria-label="<?php the_title_attribute(); ?>">
    <div class="container">
        <div class="page-hero-content">
            <div class="page-hero-icon" aria-hidden="true">
                <i class="<?php echo esc_attr( $icon ); ?>"></i>
            </div>
            <?php if ( $cat_name ) : ?>
            <span class="page-hero-badge"><?php echo esc_html( $cat_name ); ?></span>
            <?php endif; ?>
            <h1><?php the_title(); ?></h1>
            <?php if ( has_excerpt() ) : ?>
            <p class="page-hero-subtitle"><?php the_excerpt(); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="service-single-layout">

            <article class="service-single-content prose">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="single-featured-image">
                    <?php the_post_thumbnail( 'emc-card' ); ?>
                </div>
                <?php endif; ?>

                <?php the_content(); ?>

                <div class="single-back-link">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'emc_service' ) ); ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        <?php esc_html_e( 'All Services', 'emc-theme' ); ?>
                    </a>
                </div>
            </article>

            <aside class="service-single-sidebar">
                <div class="service-cta-card glass-card">
                    <div class="service-cta-icon" aria-hidden="true">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </div>
                    <h3><?php esc_html_e( 'Get Involved', 'emc-theme' ); ?></h3>
                    <p><?php esc_html_e( 'Interested in this service? Contact us to learn more or register your interest.', 'emc-theme' ); ?></p>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary btn-block">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <?php esc_html_e( 'Contact Us', 'emc-theme' ); ?>
                    </a>
                </div>

                <?php
                $related = new WP_Query( array(
                    'post_type'      => 'emc_service',
                    'posts_per_page' => 4,
                    'post__not_in'   => array( get_the_ID() ),
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) );
                if ( $related->have_posts() ) :
                ?>
                <div class="related-services-card glass-card" style="margin-top:1.5rem">
                    <h3><?php esc_html_e( 'Other Services', 'emc-theme' ); ?></h3>
                    <ul class="related-services-list">
                        <?php while ( $related->have_posts() ) : $related->the_post();
                            $r_icon = get_post_meta( get_the_ID(), '_emc_service_icon', true ) ?: 'fas fa-star';
                        ?>
                        <li>
                            <i class="<?php echo esc_attr( $r_icon ); ?>" aria-hidden="true"></i>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
                <?php endif; ?>
            </aside>

        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
