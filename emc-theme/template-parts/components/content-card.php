<?php
/**
 * Template Part: Post Card
 * Used in archive.php and search.php grids.
 * @package emc-theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-preview-card scroll-reveal' ); ?>>
    <a href="<?php the_permalink(); ?>" class="news-preview-img-link" tabindex="-1" aria-hidden="true">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="news-preview-img" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'emc-thumbnail' ) ); ?>');" role="img" aria-label="<?php the_title_attribute(); ?>"></div>
        <?php else : ?>
            <div class="news-preview-img" style="background-image:url('<?php echo esc_url( EMC_ASSETS . '/gallery/Community Support Services/New-Muslim-600x338.jpeg' ); ?>');background-size:cover;background-position:center;" role="img" aria-label="<?php the_title_attribute(); ?>"></div>
        <?php endif; ?>
    </a>

    <div class="news-preview-body">
        <div class="news-preview-meta">
            <?php
            $cats = get_the_category();
            if ( $cats ) :
            ?>
            <span class="news-preview-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
            <?php endif; ?>
            <time class="news-preview-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                <?php echo get_the_date(); ?>
            </time>
        </div>

        <h2 class="news-preview-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>

        <p><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>

        <a href="<?php the_permalink(); ?>" class="news-preview-link">
            <?php esc_html_e( 'Read More', 'emc-theme' ); ?>
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
