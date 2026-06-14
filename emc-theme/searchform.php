<?php
/**
 * EMC Theme — searchform.php
 * Custom search form template used by get_search_form().
 * @package emc-theme
 */

$unique_id = uniqid( 'search-form-' );
?>
<form role="search" method="get" class="emc-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>">
        <?php esc_html_e( 'Search for:', 'emc-theme' ); ?>
    </label>
    <div class="emc-search-form-inner">
        <input
            type="search"
            id="<?php echo esc_attr( $unique_id ); ?>"
            class="emc-search-input"
            name="s"
            value="<?php echo esc_attr( get_search_query() ); ?>"
            placeholder="<?php esc_attr_e( 'Search&hellip;', 'emc-theme' ); ?>"
            autocomplete="off"
        >
        <button type="submit" class="emc-search-submit" aria-label="<?php esc_attr_e( 'Submit search', 'emc-theme' ); ?>">
            <i class="fas fa-search" aria-hidden="true"></i>
        </button>
    </div>
</form>
