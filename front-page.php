<?php
/**
 * EMC Theme — front-page.php
 * Phase 4: All homepage sections are togglable via Customizer → Homepage Sections → Section Visibility.
 * @package emc-theme
 */

get_header();

// Helper: check section visibility
$show = function( $key, $default = true ) {
    return (bool) emc_option( 'emc_show_' . $key, $default );
};

// Hero is always shown
get_template_part( 'template-parts/sections/hero' );

// Prayer Times strip
if ( $show( 'prayer_strip' ) ) {
    get_template_part( 'template-parts/sections/prayer-times' );
}

// About
if ( $show( 'about' ) ) {
    get_template_part( 'template-parts/sections/about-intro' );
}

// Services
if ( $show( 'services' ) ) {
    get_template_part( 'template-parts/sections/services-preview' );
}

// Events
if ( $show( 'events' ) ) {
    get_template_part( 'template-parts/sections/events-preview' );
}

// Campaign
if ( $show( 'campaign' ) ) {
    get_template_part( 'template-parts/sections/campaign' );
}

// Stats / Counters (off by default)
if ( $show( 'counters', false ) ) {
    get_template_part( 'template-parts/sections/counters' );
}

// Team (off by default — shows only when CPT has content)
if ( $show( 'team', false ) ) {
    get_template_part( 'template-parts/sections/team' );
}

// Testimonials (off by default — shows only when CPT has content)
if ( $show( 'testimonials', false ) ) {
    get_template_part( 'template-parts/sections/testimonials' );
}

// FAQ (off by default — shows only when CPT has content)
if ( $show( 'faq', false ) ) {
    get_template_part( 'template-parts/sections/faq' );
}

// CTA Strip
if ( $show( 'cta' ) ) {
    get_template_part( 'template-parts/sections/cta-strip' );
}

// Media & News
if ( $show( 'media' ) ) {
    get_template_part( 'template-parts/sections/media-preview' );
}

// Newsletter
if ( $show( 'newsletter' ) ) {
    get_template_part( 'template-parts/sections/newsletter' );
}

get_footer();
