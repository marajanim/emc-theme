<?php
/**
 * EMC Theme — single-emc_event.php
 * Single event template.
 * @package emc-theme
 */

get_header();

while ( have_posts() ) :
    the_post();

    $date     = get_post_meta( get_the_ID(), '_emc_event_date',     true );
    $end_date = get_post_meta( get_the_ID(), '_emc_event_end_date', true );
    $time     = get_post_meta( get_the_ID(), '_emc_event_time',     true );
    $venue    = get_post_meta( get_the_ID(), '_emc_event_venue',    true );
    $capacity = get_post_meta( get_the_ID(), '_emc_event_capacity', true );
    $reg_link = get_post_meta( get_the_ID(), '_emc_event_reg_link', true );
    $cats     = get_the_terms( get_the_ID(), 'event_category' );
    $cat_name = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';

    $formatted_date = $date ? date_i18n( get_option( 'date_format' ), strtotime( $date ) ) : '';
    $formatted_end  = $end_date ? date_i18n( get_option( 'date_format' ), strtotime( $end_date ) ) : '';
?>

<section class="page-hero page-hero--event" aria-label="<?php the_title_attribute(); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="page-hero-bg" style="background-image:url(<?php echo esc_url( get_the_post_thumbnail_url( null, 'emc-hero' ) ); ?>)" aria-hidden="true"></div>
    <div class="page-hero-overlay" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="container">
        <div class="page-hero-content">
            <?php if ( $cat_name ) : ?>
            <span class="page-hero-badge">
                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                <?php echo esc_html( $cat_name ); ?>
            </span>
            <?php endif; ?>
            <h1><?php the_title(); ?></h1>
            <?php if ( $formatted_date ) : ?>
            <p class="page-hero-meta">
                <i class="fas fa-calendar" aria-hidden="true"></i>
                <?php echo esc_html( $formatted_date );
                      echo $formatted_end && $formatted_end !== $formatted_date
                          ? ' &ndash; ' . esc_html( $formatted_end ) : ''; ?>
                <?php if ( $time ) : ?>
                &nbsp;&bull;&nbsp;
                <i class="fas fa-clock" aria-hidden="true"></i>
                <?php echo esc_html( $time ); ?>
                <?php endif; ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="event-single-layout">

            <article class="event-single-content prose">
                <?php if ( has_post_thumbnail() && ! get_post_meta( get_the_ID(), '_emc_event_date', true ) ) : ?>
                <div class="single-featured-image">
                    <?php the_post_thumbnail( 'emc-card' ); ?>
                </div>
                <?php endif; ?>

                <?php the_content(); ?>

                <div class="single-back-link">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'emc_event' ) ); ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        <?php esc_html_e( 'All Events', 'emc-theme' ); ?>
                    </a>
                </div>
            </article>

            <aside class="event-single-sidebar">
                <div class="event-details-card glass-card">
                    <h3><?php esc_html_e( 'Event Details', 'emc-theme' ); ?></h3>
                    <ul class="event-details-list">
                        <?php if ( $formatted_date ) : ?>
                        <li>
                            <i class="fas fa-calendar-day" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Date', 'emc-theme' ); ?></strong>
                                <span><?php echo esc_html( $formatted_date );
                                      if ( $formatted_end && $formatted_end !== $formatted_date ) {
                                          echo ' &ndash; ' . esc_html( $formatted_end );
                                      } ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( $time ) : ?>
                        <li>
                            <i class="fas fa-clock" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Time', 'emc-theme' ); ?></strong>
                                <span><?php echo esc_html( $time ); ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( $venue ) : ?>
                        <li>
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Venue', 'emc-theme' ); ?></strong>
                                <span><?php echo esc_html( $venue ); ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( $capacity ) : ?>
                        <li>
                            <i class="fas fa-users" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Capacity', 'emc-theme' ); ?></strong>
                                <span><?php echo absint( $capacity ); ?> <?php esc_html_e( 'places', 'emc-theme' ); ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
                        <li>
                            <i class="fas fa-tag" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Category', 'emc-theme' ); ?></strong>
                                <span><?php echo esc_html( implode( ', ', wp_list_pluck( $cats, 'name' ) ) ); ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <?php if ( $reg_link ) : ?>
                    <a href="<?php echo esc_url( $reg_link ); ?>" class="btn btn-primary btn-block" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-ticket-alt" aria-hidden="true"></i>
                        <?php esc_html_e( 'Register for this Event', 'emc-theme' ); ?>
                    </a>
                    <?php endif; ?>
                </div>

                <div class="event-sidebar-donate glass-card" style="margin-top:1.5rem">
                    <p class="sidebar-donate-text"><?php esc_html_e( 'Support our community events and programmes.', 'emc-theme' ); ?></p>
                    <?php echo emc_donate_button( __( 'Donate Now', 'emc-theme' ), 'btn btn-primary btn-block' ); ?>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php
    /* Related events */
    $related = new WP_Query( array(
        'post_type'      => 'emc_event',
        'posts_per_page' => 3,
        'post__not_in'   => array( get_the_ID() ),
        'meta_key'       => '_emc_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ) );
    if ( $related->have_posts() ) :
?>
<section class="section-padding related-cpt-section">
    <div class="container">
        <h2 class="section-heading"><?php esc_html_e( 'More Events', 'emc-theme' ); ?></h2>
        <div class="event-cards-grid">
            <?php while ( $related->have_posts() ) : $related->the_post();
                $r_date  = get_post_meta( get_the_ID(), '_emc_event_date',  true );
                $r_venue = get_post_meta( get_the_ID(), '_emc_event_venue', true );
            ?>
            <article class="event-card glass-card">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="event-card-img" tabindex="-1" aria-hidden="true">
                    <?php the_post_thumbnail( 'emc-thumbnail' ); ?>
                </a>
                <?php endif; ?>
                <div class="event-card-body">
                    <?php if ( $r_date ) : ?>
                    <time class="event-date-chip" datetime="<?php echo esc_attr( $r_date ); ?>">
                        <i class="fas fa-calendar-day" aria-hidden="true"></i>
                        <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $r_date ) ) ); ?>
                    </time>
                    <?php endif; ?>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php if ( $r_venue ) : ?>
                    <p class="event-venue"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> <?php echo esc_html( $r_venue ); ?></p>
                    <?php endif; ?>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
