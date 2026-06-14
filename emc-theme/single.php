<?php
/**
 * EMC Theme — single.php
 * Single blog post template.
 * @package emc-theme
 */

get_header();

while ( have_posts() ) :
    the_post();
    get_template_part( 'template-parts/components/content', 'single' );
endwhile;

// Related posts appear outside the article loop
get_template_part( 'template-parts/blog/related-posts' );

get_footer();
