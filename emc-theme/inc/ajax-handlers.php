<?php
/**
 * EMC Theme — inc/ajax-handlers.php
 * Server-side AJAX handlers for contact form and newsletter.
 *
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;

/* ==========================================================================
   Contact Form Handler
   ========================================================================== */
function emc_handle_contact_form() {
    check_ajax_referer( 'emc_nonce', 'nonce' );

    $name    = sanitize_text_field( $_POST['name']    ?? '' );
    $email   = sanitize_email(      $_POST['email']   ?? '' );
    $subject = sanitize_text_field( $_POST['subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'emc-theme' ) ) );
    }

    $to      = get_theme_mod( 'emc_admin_email', get_option( 'admin_email' ) );
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . esc_html( $name ) . ' <' . $email . '>',
        'Reply-To: ' . $email,
    );

    $body  = '<h2>' . esc_html( $subject ) . '</h2>';
    $body .= '<p><strong>Name:</strong> ' . esc_html( $name ) . '</p>';
    $body .= '<p><strong>Email:</strong> ' . esc_html( $email ) . '</p>';
    $body .= '<p><strong>Message:</strong><br>' . nl2br( esc_html( $message ) ) . '</p>';

    $sent = wp_mail( $to, 'EMC Website Enquiry: ' . $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => __( 'Message sent successfully. We will be in touch shortly.', 'emc-theme' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Sorry, your message could not be sent. Please email us directly.', 'emc-theme' ) ) );
    }
}
add_action( 'wp_ajax_emc_contact',             'emc_handle_contact_form' );
add_action( 'wp_ajax_nopriv_emc_contact',      'emc_handle_contact_form' );
// Phase 10 JS uses 'emc_contact_form' — alias to same handler
add_action( 'wp_ajax_emc_contact_form',        'emc_handle_contact_form' );
add_action( 'wp_ajax_nopriv_emc_contact_form', 'emc_handle_contact_form' );


/* ==========================================================================
   Newsletter Subscribe Handler
   ========================================================================== */
function emc_handle_newsletter() {
    check_ajax_referer( 'emc_nonce', 'nonce' );

    $email = sanitize_email( $_POST['email'] ?? '' );

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'emc-theme' ) ) );
    }

    /**
     * TODO (Phase 2+): integrate with Mailchimp or equivalent.
     * For now, email the admin and log the subscriber.
     */
    $to      = get_option( 'admin_email' );
    $subject = 'New Newsletter Subscriber — EMC Website';
    $body    = '<p>A new visitor has subscribed to the newsletter: <strong>' . esc_html( $email ) . '</strong></p>';
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    wp_mail( $to, $subject, $body, $headers );

    wp_send_json_success( array( 'message' => __( "You're subscribed! Jazakallahu Khayran.", 'emc-theme' ) ) );
}
add_action( 'wp_ajax_emc_newsletter',             'emc_handle_newsletter' );
add_action( 'wp_ajax_nopriv_emc_newsletter',      'emc_handle_newsletter' );
// Phase 10 JS uses 'emc_newsletter_subscribe' — alias to same handler
add_action( 'wp_ajax_emc_newsletter_subscribe',        'emc_handle_newsletter' );
add_action( 'wp_ajax_nopriv_emc_newsletter_subscribe', 'emc_handle_newsletter' );


/* ==========================================================================
   Gift Aid Declaration Handler
   ========================================================================== */
function emc_handle_gift_aid() {
    check_ajax_referer( 'emc_nonce', 'nonce' );

    $first    = sanitize_text_field( $_POST['first']    ?? '' );
    $last     = sanitize_text_field( $_POST['last']     ?? '' );
    $email    = sanitize_email(      $_POST['email']    ?? '' );
    $address  = sanitize_text_field( $_POST['address']  ?? '' );
    $postcode = sanitize_text_field( $_POST['postcode'] ?? '' );

    if ( ! $first || ! $last || ! is_email( $email ) || ! $address || ! $postcode ) {
        wp_send_json_error( array( 'message' => __( 'Please complete all required fields.', 'emc-theme' ) ) );
    }

    $to      = get_theme_mod( 'emc_admin_email', get_option( 'admin_email' ) );
    $subject = 'New Gift Aid Declaration — ' . $first . ' ' . $last;
    $body    = '<h2>New Gift Aid Declaration</h2>'
             . '<p><strong>Name:</strong> ' . esc_html( $first . ' ' . $last ) . '</p>'
             . '<p><strong>Email:</strong> ' . esc_html( $email ) . '</p>'
             . '<p><strong>Address:</strong> ' . esc_html( $address ) . ', ' . esc_html( $postcode ) . '</p>'
             . '<p><strong>Date Submitted:</strong> ' . current_time( 'Y-m-d H:i:s' ) . '</p>';
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => __( 'Declaration received. Jazakallahu Khayran!', 'emc-theme' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Submission failed. Please email us directly.', 'emc-theme' ) ) );
    }
}
add_action( 'wp_ajax_emc_gift_aid',        'emc_handle_gift_aid' );
add_action( 'wp_ajax_nopriv_emc_gift_aid', 'emc_handle_gift_aid' );
