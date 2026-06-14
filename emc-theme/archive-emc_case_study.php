<?php
/**
 * EMC Theme — archive-emc_case_study.php
 * Impact Stories archive — showcases community outcomes.
 * @package emc-theme
 */

get_header();
?>

<section class="page-hero page-hero--case-studies" aria-label="<?php esc_attr_e( 'Impact Stories archive', 'emc-theme' ); ?>">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-badge">
                <i class="fas fa-star" aria-hidden="true"></i>
                <?php esc_html_e( 'Community Impact', 'emc-theme' ); ?>
            </span>
            <h1><?php esc_html_e( 'Impact Stories', 'emc-theme' ); ?></h1>
            <p><?php esc_html_e( 'Real stories of how Essex Muslim Centre has made a difference to lives across our community.', 'emc-theme' ); ?></p>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">

        <?php if ( have_posts() ) : ?>
        <div class="case-study-archive-grid">
            <?php while ( have_posts() ) :
                the_post();
                $cs_date = get_post_meta( get_the_ID(), '_emc_case_study_date', true );

                $cs_stats = array();
                foreach ( array( 1, 2, 3 ) as $n ) {
                    $num = get_post_meta( get_the_ID(), "_emc_case_study_stat{$n}_num",   true );
                    $lbl = get_post_meta( get_the_ID(), "_emc_case_study_stat{$n}_label", true );
                    if ( $num && $lbl ) {
                        $cs_stats[] = array( 'num' => $num, 'label' => $lbl );
                    }
                }
            ?>
            <article class="case-study-archive-card glass-card" id="post-<?php the_ID(); ?>">

                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="case-study-archive-img" tabindex="-1" aria-hidden="true">
                    <?php the_post_thumbnail( 'emc-card' ); ?>
                </a>
                <?php endif; ?>

                <?php if ( $cs_stats ) : ?>
                <div class="case-study-stats-strip" aria-label="<?php esc_attr_e( 'Key statistics', 'emc-theme' ); ?>">
                    <?php foreach ( $cs_stats as $stat ) : ?>
                    <div class="cs-stat-item">
                        <strong class="cs-stat-num"><?php echo esc_html( $stat['num'] ); ?></strong>
                        <span class="cs-stat-lbl"><?php echo esc_html( $stat['label'] ); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="case-study-archive-body">
                    <?php if ( $cs_date ) : ?>
                    <time class="case-study-date" datetime="<?php echo esc_attr( $cs_date ); ?>">
                        <i class="fas fa-calendar" aria-hidden="true"></i>
                        <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $cs_date ) ) ); ?>
                    </time>
                    <?php endif; ?>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
                        <?php esc_html_e( 'Read the Story', 'emc-theme' ); ?>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>

            </article>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination( array(
            'prev_text' => '<i class="fas fa-arrow-left" aria-hidden="true"></i> ' . __( 'Previous', 'emc-theme' ),
            'next_text' => __( 'Next', 'emc-theme' ) . ' <i class="fas fa-arrow-right" aria-hidden="true"></i>',
        ) ); ?>

        <?php else : ?>
        <div class="archive-no-results">
            <i class="fas fa-star" aria-hidden="true"></i>
            <h2><?php esc_html_e( 'No impact stories yet', 'emc-theme' ); ?></h2>
            <p><?php esc_html_e( 'Stories of community impact will appear here. Check back soon.', 'emc-theme' ); ?></p>
        </div>
        <?php endif; ?>

    </div>
</section>

<section class="cta-strip section-padding">
    <div class="container">
        <div class="cta-strip-inner scroll-reveal">
            <div class="cta-strip-icon" aria-hidden="true">
                <i class="fas fa-hands-heart"></i>
            </div>
            <div class="cta-strip-text">
                <h2><?php esc_html_e( 'Be Part of the Story', 'emc-theme' ); ?></h2>
                <p><?php esc_html_e( 'Your support helps us create more impact stories in our community.', 'emc-theme' ); ?></p>
            </div>
            <div class="cta-strip-actions">
                <?php echo emc_donate_button( __( 'Donate Now', 'emc-theme' ), 'btn btn-primary' ); ?>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline">
                    <?php esc_html_e( 'Get Involved', 'emc-theme' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
