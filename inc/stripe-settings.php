<?php
/**
 * EMC Theme — inc/stripe-settings.php
 * WordPress admin settings page for Stripe API keys.
 * Keys are stored encrypted in wp_options and NEVER committed to version control.
 *
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------------
   Keys are configured in ONE of two ways (priority order):
   1. Constants in wp-config.php  ← recommended (gitignored, secure)
        define( 'EMC_STRIPE_PUB_KEY',    'pk_test_...' );
        define( 'EMC_STRIPE_SECRET_KEY', 'sk_test_...' );
   2. WP Admin > Settings > EMC Stripe  (stored in wp_options)
   The functions emc_stripe_pub_key() and emc_stripe_secret_key()
   in ajax-handlers.php read from constants first, then wp_options.
------------------------------------------------------------------ */

/* ------------------------------------------------------------------
   Register the settings page under Settings > EMC Stripe
------------------------------------------------------------------ */
add_action( 'admin_menu', function () {
    add_options_page(
        'EMC Stripe Settings',
        'EMC Stripe',
        'manage_options',
        'emc-stripe-settings',
        'emc_stripe_settings_page'
    );
} );

add_action( 'admin_init', function () {
    register_setting( 'emc_stripe_settings', 'emc_stripe_pub_key',    array( 'sanitize_callback' => 'sanitize_text_field' ) );
    register_setting( 'emc_stripe_settings', 'emc_stripe_secret_key', array( 'sanitize_callback' => 'sanitize_text_field' ) );
} );

function emc_stripe_settings_page() {
    $pub    = esc_attr( get_option( 'emc_stripe_pub_key',    '' ) );
    $secret = get_option( 'emc_stripe_secret_key', '' );
    $masked = $secret ? substr( $secret, 0, 8 ) . str_repeat( '*', max( 0, strlen( $secret ) - 12 ) ) . substr( $secret, -4 ) : '';
    ?>
    <div class="wrap">
        <h1><span class="dashicons dashicons-shield-alt" style="color:#2aaca0;font-size:28px;margin-right:8px;"></span> EMC Stripe Settings</h1>
        <p style="color:#666;max-width:600px;">
            Enter your Stripe API keys below. These are stored securely in the WordPress database and are never committed to version control.
            For production, you can also define them in <code>wp-config.php</code>:<br>
            <code>define( 'EMC_STRIPE_SECRET_KEY', 'sk_live_...' );</code><br>
            <code>define( 'EMC_STRIPE_PUB_KEY', 'pk_live_...' );</code>
        </p>
        <?php if ( settings_errors( 'emc_stripe_settings' ) ) settings_errors( 'emc_stripe_settings' ); ?>
        <form method="post" action="options.php">
            <?php settings_fields( 'emc_stripe_settings' ); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="emc_stripe_pub_key">Publishable Key</label></th>
                    <td>
                        <input type="text" id="emc_stripe_pub_key" name="emc_stripe_pub_key"
                               value="<?php echo $pub; ?>" class="regular-text" placeholder="pk_test_... or pk_live_...">
                        <p class="description">Safe to share — used in the browser JavaScript.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="emc_stripe_secret_key">Secret Key</label></th>
                    <td>
                        <input type="password" id="emc_stripe_secret_key" name="emc_stripe_secret_key"
                               value="<?php echo esc_attr( $secret ); ?>"
                               class="regular-text" placeholder="sk_test_... or sk_live_...">
                        <?php if ( $masked ) : ?>
                        <p class="description">Current key: <code><?php echo esc_html( $masked ); ?></code></p>
                        <?php endif; ?>
                        <p class="description" style="color:#d63638;">Never share this key publicly.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Save Stripe Keys' ); ?>
        </form>

        <?php if ( get_option('emc_stripe_pub_key') && get_option('emc_stripe_secret_key') ) : ?>
        <div class="notice notice-success" style="max-width:600px;">
            <p>✅ Stripe keys are configured. The donate page payment flow is active.</p>
        </div>
        <?php else : ?>
        <div class="notice notice-warning" style="max-width:600px;">
            <p>⚠️ Stripe keys are not yet set. The donate button will not process payments until both keys are entered.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/* ------------------------------------------------------------------
   Admin notice if keys are missing (on donate-related pages)
------------------------------------------------------------------ */
add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_options' ) ) return;
    if ( get_option( 'emc_stripe_secret_key' ) && get_option( 'emc_stripe_pub_key' ) ) return;
    $url = admin_url( 'options-general.php?page=emc-stripe-settings' );
    echo '<div class="notice notice-warning is-dismissible"><p>'
       . '🔑 <strong>EMC Stripe:</strong> Stripe API keys are not configured. '
       . '<a href="' . esc_url( $url ) . '">Add your keys here</a> to enable online donations.'
       . '</p></div>';
} );
