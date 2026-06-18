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

/* ==========================================================================
   Stripe Configuration Helpers
   Keys are stored in WordPress options (set via WP Admin > Settings > EMC Stripe).
   For production, override in wp-config.php:
       define( 'EMC_STRIPE_SECRET_KEY', 'sk_live_...' );
       define( 'EMC_STRIPE_PUB_KEY',    'pk_live_...' );
   ========================================================================== */
function emc_stripe_secret_key() {
    if ( defined( 'EMC_STRIPE_SECRET_KEY' ) ) return EMC_STRIPE_SECRET_KEY;
    return get_option( 'emc_stripe_secret_key', '' );
}
function emc_stripe_pub_key() {
    if ( defined( 'EMC_STRIPE_PUB_KEY' ) ) return EMC_STRIPE_PUB_KEY;
    return get_option( 'emc_stripe_pub_key', '' );
}

/* ==========================================================================
   Stripe - Create PaymentIntent
   ========================================================================== */
function emc_stripe_create_intent() {
    check_ajax_referer( 'emc_donate_nonce', 'nonce' );
    $amount = absint( $_POST['amount'] ?? 0 );
    $fund   = sanitize_text_field( $_POST['fund'] ?? 'General Fund' );
    $tab    = sanitize_text_field( $_POST['tab']  ?? 'one-off' );
    if ( $amount < 50 ) {
        wp_send_json_error( array( 'message' => 'Minimum donation is £0.50.' ) );
    }
    $response = wp_remote_post( 'https://api.stripe.com/v1/payment_intents', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . emc_stripe_secret_key(),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'amount'                             => $amount,
            'currency'                           => 'gbp',
            'automatic_payment_methods[enabled]' => 'true',
            'description'                        => 'Donation to Essex Muslim Centre - ' . $fund,
            'metadata[fund]'                     => $fund,
            'metadata[tab]'                      => $tab,
            'metadata[source]'                   => 'EMC Website',
        ),
        'timeout' => 20,
    ) );
    if ( is_wp_error( $response ) ) {
        wp_send_json_error( array( 'message' => 'Payment gateway unavailable. Please try again.' ) );
    }
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! empty( $body['error'] ) ) {
        wp_send_json_error( array( 'message' => $body['error']['message'] ?? 'A payment error occurred.' ) );
    }
    wp_send_json_success( array( 'client_secret' => $body['client_secret'], 'pi_id' => $body['id'] ) );
}
add_action( 'wp_ajax_emc_stripe_create_intent',        'emc_stripe_create_intent' );
add_action( 'wp_ajax_nopriv_emc_stripe_create_intent', 'emc_stripe_create_intent' );

/* ==========================================================================
   Stripe - Record Successful Donation
   ========================================================================== */
function emc_stripe_record_donation() {
    check_ajax_referer( 'emc_donate_nonce', 'nonce' );
    $pi_id    = sanitize_text_field( $_POST['pi_id']   ?? '' );
    $amount   = absint( $_POST['amount']               ?? 0 );
    $fund     = sanitize_text_field( $_POST['fund']    ?? 'General Fund' );
    $name     = sanitize_text_field( $_POST['name']    ?? '' );
    $email    = sanitize_email(      $_POST['email']   ?? '' );
    $gift_aid = filter_var( $_POST['gift_aid'] ?? false, FILTER_VALIDATE_BOOLEAN );
    $message  = sanitize_textarea_field( $_POST['message'] ?? '' );
    if ( empty( $pi_id ) ) {
        wp_send_json_error( array( 'message' => 'Invalid payment reference.' ) );
    }
    // Verify with Stripe before recording
    $check   = wp_remote_get( 'https://api.stripe.com/v1/payment_intents/' . urlencode( $pi_id ),
        array( 'headers' => array( 'Authorization' => 'Bearer ' . emc_stripe_secret_key() ), 'timeout' => 15 ) );
    $pi_data = json_decode( wp_remote_retrieve_body( $check ), true );
    if ( empty( $pi_data['status'] ) || $pi_data['status'] !== 'succeeded' ) {
        wp_send_json_error( array( 'message' => 'Payment not confirmed. Contact us if funds were charged.' ) );
    }
    $amount_gbp = number_format( $amount / 100, 2 );
    // Log
    $log   = get_option( 'emc_donations_log', array() );
    $log[] = array( 'pi_id' => $pi_id, 'amount' => $amount_gbp, 'fund' => $fund,
                    'name' => $name ?: 'Anonymous', 'email' => $email, 'gift_aid' => $gift_aid,
                    'message' => $message, 'date' => current_time( 'Y-m-d H:i:s' ) );
    update_option( 'emc_donations_log', array_slice( $log, -1000 ) );
    // Admin email
    $tbody = '<h2>New Donation 🎉</h2><table style="font-family:sans-serif;font-size:14px;">'
           . '<tr><td><strong>Amount</strong></td><td>£' . esc_html($amount_gbp) . '</td></tr>'
           . '<tr><td><strong>Fund</strong></td><td>'   . esc_html($fund)        . '</td></tr>'
           . '<tr><td><strong>Donor</strong></td><td>'  . esc_html($name ?: 'Anonymous') . '</td></tr>'
           . '<tr><td><strong>Email</strong></td><td>'  . esc_html($email ?: 'N/A') . '</td></tr>'
           . '<tr><td><strong>Gift Aid</strong></td><td>' . ($gift_aid ? 'Yes' : 'No') . '</td></tr>'
           . '<tr><td><strong>Message</strong></td><td>' . esc_html($message ?: '—') . '</td></tr>'
           . '<tr><td><strong>Stripe PI</strong></td><td><code>' . esc_html($pi_id) . '</code></td></tr>'
           . '<tr><td><strong>Date</strong></td><td>'   . current_time('j F Y, H:i') . '</td></tr></table>';
    wp_mail( get_option('admin_email'), 'New Donation - £' . $amount_gbp . ' - ' . $fund,
             $tbody, array('Content-Type: text/html; charset=UTF-8') );
    // Donor receipt
    if ( is_email( $email ) ) {
        $donor_body = '<div style="font-family:sans-serif;max-width:520px;">'
            . '<h2 style="color:#0c7a6d;">JazakAllahu Khayran, ' . esc_html($name ?: 'dear donor') . '!</h2>'
            . '<p>Your donation of <strong>£' . esc_html($amount_gbp) . '</strong> to the <strong>'
            . esc_html($fund) . '</strong> has been received.</p>'
            . '<p style="font-size:12px;color:#999;">Stripe Ref: <code>' . esc_html($pi_id) . '</code></p>'
            . '<p>May Allah reward you abundantly.</p>'
            . '<hr><p style="font-size:11px;color:#aaa;">Essex Muslim Centre · Charity No. '
            . esc_html(get_option('emc_charity_number','1209815')) . '</p></div>';
        wp_mail( $email, 'Thank you for your donation - Essex Muslim Centre',
                 $donor_body, array('Content-Type: text/html; charset=UTF-8') );
    }
    wp_send_json_success( array( 'message' => 'Donation recorded. Jazakallahu Khayran!' ) );
}
add_action( 'wp_ajax_emc_stripe_record',        'emc_stripe_record_donation' );
add_action( 'wp_ajax_nopriv_emc_stripe_record', 'emc_stripe_record_donation' );
