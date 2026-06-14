<?php
/**
 * EMC Theme — inc/acf-helpers.php
 * ACF wrapper helpers. All templates use these instead of calling get_field() directly,
 * so the theme keeps working even if ACF is deactivated.
 *
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get an ACF field value with a fallback default.
 *
 * @param  string     $field    ACF field name.
 * @param  mixed      $default  Fallback if ACF is missing or field is empty.
 * @param  int|null   $post_id  Optional post ID (defaults to current post).
 * @return mixed
 */
function emc_acf( $field, $default = '', $post_id = null ) {
    // 1. Check Customizer (theme_mod) first — same key name as ACF field
    $customizer_val = get_theme_mod( $field, null );
    if ( $customizer_val !== null && $customizer_val !== '' ) {
        return $customizer_val;
    }
    // 2. Fall back to ACF if plugin is active
    if ( ! function_exists( 'get_field' ) ) {
        return $default;
    }
    $val = get_field( $field, $post_id );
    if ( $val === null || $val === '' || $val === false ) {
        return $default;
    }
    return $val;
}

/**
 * Get an ACF image field and return the URL.
 * Supports both 'array' and 'url' return formats.
 *
 * @param  string      $field    ACF field name.
 * @param  string      $default  Fallback URL.
 * @param  string      $size     Image size when return format is 'array'.
 * @param  int|null    $post_id  Optional post ID.
 * @return string      Image URL.
 */
function emc_acf_image( $field, $default = '', $size = 'full', $post_id = null ) {
    if ( ! function_exists( 'get_field' ) ) {
        return $default;
    }
    $img = get_field( $field, $post_id );
    if ( empty( $img ) ) {
        return $default;
    }
    // Array return format
    if ( is_array( $img ) ) {
        if ( $size !== 'full' && ! empty( $img['sizes'][ $size ] ) ) {
            return $img['sizes'][ $size ];
        }
        return ! empty( $img['url'] ) ? $img['url'] : $default;
    }
    // URL return format (string)
    if ( is_string( $img ) ) {
        return $img;
    }
    // ID return format (integer)
    if ( is_numeric( $img ) ) {
        $url = wp_get_attachment_image_url( (int) $img, $size );
        return $url ? $url : $default;
    }
    return $default;
}

/**
 * Get an ACF group field (sub-fields) with defaults.
 * ACF Free doesn't have repeaters, so we use numbered groups.
 *
 * @param  string  $prefix   Field name prefix, e.g. 'about_trustee_1'.
 * @param  array   $keys     Sub-field suffixes, e.g. ['name', 'role', 'bio'].
 * @param  array   $defaults Matching default values.
 * @param  int     $post_id  Optional post ID.
 * @return array   Associative array of values.
 */
function emc_acf_group( $prefix, $keys, $defaults = array(), $post_id = null ) {
    $result = array();
    foreach ( $keys as $i => $key ) {
        $field_name = $prefix . '_' . $key;
        $def        = isset( $defaults[ $i ] ) ? $defaults[ $i ] : '';
        $result[ $key ] = emc_acf( $field_name, $def, $post_id );
    }
    return $result;
}
