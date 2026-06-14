<?php
/**
 * Template Part: Services Preview — Phase 4 CPT-driven version.
 * Queries emc_service CPT (featured only). Falls back to static cards
 * unless the admin has set "CPT only" in the Customizer.
 * @package emc-theme
 */

$heading      = emc_option( 'emc_services_heading',    __( 'Serving the Community', 'emc-theme' ) );
$subheading   = emc_option( 'emc_services_subheading', __( 'Our Services', 'emc-theme' ) );
$cta_label    = emc_option( 'emc_services_cta_label',  __( 'View All Services', 'emc-theme' ) );
$cpt_only     = (bool) emc_option( 'emc_services_cpt_only', false );
$services_url = get_permalink( get_page_by_path( 'services' ) ) ?: home_url( '/services/' );

// Query featured services from CPT
$cpt_query = new WP_Query( array(
    'post_type'      => 'emc_service',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'   => '_emc_service_featured',
            'value' => '1',
        ),
    ),
    'meta_key'  => '_emc_service_order',
    'orderby'   => 'meta_value_num',
    'order'     => 'ASC',
) );

// If no featured found, fall back to latest 6
if ( ! $cpt_query->have_posts() ) {
    $cpt_query = new WP_Query( array(
        'post_type'      => 'emc_service',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ) );
}

$use_cpt = $cpt_query->have_posts();

// Static fallback cards (shown when no CPT content and admin hasn't disabled them)
$static_services = array(
    array( 'icon' => 'fas fa-praying-hands', 'title' => __( 'Friday Prayers',     'emc-theme' ), 'desc' => __( 'Join us for weekly Jumu\'ah prayers and inspiring Khutbahs tailored to our contemporary challenges.', 'emc-theme' ), 'delay' => '0s' ),
    array( 'icon' => 'fas fa-users',         'title' => __( 'Youth Programmes',   'emc-theme' ), 'desc' => __( 'Engaging activities, educational classes, and sports for the next generation of Muslims.', 'emc-theme' ),               'delay' => '.1s' ),
    array( 'icon' => 'fas fa-book-open',     'title' => __( 'Reversion Support',  'emc-theme' ), 'desc' => __( 'Guidance, resources, and a welcoming community for those embracing the Islamic faith.', 'emc-theme' ),                'delay' => '.2s' ),
    array( 'icon' => 'fas fa-graduation-cap','title' => __( 'Islamic Education',  'emc-theme' ), 'desc' => __( 'Weekend Madrasah, Arabic classes, and Quran lessons for children and adults.', 'emc-theme' ),                       'delay' => '.3s' ),
    array( 'icon' => 'fas fa-hand-holding-heart','title'=> __( 'Welfare Services','emc-theme' ), 'desc' => __( 'Food bank partnerships, financial counselling, and support for those in need.', 'emc-theme' ),                      'delay' => '.4s' ),
    array( 'icon' => 'fas fa-comments',      'title' => __( 'Community Events',   'emc-theme' ), 'desc' => __( 'Regular gatherings, sports days, and interfaith activities that bring people together.', 'emc-theme' ),              'delay' => '.5s' ),
);
?>
<section class="services section-padding" id="services" aria-labelledby="services-heading">
    <div class="container">
        <div class="section-header">
            <span class="subtitle"><?php echo esc_html( $subheading ); ?></span>
            <h2 id="services-heading"><?php echo esc_html( $heading ); ?></h2>
        </div>

        <div class="services-grid">
            <?php if ( $use_cpt ) : ?>
                <?php
                $delay = 0;
                while ( $cpt_query->have_posts() ) : $cpt_query->the_post();
                    $icon = get_post_meta( get_the_ID(), '_emc_service_icon', true ) ?: 'fas fa-star-and-crescent';
                ?>
                <div class="service-card scroll-reveal" style="transition-delay:<?php echo esc_attr( $delay . 's' ); ?>">
                    <div class="icon-wrapper" aria-hidden="true">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </div>
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                    <?php if ( get_permalink() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="service-card-link">
                        <?php esc_html_e( 'Learn more', 'emc-theme' ); ?>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php
                $delay = round( $delay + 0.1, 1 );
                endwhile;
                wp_reset_postdata();
                ?>
            <?php elseif ( ! $cpt_only ) : ?>
                <?php foreach ( $static_services as $s ) : ?>
                <div class="service-card scroll-reveal" style="transition-delay:<?php echo esc_attr( $s['delay'] ); ?>">
                    <div class="icon-wrapper" aria-hidden="true">
                        <i class="<?php echo esc_attr( $s['icon'] ); ?>"></i>
                    </div>
                    <h3><?php echo esc_html( $s['title'] ); ?></h3>
                    <p><?php echo esc_html( $s['desc'] ); ?></p>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="no-content-notice"><?php esc_html_e( 'No services published yet. Add services in the WordPress admin under Services.', 'emc-theme' ); ?></p>
            <?php endif; ?>
        </div>

        <div class="text-center" style="margin-top:3rem;">
            <a href="<?php echo esc_url( $services_url ); ?>" class="btn btn-primary">
                <?php echo esc_html( $cta_label ); ?>
            </a>
        </div>
    </div>
</section>
