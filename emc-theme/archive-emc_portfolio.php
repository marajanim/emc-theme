<?php
/**
 * EMC Theme — archive-emc_portfolio.php
 * Community projects archive with status + category filters.
 * @package emc-theme
 */

get_header();

$status_filter = isset( $_GET['project_status'] )
    ? sanitize_text_field( wp_unslash( $_GET['project_status'] ) )
    : '';
$allowed_statuses = array( 'ongoing', 'completed', 'planned' );
if ( $status_filter && ! in_array( $status_filter, $allowed_statuses, true ) ) {
    $status_filter = '';
}

$status_labels = array(
    'ongoing'   => __( 'Ongoing', 'emc-theme' ),
    'completed' => __( 'Completed', 'emc-theme' ),
    'planned'   => __( 'Planned', 'emc-theme' ),
);
?>

<section class="page-hero page-hero--portfolio" aria-label="<?php esc_attr_e( 'Projects archive', 'emc-theme' ); ?>">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-badge">
                <i class="fas fa-building" aria-hidden="true"></i>
                <?php esc_html_e( 'Community Impact', 'emc-theme' ); ?>
            </span>
            <h1><?php esc_html_e( 'Our Projects', 'emc-theme' ); ?></h1>
            <p><?php esc_html_e( 'Discover the projects we are delivering across the Chelmsford community.', 'emc-theme' ); ?></p>
        </div>
    </div>
</section>

<nav class="archive-filter-bar" aria-label="<?php esc_attr_e( 'Filter projects', 'emc-theme' ); ?>">
    <div class="container">
        <div class="filter-bar-row">

            <ul class="filter-tabs" role="list">
                <li>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'emc_portfolio' ) ); ?>"
                       class="filter-tab<?php echo ( ! $status_filter && ! is_tax() ) ? ' active' : ''; ?>">
                        <?php esc_html_e( 'All Projects', 'emc-theme' ); ?>
                    </a>
                </li>
                <?php foreach ( $status_labels as $val => $lbl ) :
                    $url = add_query_arg( 'project_status', $val, get_post_type_archive_link( 'emc_portfolio' ) );
                ?>
                <li>
                    <a href="<?php echo esc_url( $url ); ?>"
                       class="filter-tab<?php echo ( $status_filter === $val ) ? ' active' : ''; ?>">
                        <?php echo esc_html( $lbl ); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <?php
            $port_cats = get_terms( array( 'taxonomy' => 'portfolio_category', 'hide_empty' => true ) );
            if ( $port_cats && ! is_wp_error( $port_cats ) ) :
            ?>
            <ul class="filter-tabs filter-tabs--secondary" role="list">
                <?php foreach ( $port_cats as $cat ) : ?>
                <li>
                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
                       class="filter-tab<?php echo ( is_tax( 'portfolio_category', $cat ) ) ? ' active' : ''; ?>">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

        </div>
    </div>
</nav>

<section class="section-padding">
    <div class="container">
        <?php
        /* If status_filter is set, override the query */
        if ( $status_filter && have_posts() ) :
            /* Re-query with meta filter */
            $filtered = new WP_Query( array(
                'post_type'      => 'emc_portfolio',
                'posts_per_page' => 12,
                'meta_query'     => array(
                    array(
                        'key'   => '_emc_portfolio_status',
                        'value' => $status_filter,
                    ),
                ),
                'orderby' => 'date',
                'order'   => 'DESC',
            ) );
            $query_to_use = $filtered;
        else :
            $query_to_use = $wp_query;
        endif;

        if ( $query_to_use->have_posts() ) :
        ?>
        <div class="portfolio-cards-grid">
            <?php while ( $query_to_use->have_posts() ) :
                $query_to_use->the_post();
                $pf_status = get_post_meta( get_the_ID(), '_emc_portfolio_status',     true ) ?: 'ongoing';
                $pf_start  = get_post_meta( get_the_ID(), '_emc_portfolio_start_date', true );
                $pf_end    = get_post_meta( get_the_ID(), '_emc_portfolio_end_date',   true );
                $pf_cats   = get_the_terms( get_the_ID(), 'portfolio_category' );
            ?>
            <article class="portfolio-card glass-card" id="post-<?php the_ID(); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="portfolio-card-img" tabindex="-1" aria-hidden="true">
                    <?php the_post_thumbnail( 'emc-card' ); ?>
                </a>
                <?php endif; ?>
                <div class="portfolio-card-body">
                    <div class="portfolio-card-meta">
                        <span class="status-chip status-chip--<?php echo esc_attr( $pf_status ); ?>">
                            <?php echo esc_html( $status_labels[ $pf_status ] ?? ucfirst( $pf_status ) ); ?>
                        </span>
                        <?php if ( $pf_cats && ! is_wp_error( $pf_cats ) ) : ?>
                        <span class="portfolio-cat-label"><?php echo esc_html( $pf_cats[0]->name ); ?></span>
                        <?php endif; ?>
                    </div>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php the_excerpt(); ?>
                    <?php if ( $pf_start ) : ?>
                    <p class="portfolio-dates">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                        <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $pf_start ) ) );
                              if ( $pf_end ) {
                                  echo ' &ndash; ' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $pf_end ) ) );
                              } ?>
                    </p>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
                        <?php esc_html_e( 'View Project', 'emc-theme' ); ?>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php the_posts_pagination( array(
            'prev_text' => '<i class="fas fa-arrow-left" aria-hidden="true"></i> ' . __( 'Previous', 'emc-theme' ),
            'next_text' => __( 'Next', 'emc-theme' ) . ' <i class="fas fa-arrow-right" aria-hidden="true"></i>',
        ) ); ?>

        <?php else : ?>
        <div class="archive-no-results">
            <i class="fas fa-building" aria-hidden="true"></i>
            <h2><?php esc_html_e( 'No projects found', 'emc-theme' ); ?></h2>
            <p><?php esc_html_e( 'Projects will appear here once added. Check back soon.', 'emc-theme' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
