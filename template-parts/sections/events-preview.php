<?php
/**
 * Template Part: Featured Events Preview
 * @package emc-theme
 */

$events_url = get_permalink( get_page_by_path( 'events' ) ) ?: home_url( '/events/' );

// Query upcoming events CPT; fall back to static if none published yet
$events_query = new WP_Query( array(
    'post_type'      => 'emc_event',
    'posts_per_page' => 2,
    'post_status'    => 'publish',
    'meta_key'       => '_emc_event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => array(
        array(
            'key'     => '_emc_event_date',
            'value'   => date( 'Y-m-d' ),
            'compare' => '>=',
            'type'    => 'DATE',
        ),
    ),
) );
?>
<section class="events section-padding" id="events" style="background:var(--white);" aria-labelledby="events-heading">
    <div class="container">
        <div class="section-header left">
            <span class="subtitle"><?php esc_html_e( 'Upcoming', 'emc-theme' ); ?></span>
            <h2 id="events-heading"><?php esc_html_e( 'Featured Events', 'emc-theme' ); ?></h2>
        </div>

        <div class="events-grid">
            <?php if ( $events_query->have_posts() ) : ?>
                <?php
                $delay = 0;
                while ( $events_query->have_posts() ) : $events_query->the_post();
                    $event_date  = get_post_meta( get_the_ID(), '_emc_event_date', true );
                    $event_time  = get_post_meta( get_the_ID(), '_emc_event_time', true );
                    $event_venue = get_post_meta( get_the_ID(), '_emc_event_venue', true );
                    $thumb       = get_the_post_thumbnail_url( get_the_ID(), 'emc-card' );
                    $day         = $event_date ? date( 'd', strtotime( $event_date ) ) : '';
                    $mon         = $event_date ? strtoupper( date( 'M', strtotime( $event_date ) ) ) : '';
                ?>
                <div class="event-card scroll-reveal" style="transition-delay:<?php echo esc_attr( $delay . 's' ); ?>">
                    <div class="event-img"<?php if ( $thumb ) : ?> style="background-image:url('<?php echo esc_url( $thumb ); ?>')"<?php endif; ?>>
                        <?php if ( $day ) : ?>
                        <div class="event-date">
                            <span style="display:block;font-size:var(--step-2);font-weight:700;color:var(--primary-green);line-height:1;"><?php echo esc_html( $day ); ?></span>
                            <span style="display:block;font-size:var(--step--2);font-weight:600;color:var(--text-muted);"><?php echo esc_html( $mon ); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div style="padding:2rem;">
                        <h3 style="margin-bottom:1rem;"><?php the_title(); ?></h3>
                        <div style="display:flex;gap:1rem;margin-bottom:1rem;font-size:var(--step--2);color:var(--text-muted);">
                            <?php if ( $event_time ) : ?>
                            <span><i class="far fa-clock" style="color:var(--accent-gold);" aria-hidden="true"></i> <?php echo esc_html( $event_time ); ?></span>
                            <?php endif; ?>
                            <?php if ( $event_venue ) : ?>
                            <span><i class="fas fa-map-marker-alt" style="color:var(--accent-gold);" aria-hidden="true"></i> <?php echo esc_html( $event_venue ); ?></span>
                            <?php endif; ?>
                        </div>
                        <p style="color:var(--text-muted);font-size:var(--step--1);margin-bottom:1.5rem;"><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="padding:0.5rem 1rem;font-size:var(--step--2);">
                            <?php esc_html_e( 'Learn More', 'emc-theme' ); ?>
                        </a>
                    </div>
                </div>
                <?php
                $delay += 0.1;
                endwhile;
                wp_reset_postdata();
                ?>
            <?php else : ?>
                <!-- Static fallback cards until events are published -->
                <div class="event-card scroll-reveal">
                    <div class="event-img" style="background-image:url('<?php echo esc_url( EMC_ASSETS . '/gallery/Community Support Services/New-Muslim-600x338.jpeg' ); ?>');">
                        <div class="event-date">
                            <span style="display:block;font-size:var(--step-2);font-weight:700;color:var(--primary-green);line-height:1;">15</span>
                            <span style="display:block;font-size:var(--step--2);font-weight:600;color:var(--text-muted);">MAY</span>
                        </div>
                    </div>
                    <div style="padding:2rem;">
                        <h3 style="margin-bottom:1rem;"><?php esc_html_e( 'Community Support Services', 'emc-theme' ); ?></h3>
                        <div style="display:flex;gap:1rem;margin-bottom:1rem;font-size:var(--step--2);color:var(--text-muted);">
                            <span><i class="far fa-clock" style="color:var(--accent-gold);" aria-hidden="true"></i> 10:00 AM – 4:00 PM</span>
                            <span><i class="fas fa-map-marker-alt" style="color:var(--accent-gold);" aria-hidden="true"></i> EMC Centre</span>
                        </div>
                        <p style="color:var(--text-muted);font-size:var(--step--1);margin-bottom:1.5rem;">
                            <?php esc_html_e( 'Supporting new Muslims and community members with guidance, resources, and a welcoming environment.', 'emc-theme' ); ?>
                        </p>
                        <a href="<?php echo esc_url( $events_url ); ?>" class="btn btn-outline" style="padding:0.5rem 1rem;font-size:var(--step--2);">
                            <?php esc_html_e( 'View Events', 'emc-theme' ); ?>
                        </a>
                    </div>
                </div>
                <div class="event-card scroll-reveal" style="transition-delay:0.1s;">
                    <div class="event-img" style="background-image:url('<?php echo esc_url( EMC_ASSETS . '/gallery/Career Advise/WhatsApp-Image-2025-08-21-at-21.05.39-1-1-300x169.jpeg' ); ?>');">
                        <div class="event-date">
                            <span style="display:block;font-size:var(--step-2);font-weight:700;color:var(--primary-green);line-height:1;">22</span>
                            <span style="display:block;font-size:var(--step--2);font-weight:600;color:var(--text-muted);">MAY</span>
                        </div>
                    </div>
                    <div style="padding:2rem;">
                        <h3 style="margin-bottom:1rem;"><?php esc_html_e( 'Career Advise Seminar', 'emc-theme' ); ?></h3>
                        <div style="display:flex;gap:1rem;margin-bottom:1rem;font-size:var(--step--2);color:var(--text-muted);">
                            <span><i class="far fa-clock" style="color:var(--accent-gold);" aria-hidden="true"></i> 6:00 PM – 8:00 PM</span>
                            <span><i class="fas fa-map-marker-alt" style="color:var(--accent-gold);" aria-hidden="true"></i> Main Hall</span>
                        </div>
                        <p style="color:var(--text-muted);font-size:var(--step--1);margin-bottom:1.5rem;">
                            <?php esc_html_e( 'Career guidance and mentorship for youth aged 14–18. Guest speakers and workshops.', 'emc-theme' ); ?>
                        </p>
                        <a href="<?php echo esc_url( $events_url ); ?>" class="btn btn-outline" style="padding:0.5rem 1rem;font-size:var(--step--2);">
                            <?php esc_html_e( 'View Events', 'emc-theme' ); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center" style="margin-top:4rem;">
            <a href="<?php echo esc_url( $events_url ); ?>" class="btn btn-primary">
                <?php esc_html_e( 'View All Events', 'emc-theme' ); ?>
            </a>
        </div>
    </div>
</section>
