<?php
/**
 * Template Part: Generic Post Content
 * Used by index.php for the blog listing loop.
 * @package emc-theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-entry' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="entry-thumbnail">
        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php the_post_thumbnail( 'emc-card', array( 'loading' => 'lazy' ) ); ?>
        </a>
    </div>
    <?php endif; ?>

    <div class="entry-body">
        <?php
        $cats = get_the_category();
        if ( $cats ) :
        ?>
        <span class="entry-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
        <?php endif; ?>

        <header class="entry-header">
            <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="entry-meta">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <i class="far fa-calendar" aria-hidden="true"></i>
                    <?php echo get_the_date(); ?>
                </time>
                <span>
                    <i class="far fa-user" aria-hidden="true"></i>
                    <?php the_author(); ?>
                </span>
            </div>
        </header>

        <div class="entry-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
            <?php esc_html_e( 'Read More', 'emc-theme' ); ?>
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
