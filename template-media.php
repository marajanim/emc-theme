<?php
/**
 * Template Name: Media
 * Template Post Type: page
 *
 * EMC Theme — Media & News page template.
 * Hero, featured video, and podcast content editable via ACF.
 *
 * @package emc-theme
 */

get_header();

wp_enqueue_style( 'emc-page-media', EMC_ASSETS . '/css/media.css', array( 'emc-style' ), EMC_VERSION );

$media_js_path = EMC_DIR . '/assets/js/media.js';
if ( file_exists( $media_js_path ) ) {
    wp_enqueue_script( 'emc-page-media', EMC_ASSETS . '/js/media.js', array( 'emc-script' ), filemtime( $media_js_path ), true );
}
?>

<!-- Page Hero -->
<section class="page-hero" style="background: linear-gradient(135deg, #0F172A 0%, var(--primary-dark) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="badge" style="background:rgba(212,175,55,0.15);border-color:rgba(212,175,55,0.3);color:var(--accent-gold);"><i class="fas fa-photo-video"></i> <?php echo esc_html( emc_acf( 'media_hero_badge', __( 'Media Hub', 'emc-theme' ) ) ); ?></span>
            <h1><?php echo esc_html( emc_acf( 'media_hero_title', __( 'Media & News', 'emc-theme' ) ) ); ?></h1>
            <p><?php echo esc_html( emc_acf( 'media_hero_desc', __( 'Catch up on the latest Khutbahs, browse photos from recent events, and read our community blog.', 'emc-theme' ) ) ); ?></p>
        </div>
    </div>
</section>

<!-- Content Area -->
<section class="media-section section-padding">
    <div class="container">

        <!-- Custom Tabs -->
        <div class="media-tabs">
            <button class="media-tab-btn active" data-tab="videos"><i class="fas fa-play-circle"></i> <?php echo esc_html( emc_acf( 'media_tab_videos', 'Videos & Audio' ) ); ?></button>
            <button class="media-tab-btn" data-tab="photos"><i class="fas fa-images"></i> <?php echo esc_html( emc_acf( 'media_tab_photos', 'Photo Gallery' ) ); ?></button>
            <button class="media-tab-btn" data-tab="news"><i class="fas fa-newspaper"></i> <?php echo esc_html( emc_acf( 'media_tab_news', 'News & Blog' ) ); ?></button>
        </div>

        <!-- Tab 1: Videos -->
        <div class="media-tab-panel active" id="tab-videos">
            <div class="section-header left">
                <h2><?php esc_html_e( 'Latest Khutbahs & Lectures', 'emc-theme' ); ?></h2>
            </div>

            <div class="video-grid">
                <!-- Featured Video -->
                <div class="video-card featured scroll-reveal">
                    <div class="video-thumbnail">
                        <?php $vid_thumb = emc_acf_image( 'media_video_thumbnail', EMC_ASSETS . '/gallery/Friday Prayer/FPS-600x600.jpeg' ); ?>
                        <img src="<?php echo esc_url( $vid_thumb ); ?>" alt="<?php esc_attr_e( 'Khutbah Thumbnail', 'emc-theme' ); ?>">
                        <?php $vid_url = emc_acf( 'media_video_url', '' ); ?>
                        <a href="<?php echo $vid_url ? esc_url( $vid_url ) : '#'; ?>" class="play-btn"<?php echo $vid_url ? ' target="_blank"' : ''; ?>><i class="fas fa-play"></i></a>
                        <span class="video-duration"><?php echo esc_html( emc_acf( 'media_video_duration', '45:20' ) ); ?></span>
                    </div>
                    <div class="video-info">
                        <span class="video-date"><?php echo esc_html( emc_acf( 'media_video_date', '10 May 2026' ) ); ?></span>
                        <h3><?php echo esc_html( emc_acf( 'media_video_title', __( 'The Importance of Community Ties in Islam', 'emc-theme' ) ) ); ?></h3>
                        <p><?php echo esc_html( emc_acf( 'media_video_desc', __( 'Sheikh Ahmed discusses the prophetic examples of building a strong, unified community and the responsibilities we hold towards our neighbours.', 'emc-theme' ) ) ); ?></p>
                    </div>
                </div>

                <!-- Standard Videos (placeholder) -->
                <div class="video-card scroll-reveal" style="transition-delay:0.1s">
                    <div class="video-thumbnail">
                        <img src="<?php echo esc_url( EMC_ASSETS . '/gallery/Activity During Ramadan/r3-300x300.jpeg' ); ?>" alt="<?php esc_attr_e( 'Khutbah', 'emc-theme' ); ?>" style="width:100%;height:100%;object-fit:cover;">
                        <div class="play-btn small"><i class="fas fa-play"></i></div>
                        <span class="video-duration">38:15</span>
                    </div>
                    <div class="video-info">
                        <span class="video-date">03 May 2026</span>
                        <h4><?php esc_html_e( 'Patience During Difficult Times', 'emc-theme' ); ?></h4>
                    </div>
                </div>

                <div class="video-card scroll-reveal" style="transition-delay:0.2s">
                    <div class="video-thumbnail">
                        <img src="<?php echo esc_url( EMC_ASSETS . '/gallery/Friday Prayer/Friday-1-600x450.jpeg' ); ?>" alt="<?php esc_attr_e( 'Khutbah', 'emc-theme' ); ?>" style="width:100%;height:100%;object-fit:cover;">
                        <div class="play-btn small"><i class="fas fa-play"></i></div>
                        <span class="video-duration">42:10</span>
                    </div>
                    <div class="video-info">
                        <span class="video-date">26 Apr 2026</span>
                        <h4><?php esc_html_e( 'Preparing Your Heart for Ramadan', 'emc-theme' ); ?></h4>
                    </div>
                </div>
            </div>

            <!-- Podcast Box -->
            <div class="podcast-banner scroll-reveal">
                <div class="podcast-info">
                    <i class="fas fa-podcast"></i>
                    <div>
                        <h3><?php echo esc_html( emc_acf( 'media_podcast_heading', __( 'Listen on the Go', 'emc-theme' ) ) ); ?></h3>
                        <p><?php echo esc_html( emc_acf( 'media_podcast_desc', __( 'All our Friday Khutbahs and guest lectures are available on our weekly podcast. Subscribe on your favourite platform.', 'emc-theme' ) ) ); ?></p>
                    </div>
                </div>
                <div class="podcast-links">
                    <?php $spotify = emc_acf( 'media_podcast_spotify', '#' ); ?>
                    <?php $apple   = emc_acf( 'media_podcast_apple', '#' ); ?>
                    <a href="<?php echo esc_url( $spotify ); ?>" class="btn btn-outline"<?php echo $spotify !== '#' ? ' target="_blank"' : ''; ?>><i class="fab fa-spotify"></i> Spotify</a>
                    <a href="<?php echo esc_url( $apple ); ?>" class="btn btn-outline"<?php echo $apple !== '#' ? ' target="_blank"' : ''; ?>><i class="fab fa-apple"></i> Apple Podcasts</a>
                </div>
            </div>
        </div>

        <!-- Tab 2: Photos -->
        <div class="media-tab-panel" id="tab-photos">
            <div class="section-header left">
                <h2><?php esc_html_e( 'Photo Gallery', 'emc-theme' ); ?></h2>
            </div>

            <?php
            // ── Gather all gallery categories ────────────────────────────────
            $gallery_terms = get_terms( array(
                'taxonomy'   => 'gallery_category',
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ) );

            // Fallback static category list when taxonomy has no terms yet
            $fallback_cats = array(
                'Activity During Ramadan', 'Career Advise', 'Community Support Services',
                'Eid Celebration', 'Friday Prayer', 'Fundraising Event',
                'Outdoor Activity 2024', 'Quran Group Study', 'Visit to the Cambridge Mosque',
            );

            // Dynamic Photo Gallery — powered by emc_gallery CPT
            $gallery_query = new WP_Query( array(
                'post_type'      => 'emc_gallery',
                'posts_per_page' => 60,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            // Size class cycle for visual variety
            $size_classes = array( 'size-large', '', '', 'size-tall', 'size-wide', '', '', '', 'size-tall', '', '', 'size-wide', '', '', 'size-large', '', '', '', 'size-tall', '', '', 'size-wide', '', '', '', '', '', '', '', '' );
            $has_dynamic  = $gallery_query->have_posts();
            ?>

            <!-- Category Filter Bar -->
            <div class="gallery-filter-bar" id="gallery-filter-bar">
                <button class="gallery-filter-btn active" data-filter="all">
                    <i class="fas fa-th" aria-hidden="true"></i> <?php esc_html_e( 'All', 'emc-theme' ); ?>
                </button>
                <?php if ( $has_dynamic && ! is_wp_error( $gallery_terms ) && ! empty( $gallery_terms ) ) : ?>
                    <?php foreach ( $gallery_terms as $term ) : ?>
                    <button class="gallery-filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>">
                        <?php echo esc_html( $term->name ); ?>
                    </button>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach ( $fallback_cats as $cat ) : ?>
                    <button class="gallery-filter-btn" data-filter="<?php echo esc_attr( sanitize_title( $cat ) ); ?>">
                        <?php echo esc_html( $cat ); ?>
                    </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="photo-gallery" id="photo-gallery">
                <?php if ( $has_dynamic ) : ?>
                    <?php
                    $idx = 0;
                    while ( $gallery_query->have_posts() ) :
                        $gallery_query->the_post();
                        $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                        if ( ! $thumb_url ) continue;
                        $size_class  = isset( $size_classes[ $idx ] ) ? $size_classes[ $idx ] : '';
                        $delay       = round( fmod( $idx * 0.1, 0.3 ), 1 );
                        $item_terms  = wp_get_post_terms( get_the_ID(), 'gallery_category', array( 'fields' => 'slugs' ) );
                        $cat_slug    = ! empty( $item_terms ) && ! is_wp_error( $item_terms ) ? implode( ' ', $item_terms ) : 'uncategorised';
                    ?>
                    <div class="gallery-item <?php echo esc_attr( $size_class ); ?> scroll-reveal"
                         data-category="<?php echo esc_attr( $cat_slug ); ?>"
                         <?php echo $delay ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                        <div class="gallery-overlay">
                            <span><?php the_title(); ?></span>
                            <i class="fas fa-expand"></i>
                        </div>
                    </div>
                    <?php
                        $idx++;
                    endwhile;
                    wp_reset_postdata();
                    ?>
                <?php else : ?>
                    <!-- Static fallback (pre-import) -->
                    <?php
                    $fallback_items = function_exists( 'emc_demo_get_gallery_items' ) ? emc_demo_get_gallery_items() : array();
                    $idx = 0;
                    foreach ( $fallback_items as $fb ) :
                        $fb_url     = EMC_ASSETS . '/gallery/' . $fb['file'];
                        $size_class = isset( $size_classes[ $idx ] ) ? $size_classes[ $idx ] : '';
                        $delay      = round( fmod( $idx * 0.1, 0.3 ), 1 );
                        $cat_slug   = sanitize_title( $fb['category'] );
                    ?>
                    <div class="gallery-item <?php echo esc_attr( $size_class ); ?> scroll-reveal"
                         data-category="<?php echo esc_attr( $cat_slug ); ?>"
                         <?php echo $delay ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
                        <img src="<?php echo esc_url( $fb_url ); ?>" alt="<?php echo esc_attr( $fb['title'] ); ?>" loading="lazy">
                        <div class="gallery-overlay">
                            <span><?php echo esc_html( $fb['title'] ); ?></span>
                            <i class="fas fa-expand"></i>
                        </div>
                    </div>
                    <?php
                        $idx++;
                    endforeach;
                    ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab 3: News / Blog -->
        <div class="media-tab-panel" id="tab-news">
            <div class="section-header left">
                <h2><?php esc_html_e( 'Latest News & Updates', 'emc-theme' ); ?></h2>
            </div>

            <div class="news-grid">
                <?php
                $news_query = new WP_Query( array(
                    'post_type'      => 'post',
                    'posts_per_page' => 6,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );

                if ( $news_query->have_posts() ) :
                    $delay = 0;
                    while ( $news_query->have_posts() ) :
                        $news_query->the_post();
                        $cats     = get_the_category();
                        $cat_name = ! empty( $cats ) ? $cats[0]->name : __( 'News', 'emc-theme' );
                ?>
                <article class="news-card scroll-reveal"<?php echo $delay ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
                    <div class="news-img" <?php if ( has_post_thumbnail() ) : ?>style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'emc-card' ) ); ?>');"<?php else : ?>style="background: linear-gradient(135deg, #1A2B4C, #0E3020);"<?php endif; ?>>
                        <span class="news-category"><?php echo esc_html( $cat_name ); ?></span>
                    </div>
                    <div class="news-body">
                        <span class="news-date"><?php echo get_the_date(); ?></span>
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Read More', 'emc-theme' ); ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <?php
                        $delay = round( $delay + 0.1, 1 );
                    endwhile;
                    wp_reset_postdata();
                else :
                    $demo_news = array(
                        array( 'cat' => 'Announcement', 'date' => '12 May 2026', 'title' => 'Building Fund Reaches 60% Target',        'desc' => 'Alhamdulillah, through the generous support of our community, the campaign has now reached 60% of its target.' ),
                        array( 'cat' => 'Community',    'date' => '05 May 2026', 'title' => 'EMC Partners with Local Food Bank',        'desc' => 'A new partnership with the Chelmsford Food Bank to provide weekly collections and distribution support.' ),
                        array( 'cat' => 'Education',    'date' => '28 Apr 2026', 'title' => 'New Weekend Madrasah Curriculum', 'desc' => 'Our weekend Madrasah will roll out a modernized curriculum focusing on interactive learning.' ),
                    );
                    $delay = 0;
                    foreach ( $demo_news as $news ) :
                ?>
                <article class="news-card scroll-reveal"<?php echo $delay ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
                    <div class="news-img" style="background-image:url('<?php echo esc_url( EMC_ASSETS . '/gallery/Fundraising Event/20250316_17' . ( $delay < 0.15 ? '0418' : ( $delay < 0.25 ? '2759' : '3031' ) ) . '-300x225.jpg' ); ?>');background-size:cover;background-position:center;"><span class="news-category"><?php echo esc_html( $news['cat'] ); ?></span></div>
                    <div class="news-body">
                        <span class="news-date"><?php echo esc_html( $news['date'] ); ?></span>
                        <h3><?php echo esc_html( $news['title'] ); ?></h3>
                        <p><?php echo esc_html( $news['desc'] ); ?></p>
                        <a href="#" class="read-more"><?php esc_html_e( 'Read More', 'emc-theme' ); ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <?php
                        $delay = round( $delay + 0.1, 1 );
                    endforeach;
                endif;
                ?>
            </div>
        </div>

    </div>
</section>

<!-- Lightbox Modal -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" id="lightbox-close"><i class="fas fa-times"></i></button>
    <img src="" alt="<?php esc_attr_e( 'Gallery Preview', 'emc-theme' ); ?>" id="lightbox-img" class="lightbox-img">
    <div class="lightbox-caption" id="lightbox-caption"></div>
</div>

<?php get_footer(); ?>
