<?php
/**
 * Template Name: Elementor Canvas
 * Template Post Type: page
 *
 * Blank canvas — no header, no footer, no theme CSS.
 * Use for full-screen landing pages and popups built entirely in Elementor.
 *
 * @package emc-theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'emc-canvas-page' ); ?>>
<?php wp_body_open(); ?>

<main id="main-content">
    <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
