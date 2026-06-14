<?php
/**
 * Template Name: Landing Page (No Header / Footer)
 * Template Post Type: page
 *
 * Minimal page — theme CSS loads (so our design system is available),
 * but the site header and footer are suppressed.
 * Ideal for donation landing pages and campaign pages.
 *
 * @package emc-theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'emc-landing-page' ); ?>>
<?php wp_body_open(); ?>

<main id="main-content" class="landing-page-content">
    <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
