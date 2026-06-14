<?php
/**
 * EMC Theme — single-emc_case_study.php
 * Single impact story / case study template.
 * @package emc-theme
 */

get_header();

while ( have_posts() ) :
    the_post();

    $date     = get_post_meta( get_the_ID(), '_emc_case_study_date', true );
    $ext_link = get_post_meta( get_the_ID(), '_emc_case_study_link', true );

    $stats = array();
    foreach ( array( 1, 2, 3 ) as $n ) {
        $num = get_post_meta( get_the_ID(), "_emc_case_study_stat{$n}_num",   true );
        $lbl = get_post_meta( get_the_ID(), "_emc_case_study_stat{$n}_label", true );
        if ( $num && $lbl ) {
            $stats[] = array( 'num' => $num, 'label' => $lbl );
        }
    }

    $formatted_date = $date ? date_i18n( get_option( 'date_format' ), strtotime( $date ) ) : '';
?>

<section class="page-hero page-hero--case-study" aria-label="<?php the_title_attribute(); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="page-hero-bg" style="background-image:url(<?php echo esc_url( get_the_post_thumbnail_url( null, 'emc-hero' ) ); ?>)" aria-hidden="true"></div>
    <div class="page-hero-overlay" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-badge">
                <i class="fas fa-star" aria-hidden="true"></i>
                <?php esc_html_e( 'Impact Story', 'emc-theme' ); ?>
            </span>
            <h1><?php the_title(); ?></h1>
            <?php if ( $formatted_date ) : ?>
            <p class="page-hero-meta">
                <i class="fas fa-calendar" aria-hidden="true"></i>
                <?php echo esc_html( $formatted_date ); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ( $stats ) : ?>
<section class="case-study-stats-bar" aria-label="<?php esc_attr_e( 'Key impact statistics', 'emc-theme' ); ?>">
    <div class="container">
        <div class="case-study-stats-grid">
            <?php foreach ( $stats as $stat ) : ?>
            <div class="case-study-stat scroll-reveal">
                <span class="stat-number"><?php echo esc_html( $stat['num'] ); ?></span>
                <span class="stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section-padding">
    <div class="container">
        <div class="case-study-single-layout">

            <article class="case-study-content prose">
                <?php the_content(); ?>

                <?php if ( $ext_link ) : ?>
                <p>
                    <a href="<?php echo esc_url( $ext_link ); ?>" class="btn btn-outline" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                        <?php esc_html_e( 'Learn More', 'emc-theme' ); ?>
                    </a>
                </p>
                <?php endif; ?>

                <div class="single-back-link">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'emc_case_study' ) ); ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        <?php esc_html_e( 'All Impact Stories', 'emc-theme' ); ?>
                    </a>
                </div>
            </article>

            <aside class="case-study-sidebar">
                <div class="case-study-share-card glass-card">
                    <h3><?php esc_html_e( 'Share This Story', 'emc-theme' ); ?></h3>
                    <p><?php esc_html_e( 'Help spread the word about our community impact.', 'emc-theme' ); ?></p>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>"
                           class="share-btn share-btn--facebook" target="_blank" rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e( 'Share on Facebook', 'emc-theme' ); ?>">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
                           class="share-btn share-btn--twitter" target="_blank" rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e( 'Share on X', 'emc-theme' ); ?>">
                            <i class="fab fa-x-twitter" aria-hidden="true"></i> X
                        </a>
                        <a href="https://wa.me/?text=<?php echo rawurlencode( get_the_title() . ' ' . get_permalink() ); ?>"
                           class="share-btn share-btn--whatsapp" target="_blank" rel="noopener noreferrer"
                           aria-label="<?php esc_attr_e( 'Share on WhatsApp', 'emc-theme' ); ?>">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp
                        </a>
                    </div>
                </div>

                <div class="case-study-donate-card glass-card" style="margin-top:1.5rem">
                    <p><?php esc_html_e( 'Stories like this are made possible by your generosity. Support us today.', 'emc-theme' ); ?></p>
                    <?php echo emc_donate_button( __( 'Make a Difference', 'emc-theme' ), 'btn btn-primary btn-block' ); ?>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php
    $related = new WP_Query( array(
        'post_type'      => 'emc_case_study',
        'posts_per_page' => 3,
        'post__not_in'   => array( get_the_ID() ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );
    if ( $related->have_posts() ) :
?>
<section class="section-padding related-cpt-section">
    <div class="container">
        <h2 class="section-heading"><?php esc_html_e( 'More Impact Stories', 'emc-theme' ); ?></h2>
        <div class="case-study-cards-grid">
            <?php while ( $related->have_posts() ) : $related->the_post();
                $r_date = get_post_meta( get_the_ID(), '_emc_case_study_date', true );
                $r_s1n  = get_post_meta( get_the_ID(), '_emc_case_study_stat1_num', true );
                $r_s1l  = get_post_meta( get_the_ID(), '_emc_case_study_stat1_label', true );
            ?>
            <article class="case-study-card glass-card">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="case-study-card-img" tabindex="-1" aria-hidden="true">
                    <?php the_post_thumbnail( 'emc-thumbnail' ); ?>
                </a>
                <?php endif; ?>
                <div class="case-study-card-body">
                    <?php if ( $r_s1n && $r_s1l ) : ?>
                    <div class="case-study-card-stat">
                        <strong><?php echo esc_html( $r_s1n ); ?></strong>
                        <span><?php echo esc_html( $r_s1l ); ?></span>
                    </div>
                    <?php endif; ?>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php if ( $r_date ) : ?>
                    <time datetime="<?php echo esc_attr( $r_date ); ?>">
                        <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $r_date ) ) ); ?>
                    </time>
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
