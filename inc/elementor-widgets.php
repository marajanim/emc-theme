<?php
/**
 * EMC Theme — inc/elementor-widgets.php
 * Custom Elementor widgets: Donate Button, Prayer Times Compact, Stats Counter.
 * Phase 7: Only registers when Elementor is active.
 *
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;

/* ==========================================================================
   Register Widgets on Elementor's Init Hook
   ========================================================================== */
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    require_once __DIR__ . '/elementor/widget-donate-button.php';
    require_once __DIR__ . '/elementor/widget-prayer-times.php';
    require_once __DIR__ . '/elementor/widget-stats-counter.php';

    $widgets_manager->register( new EMC_Widget_Donate_Button() );
    $widgets_manager->register( new EMC_Widget_Prayer_Times() );
    $widgets_manager->register( new EMC_Widget_Stats_Counter() );
} );
