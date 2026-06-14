<?php
/**
 * Template Part: No Posts Found
 * @package emc-theme
 */
?>
<div class="no-results">
    <div class="no-results-inner">
        <i class="fas fa-search" aria-hidden="true"></i>
        <h2><?php esc_html_e( 'Nothing Found', 'emc-theme' ); ?></h2>
        <?php if ( is_search() ) : ?>
            <p>
                <?php
                printf(
                    /* translators: %s: search query */
                    esc_html__( 'No results for "%s". Try a different search term.', 'emc-theme' ),
                    esc_html( get_search_query() )
                );
                ?>
            </p>
        <?php else : ?>
            <p><?php esc_html_e( 'It looks like nothing was found here. Try using the search below.', 'emc-theme' ); ?></p>
        <?php endif; ?>
        <?php get_search_form(); ?>
    </div>
</div>
