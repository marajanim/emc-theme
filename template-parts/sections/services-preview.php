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
$contact_url  = get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact/' );

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

// Static fallback cards — 5 core EMC services with Read More + Book links
$static_services = array(
    array(
        'icon'       => 'fas fa-book-open',
        'title'      => __( 'Arabic Education', 'emc-theme' ),
        'desc'       => __( 'Weekend Madrasah, Arabic classes, and Quran lessons for children and adults of all levels.', 'emc-theme' ),
        'delay'      => '0s',
        'read_url'   => $services_url . '#arabic-education',
        'book_url'   => add_query_arg( 'service', 'arabic-education', $contact_url ),
        'book_label' => __( 'Enrol Now', 'emc-theme' ),
    ),
    array(
        'icon'       => 'fas fa-ring',
        'title'      => __( 'Nikah (Marriage)', 'emc-theme' ),
        'desc'       => __( 'Islamic marriage ceremonies conducted by our Imam with pre-marriage guidance and support.', 'emc-theme' ),
        'delay'      => '.1s',
        'read_url'   => $services_url . '#nikah',
        'book_url'   => add_query_arg( 'service', 'nikah', $contact_url ),
        'book_label' => __( 'Book a Ceremony', 'emc-theme' ),
    ),
    array(
        'icon'       => 'fas fa-praying-hands',
        'title'      => __( 'Janaza Services', 'emc-theme' ),
        'desc'       => __( 'Compassionate funeral prayer, ghusl, and burial coordination services available 24/7.', 'emc-theme' ),
        'delay'      => '.2s',
        'read_url'   => $services_url . '#janaza',
        'book_url'   => add_query_arg( 'service', 'janaza', $contact_url ),
        'book_label' => __( 'Contact Us', 'emc-theme' ),
    ),
    array(
        'icon'       => 'fas fa-user-tie',
        'title'      => __( 'Meet an Imam', 'emc-theme' ),
        'desc'       => __( 'Schedule a one-to-one appointment with our Imam for spiritual guidance, counselling, or Islamic advice.', 'emc-theme' ),
        'delay'      => '.3s',
        'read_url'   => $services_url . '#meet-an-imam',
        'book_url'   => add_query_arg( 'service', 'meet-an-imam', $contact_url ),
        'book_label' => __( 'Book Appointment', 'emc-theme' ),
    ),
    array(
        'icon'       => 'fas fa-hand-holding-heart',
        'title'      => __( 'Welfare Services', 'emc-theme' ),
        'desc'       => __( 'Food bank partnerships, financial counselling, and support for families in need.', 'emc-theme' ),
        'delay'      => '.4s',
        'read_url'   => $services_url . '#welfare',
        'book_url'   => add_query_arg( 'service', 'welfare', $contact_url ),
        'book_label' => __( 'Get Support', 'emc-theme' ),
    ),
    array(
        'icon'       => 'fas fa-calendar-alt',
        'title'      => __( 'General Events', 'emc-theme' ),
        'desc'       => __( 'Community gatherings, Islamic talks, sports days, and interfaith activities throughout the year.', 'emc-theme' ),
        'delay'      => '.5s',
        'read_url'   => get_permalink( get_page_by_path( 'events' ) ) ?: home_url( '/events/' ),
        'book_url'   => add_query_arg( 'service', 'events', $contact_url ),
        'book_label' => __( 'View Events', 'emc-theme' ),
    ),
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
                    $icon     = get_post_meta( get_the_ID(), '_emc_service_icon', true ) ?: 'fas fa-star-and-crescent';
                    $book_url = add_query_arg( 'service', sanitize_title( get_the_title() ), $contact_url );
                ?>
                <div class="service-card scroll-reveal" style="transition-delay:<?php echo esc_attr( $delay . 's' ); ?>">
                    <div class="icon-wrapper" aria-hidden="true">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </div>
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                    <div class="service-card-actions">
                        <a href="<?php the_permalink(); ?>" class="service-card-link">
                            <?php esc_html_e( 'Read More', 'emc-theme' ); ?>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a href="<?php echo esc_url( $book_url ); ?>" class="service-card-book btn btn-primary btn-sm">
                            <?php esc_html_e( 'Book / Enquire', 'emc-theme' ); ?>
                        </a>
                    </div>
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
                    <div class="service-card-actions">
                        <a href="<?php echo esc_url( $s['read_url'] ); ?>" class="service-card-link">
                            <?php esc_html_e( 'Read More', 'emc-theme' ); ?>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a href="<?php echo esc_url( $s['book_url'] ); ?>" class="service-card-book btn btn-primary btn-sm">
                            <?php echo esc_html( $s['book_label'] ); ?>
                        </a>
                    </div>
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
