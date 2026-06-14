<?php
/**
 * EMC Theme — inc/elementor-compat.php
 * Elementor compatibility: theme locations, style fixes, breakpoints, body classes.
 * Phase 7: Safe to load regardless of whether Elementor is installed.
 *
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;

/* ==========================================================================
   Theme Location Registration (Elementor Pro)
   ========================================================================== */

/**
 * Register header, footer, single, and archive as Elementor theme locations.
 * Elementor Pro users can then create templates assigned to these locations.
 */
add_action( 'elementor/theme/register_locations', function( $manager ) {
    $manager->register_all_core_location();
} );


/* ==========================================================================
   Body Classes
   ========================================================================== */
add_filter( 'body_class', function( $classes ) {
    if ( ! did_action( 'elementor/loaded' ) ) {
        return $classes;
    }

    $classes[] = 'elementor-theme-emc';

    if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
        $classes[] = 'emc-elementor-preview';
    }

    return $classes;
} );


/* ==========================================================================
   Enqueue Elementor Compatibility CSS
   ========================================================================== */
add_action( 'wp_enqueue_scripts', function() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }
    wp_enqueue_style(
        'emc-elementor',
        EMC_ASSETS . '/css/elementor.css',
        array( 'emc-style' ),
        EMC_VERSION
    );
}, 20 );


/* ==========================================================================
   Style Dequeue on Canvas Template
   ========================================================================== */
add_action( 'wp_enqueue_scripts', function() {
    if ( ! is_singular() ) {
        return;
    }
    $tpl = get_page_template_slug();
    if ( 'page-templates/elementor-canvas.php' === $tpl ) {
        wp_dequeue_style( 'emc-style' );
        wp_dequeue_style( 'emc-elementor' );
    }
}, 99 );


/* ==========================================================================
   Elementor Editor: Make Theme CSS Variables Available
   ========================================================================== */
add_action( 'elementor/editor/before_enqueue_styles', function() {
    wp_enqueue_style( 'emc-style' );
} );


/* ==========================================================================
   Custom Widget Category
   ========================================================================== */
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
    $elements_manager->add_category(
        'emc-widgets',
        array(
            'title' => __( 'Essex Muslim Centre', 'emc-theme' ),
            'icon'  => 'fa fa-mosque',
        )
    );
} );


/* ==========================================================================
   Prevent Elementor Kit from Overriding Our Font Variables
   ========================================================================== */
add_filter( 'elementor/fonts/additional_fonts', function( $fonts ) {
    $fonts['Poppins']   = 'google';
    $fonts['Playfair Display'] = 'google';
    return $fonts;
} );


/* ==========================================================================
   Admin: Show Elementor Edit Button on CPT Singular Pages
   ========================================================================== */
add_filter( 'elementor/utils/is_post_support', function( $is_supported, $post_id, $post_type ) {
    $elementor_cpts = array( 'emc_event', 'emc_service', 'emc_portfolio', 'emc_case_study', 'emc_vacancy' );
    if ( in_array( $post_type, $elementor_cpts, true ) ) {
        return true;
    }
    return $is_supported;
}, 10, 3 );
