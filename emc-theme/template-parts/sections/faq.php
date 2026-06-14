<?php
/**
 * Template Part: FAQ Accordion Section
 * Driven by emc_faq CPT. Title = question, editor = answer.
 * Silently hidden if no FAQs are published.
 * @package emc-theme
 */

$heading    = emc_option( 'emc_faq_heading',    __( 'Frequently Asked Questions', 'emc-theme' ) );
$subheading = emc_option( 'emc_faq_subheading', __( 'Got Questions?', 'emc-theme' ) );
$per_page   = max( 1, (int) emc_option( 'emc_faq_per_page', 6 ) );

$faq_query = new WP_Query( array(
    'post_type'      => 'emc_faq',
    'posts_per_page' => $per_page,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
) );

if ( ! $faq_query->have_posts() ) {
    return;
}
?>
<section class="faq-section section-padding" id="faq" aria-labelledby="faq-heading">
    <div class="container">
        <div class="faq-layout">

            <div class="section-header faq-header scroll-reveal">
                <span class="subtitle"><?php echo esc_html( $subheading ); ?></span>
                <h2 id="faq-heading"><?php echo esc_html( $heading ); ?></h2>
                <p><?php esc_html_e( 'Find answers to common questions about our services, prayer times, and how to get involved.', 'emc-theme' ); ?></p>
            </div>

            <div class="faq-accordion" id="faq-accordion">
                <?php
                $index = 0;
                while ( $faq_query->have_posts() ) : $faq_query->the_post();
                    $faq_id = 'faq-item-' . get_the_ID();
                    $panel_id = 'faq-panel-' . get_the_ID();
                ?>
                <div class="faq-item scroll-reveal" style="transition-delay:<?php echo esc_attr( $index * 0.05 ); ?>s">
                    <button
                        class="faq-question<?php echo $index === 0 ? ' active' : ''; ?>"
                        id="<?php echo esc_attr( $faq_id ); ?>"
                        aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                    >
                        <span><?php the_title(); ?></span>
                        <i class="fas fa-chevron-down faq-icon" aria-hidden="true"></i>
                    </button>
                    <div
                        class="faq-answer<?php echo $index === 0 ? ' open' : ''; ?>"
                        id="<?php echo esc_attr( $panel_id ); ?>"
                        role="region"
                        aria-labelledby="<?php echo esc_attr( $faq_id ); ?>"
                        <?php echo $index !== 0 ? 'hidden' : ''; ?>
                    >
                        <div class="faq-answer-inner">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
                <?php
                $index++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>
