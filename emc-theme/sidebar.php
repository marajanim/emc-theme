<?php
/**
 * EMC Theme — sidebar.php
 * Blog sidebar: renders configured widgets if available, otherwise default widgets.
 * @package emc-theme
 */

if ( ! (bool) emc_option( 'emc_blog_show_sidebar', 1 ) ) {
    return;
}
?>
<aside class="blog-sidebar" aria-label="<?php esc_attr_e( 'Blog sidebar', 'emc-theme' ); ?>">

    <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-blog' ); ?>

    <?php else : ?>

        <!-- Search Widget -->
        <div class="sidebar-widget">
            <h3 class="sidebar-widget-title"><?php esc_html_e( 'Search', 'emc-theme' ); ?></h3>
            <?php get_search_form(); ?>
        </div>

        <!-- Recent Posts Widget -->
        <?php
        $recent = new WP_Query( array(
            'post_type'           => 'post',
            'posts_per_page'      => 5,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ) );
        if ( $recent->have_posts() ) :
        ?>
        <div class="sidebar-widget">
            <h3 class="sidebar-widget-title"><?php esc_html_e( 'Recent Posts', 'emc-theme' ); ?></h3>
            <ul class="sidebar-recent-posts">
                <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
                <li class="sidebar-recent-item">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="sidebar-recent-thumb" tabindex="-1" aria-hidden="true">
                        <?php the_post_thumbnail( array( 60, 60 ), array( 'loading' => 'lazy' ) ); ?>
                    </a>
                    <?php endif; ?>
                    <div class="sidebar-recent-body">
                        <a href="<?php the_permalink(); ?>" class="sidebar-recent-title"><?php the_title(); ?></a>
                        <time class="sidebar-recent-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </div>
                </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Categories Widget -->
        <?php
        $cats = get_categories( array( 'hide_empty' => true, 'number' => 10 ) );
        if ( $cats ) :
        ?>
        <div class="sidebar-widget">
            <h3 class="sidebar-widget-title"><?php esc_html_e( 'Categories', 'emc-theme' ); ?></h3>
            <ul class="sidebar-categories">
                <?php foreach ( $cats as $cat ) : ?>
                <li>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                        <?php echo esc_html( $cat->name ); ?>
                        <span class="sidebar-cat-count"><?php echo absint( $cat->count ); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Tags Widget -->
        <?php
        $tags = get_tags( array( 'hide_empty' => true, 'number' => 20, 'orderby' => 'count', 'order' => 'DESC' ) );
        if ( $tags ) :
        ?>
        <div class="sidebar-widget">
            <h3 class="sidebar-widget-title"><?php esc_html_e( 'Tags', 'emc-theme' ); ?></h3>
            <div class="sidebar-tags">
                <?php foreach ( $tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="sidebar-tag">
                    <?php echo esc_html( $tag->name ); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- About EMC Widget -->
        <div class="sidebar-widget sidebar-widget--about glass-card">
            <div class="sidebar-about-icon" aria-hidden="true">
                <i class="fas fa-mosque"></i>
            </div>
            <h3 class="sidebar-widget-title"><?php esc_html_e( 'About EMC', 'emc-theme' ); ?></h3>
            <p><?php echo esc_html( emc_option( 'emc_footer_about_text',
                __( 'Advancing Islamic faith, education, and community welfare in Chelmsford, Essex.', 'emc-theme' )
            ) ); ?></p>
            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn-outline btn-sm">
                <?php esc_html_e( 'Learn More', 'emc-theme' ); ?>
            </a>
        </div>

    <?php endif; ?>

</aside>
