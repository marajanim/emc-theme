<?php
/**
 * Template Name: Full Width
 * Template Post Type: page, emc_event, emc_service, emc_portfolio, emc_case_study
 *
 * Full-width page — theme header and footer, but no container or padding constraints.
 * Ideal for pages built entirely with Elementor's drag-and-drop editor.
 *
 * @package emc-theme
 */

get_header();

while ( have_posts() ) :
    the_post();
    ?>
    <main id="main-content" class="full-width-page-content">
        <?php the_content(); ?>
    </main>
    <?php
endwhile;

get_footer();
