<?php
/**
 * EMC Theme — single-emc_portfolio.php
 * Single community project / portfolio template.
 * @package emc-theme
 */

get_header();

while ( have_posts() ) :
    the_post();

    $status     = get_post_meta( get_the_ID(), '_emc_portfolio_status',     true ) ?: 'ongoing';
    $start_date = get_post_meta( get_the_ID(), '_emc_portfolio_start_date', true );
    $end_date   = get_post_meta( get_the_ID(), '_emc_portfolio_end_date',   true );
    $ext_link   = get_post_meta( get_the_ID(), '_emc_portfolio_link',       true );
    $cats       = get_the_terms( get_the_ID(), 'portfolio_category' );
    $cat_name   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';

    $status_labels = array(
        'ongoing'   => __( 'Ongoing', 'emc-theme' ),
        'completed' => __( 'Completed', 'emc-theme' ),
        'planned'   => __( 'Planned', 'emc-theme' ),
    );
    $status_colors = array(
        'ongoing'   => 'var(--primary-green)',
        'completed' => 'var(--deep-blue)',
        'planned'   => 'var(--accent-gold)',
    );
    $status_label = $status_labels[ $status ] ?? ucfirst( $status );
    $status_color = $status_colors[ $status ] ?? 'var(--primary-green)';
?>

<section class="page-hero page-hero--portfolio" aria-label="<?php the_title_attribute(); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="page-hero-bg" style="background-image:url(<?php echo esc_url( get_the_post_thumbnail_url( null, 'emc-hero' ) ); ?>)" aria-hidden="true"></div>
    <div class="page-hero-overlay" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="container">
        <div class="page-hero-content">
            <?php if ( $cat_name ) : ?>
            <span class="page-hero-badge">
                <i class="fas fa-folder-open" aria-hidden="true"></i>
                <?php echo esc_html( $cat_name ); ?>
            </span>
            <?php endif; ?>
            <h1><?php the_title(); ?></h1>
            <div class="page-hero-meta">
                <span class="project-status-badge" style="background:<?php echo esc_attr( $status_color ); ?>">
                    <?php echo esc_html( $status_label ); ?>
                </span>
                <?php if ( $start_date ) : ?>
                <span>
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ) );
                          if ( $end_date ) {
                              echo ' &ndash; ' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $end_date ) ) );
                          } ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="portfolio-single-layout">

            <article class="portfolio-single-content prose">
                <?php the_content(); ?>

                <?php if ( $ext_link ) : ?>
                <p>
                    <a href="<?php echo esc_url( $ext_link ); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                        <?php esc_html_e( 'View Project', 'emc-theme' ); ?>
                    </a>
                </p>
                <?php endif; ?>

                <div class="single-back-link">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'emc_portfolio' ) ); ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        <?php esc_html_e( 'All Projects', 'emc-theme' ); ?>
                    </a>
                </div>
            </article>

            <aside class="portfolio-single-sidebar">
                <div class="project-details-card glass-card">
                    <h3><?php esc_html_e( 'Project Details', 'emc-theme' ); ?></h3>
                    <ul class="project-details-list">
                        <li>
                            <i class="fas fa-info-circle" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Status', 'emc-theme' ); ?></strong>
                                <span style="color:<?php echo esc_attr( $status_color ); ?>;font-weight:600">
                                    <?php echo esc_html( $status_label ); ?>
                                </span>
                            </div>
                        </li>
                        <?php if ( $start_date ) : ?>
                        <li>
                            <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Start Date', 'emc-theme' ); ?></strong>
                                <span><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ) ); ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( $end_date ) : ?>
                        <li>
                            <i class="fas fa-calendar-check" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'End Date', 'emc-theme' ); ?></strong>
                                <span><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $end_date ) ) ); ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
                        <li>
                            <i class="fas fa-folder" aria-hidden="true"></i>
                            <div>
                                <strong><?php esc_html_e( 'Category', 'emc-theme' ); ?></strong>
                                <span><?php echo esc_html( implode( ', ', wp_list_pluck( $cats, 'name' ) ) ); ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <?php if ( $ext_link ) : ?>
                    <a href="<?php echo esc_url( $ext_link ); ?>" class="btn btn-outline btn-block" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                        <?php esc_html_e( 'Project Website', 'emc-theme' ); ?>
                    </a>
                    <?php endif; ?>
                </div>

                <div class="project-support-card glass-card" style="margin-top:1.5rem">
                    <p><?php esc_html_e( 'Help us continue delivering impactful community projects.', 'emc-theme' ); ?></p>
                    <?php echo emc_donate_button( __( 'Support This Work', 'emc-theme' ), 'btn btn-primary btn-block' ); ?>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php
    $related = new WP_Query( array(
        'post_type'      => 'emc_portfolio',
        'posts_per_page' => 3,
        'post__not_in'   => array( get_the_ID() ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );
    if ( $related->have_posts() ) :
?>
<section class="section-padding related-cpt-section">
    <div class="container">
        <h2 class="section-heading"><?php esc_html_e( 'More Projects', 'emc-theme' ); ?></h2>
        <div class="portfolio-cards-grid">
            <?php while ( $related->have_posts() ) : $related->the_post();
                $r_status = get_post_meta( get_the_ID(), '_emc_portfolio_status', true ) ?: 'ongoing';
            ?>
            <article class="portfolio-card glass-card">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="portfolio-card-img" tabindex="-1" aria-hidden="true">
                    <?php the_post_thumbnail( 'emc-thumbnail' ); ?>
                </a>
                <?php endif; ?>
                <div class="portfolio-card-body">
                    <span class="status-chip status-chip--<?php echo esc_attr( $r_status ); ?>">
                        <?php echo esc_html( $status_labels[ $r_status ] ?? ucfirst( $r_status ) ); ?>
                    </span>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php the_excerpt(); ?>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
