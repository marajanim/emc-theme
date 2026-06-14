<?php
/**
 * Template Part: Media & Latest News Preview
 * @package emc-theme
 */

$media_url = get_permalink( get_page_by_path( 'media' ) ) ?: home_url( '/media/' );

// Query latest blog posts
$news_query = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
) );
?>
<section class="homepage-media section-padding" id="media-news" style="background:var(--white);" aria-labelledby="media-heading">
    <div class="container">
        <div class="section-header">
            <span class="subtitle"><?php esc_html_e( 'Stay Connected', 'emc-theme' ); ?></span>
            <h2 id="media-heading"><?php esc_html_e( 'Media & Latest News', 'emc-theme' ); ?></h2>
        </div>

        <div class="media-news-layout">

            <!-- Left: Media Preview -->
            <div class="media-preview-col scroll-reveal" aria-label="<?php esc_attr_e( 'Latest Media', 'emc-theme' ); ?>">
                <div class="media-preview-label">
                    <i class="fas fa-photo-video" aria-hidden="true"></i>
                    <?php esc_html_e( 'Latest Media', 'emc-theme' ); ?>
                </div>
                <div class="media-preview-featured">
                    <div class="media-preview-thumb">
                        <img
                            src="<?php echo esc_url( EMC_ASSETS . '/gallery/Eid Celebration/eid_3-600x600.jpeg' ); ?>"
                            alt="<?php esc_attr_e( 'Community event', 'emc-theme' ); ?>"
                            loading="lazy"
                        >
                        <div class="media-play-overlay" aria-hidden="true">
                            <div class="play-btn"><i class="fas fa-play"></i></div>
                        </div>
                    </div>
                    <div class="media-preview-info">
                        <span class="media-preview-badge video">
                            <i class="fas fa-video" aria-hidden="true"></i>
                            <?php esc_html_e( 'Video', 'emc-theme' ); ?>
                        </span>
                        <h3><?php esc_html_e( 'The Importance of Community Ties in Islam', 'emc-theme' ); ?></h3>
                        <span class="media-preview-date">10 May 2026 &bull; 45 min</span>
                    </div>
                </div>

                <div class="media-preview-grid" aria-hidden="true">
                    <?php
                    // Dynamic mini-cards from emc_gallery CPT
                    $mini_gallery = new WP_Query( array(
                        'post_type'      => 'emc_gallery',
                        'posts_per_page' => 3,
                        'post_status'    => 'publish',
                        'orderby'        => 'rand',
                    ) );

                    if ( $mini_gallery->have_posts() ) :
                        while ( $mini_gallery->have_posts() ) :
                            $mini_gallery->the_post();
                            $mini_thumb = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
                            if ( ! $mini_thumb ) continue;
                    ?>
                    <div class="media-mini-card">
                        <div class="media-mini-thumb" style="background-image:url('<?php echo esc_url( $mini_thumb ); ?>');background-size:cover;background-position:center;"></div>
                        <span><?php the_title(); ?></span>
                    </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                    <!-- Static fallback -->
                    <div class="media-mini-card">
                        <div class="media-mini-thumb" style="background-image:url('<?php echo esc_url( EMC_ASSETS . '/gallery/Activity During Ramadan/r2-300x300.jpeg' ); ?>');background-size:cover;background-position:center;"></div>
                        <span><?php esc_html_e( 'Activity During Ramadan', 'emc-theme' ); ?></span>
                    </div>
                    <div class="media-mini-card">
                        <div class="media-mini-thumb" style="background-image:url('<?php echo esc_url( EMC_ASSETS . '/gallery/Friday Prayer/FPS-600x600.jpeg' ); ?>');background-size:cover;background-position:center;"></div>
                        <span><?php esc_html_e( 'Friday Prayer', 'emc-theme' ); ?></span>
                    </div>
                    <div class="media-mini-card">
                        <div class="media-mini-thumb" style="background-image:url('<?php echo esc_url( EMC_ASSETS . '/gallery/Outdoor Activity 2024/out_1-1-300x300.jpeg' ); ?>');background-size:cover;background-position:center;"></div>
                        <span><?php esc_html_e( 'Outdoor Activity 2024', 'emc-theme' ); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo esc_url( $media_url ); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:1.5rem;">
                    <i class="fas fa-images" aria-hidden="true"></i>
                    <?php esc_html_e( 'View Full Gallery', 'emc-theme' ); ?>
                </a>
            </div>

            <!-- Right: News -->
            <div class="news-preview-col">
                <div class="media-preview-label">
                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                    <?php esc_html_e( 'Latest News', 'emc-theme' ); ?>
                </div>
                <div class="news-preview-list">
                    <?php if ( $news_query->have_posts() ) : ?>
                        <?php
                        $delay = 0;
                        while ( $news_query->have_posts() ) : $news_query->the_post();
                            $thumb = get_the_post_thumbnail_url( get_the_ID(), 'emc-thumbnail' );
                        ?>
                        <article class="news-preview-card scroll-reveal" style="transition-delay:<?php echo esc_attr( $delay . 's' ); ?>">
                            <div class="news-preview-img"
                                <?php if ( $thumb ) : ?>
                                    style="background-image:url('<?php echo esc_url( $thumb ); ?>')"
                                <?php else : ?>
                                    style="background:linear-gradient(135deg,var(--deep-blue),#0E3020)"
                                <?php endif; ?>
                                role="img"
                                aria-label="<?php the_title_attribute(); ?>"
                            ></div>
                            <div class="news-preview-body">
                                <div class="news-preview-meta">
                                    <?php
                                    $cats = get_the_category();
                                    if ( $cats ) :
                                    ?>
                                    <span class="news-preview-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                                    <?php endif; ?>
                                    <span class="news-preview-date"><?php echo get_the_date(); ?></span>
                                </div>
                                <h4><?php the_title(); ?></h4>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                                <a href="<?php the_permalink(); ?>" class="news-preview-link">
                                    <?php esc_html_e( 'Read More', 'emc-theme' ); ?>
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                        <?php
                        $delay = round( $delay + 0.1, 1 );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    <?php else : ?>
                        <!-- Static fallback cards until posts are published -->
                        <?php
                        $static_news = array(
                            array(
                                'cat'   => __( 'Announcement', 'emc-theme' ),
                                'date'  => '12 May 2026',
                                'title' => __( 'Building Fund Reaches 68% Target', 'emc-theme' ),
                                'desc'  => __( 'Alhamdulillah, through the generous support of our community, the campaign has reached an incredible milestone.', 'emc-theme' ),
                                'delay' => '0s',
                                'bg'    => 'background-image:url(' . EMC_ASSETS . '/gallery/Fundraising Event/20250316_170418-300x225.jpg);background-size:cover;background-position:center',
                            ),
                            array(
                                'cat'   => __( 'Community', 'emc-theme' ),
                                'date'  => '05 May 2026',
                                'title' => __( 'EMC Partners with Local Food Bank', 'emc-theme' ),
                                'desc'  => __( 'We are proud to announce a new partnership providing weekly collections and distribution support.', 'emc-theme' ),
                                'delay' => '0.1s',
                                'bg'    => 'background-image:url(' . EMC_ASSETS . '/gallery/Quran Group Study/WhatsApp-Image-2025-08-21-at-21.04.12-5-300x225.jpeg);background-size:cover;background-position:center',
                            ),
                            array(
                                'cat'   => __( 'Education', 'emc-theme' ),
                                'date'  => '28 Apr 2026',
                                'title' => __( 'New Weekend Madrasah Curriculum Launched', 'emc-theme' ),
                                'desc'  => __( 'Starting next term, our Madrasah rolls out a modernized curriculum focusing on interactive learning.', 'emc-theme' ),
                                'delay' => '0.2s',
                                'bg'    => 'background-image:url(' . EMC_ASSETS . '/gallery/Outdoor Activity 2024/out_3-1-300x300.jpeg);background-size:cover;background-position:center',
                            ),
                        );
                        foreach ( $static_news as $item ) :
                        ?>
                        <article class="news-preview-card scroll-reveal" style="transition-delay:<?php echo esc_attr( $item['delay'] ); ?>">
                            <div class="news-preview-img" style="<?php echo esc_attr( $item['bg'] ); ?>"></div>
                            <div class="news-preview-body">
                                <div class="news-preview-meta">
                                    <span class="news-preview-cat"><?php echo esc_html( $item['cat'] ); ?></span>
                                    <span class="news-preview-date"><?php echo esc_html( $item['date'] ); ?></span>
                                </div>
                                <h4><?php echo esc_html( $item['title'] ); ?></h4>
                                <p><?php echo esc_html( $item['desc'] ); ?></p>
                                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/news/' ) ); ?>" class="news-preview-link">
                                    <?php esc_html_e( 'Read More', 'emc-theme' ); ?>
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/news/' ) ); ?>" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1.5rem;">
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    <?php esc_html_e( 'All News & Updates', 'emc-theme' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>
