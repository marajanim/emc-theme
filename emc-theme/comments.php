<?php
/**
 * EMC Theme — comments.php
 * Custom comments and comment form template.
 * @package emc-theme
 */

if ( post_password_required() ) {
    return;
}

/* ── Comment callback — must be defined before wp_list_comments() ──────── */
if ( ! function_exists( 'emc_comment_template' ) ) :
function emc_comment_template( $comment, $args, $depth ) {
    $add_below = 'div-comment';
    ?>
    <li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent', $comment ); ?> id="comment-<?php comment_ID(); ?>">
    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

        <footer class="comment-meta">
            <div class="comment-author vcard">
                <?php if ( 0 !== (int) $args['avatar_size'] ) {
                    echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'comment-avatar' ) );
                } ?>
                <?php printf( '<cite class="fn">%s</cite>', get_comment_author_link( $comment ) ); ?>
            </div>
            <div class="comment-metadata">
                <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                    <time datetime="<?php comment_time( 'c' ); ?>">
                        <?php
                        printf(
                            /* translators: 1: date, 2: time */
                            esc_html__( '%1$s at %2$s', 'emc-theme' ),
                            get_comment_date( '', $comment ),
                            get_comment_time()
                        );
                        ?>
                    </time>
                </a>
                <?php edit_comment_link( __( 'Edit', 'emc-theme' ), ' <span class="edit-link">', '</span>' ); ?>
            </div>
            <?php if ( '0' === $comment->comment_approved ) : ?>
            <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'emc-theme' ); ?></p>
            <?php endif; ?>
        </footer>

        <div class="comment-content">
            <?php comment_text(); ?>
        </div>

        <?php
        comment_reply_link( array_merge( $args, array(
            'add_below' => $add_below,
            'depth'     => $depth,
            'max_depth' => $args['max_depth'],
            'before'    => '<div class="reply">',
            'after'     => '</div>',
        ) ) );
        ?>

    </article>
    <?php
}
endif;
?>

<section id="comments" class="comments-section">

    <?php if ( have_comments() ) : ?>

    <h2 class="comments-title">
        <?php
        $count = get_comments_number();
        if ( '1' === (string) $count ) {
            printf(
                /* translators: %s: post title */
                esc_html__( 'One comment on &ldquo;%s&rdquo;', 'emc-theme' ),
                '<span>' . wp_kses_post( get_the_title() ) . '</span>'
            );
        } else {
            printf(
                /* translators: 1: number of comments, 2: post title */
                esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $count, 'comments title', 'emc-theme' ) ),
                number_format_i18n( $count ),
                '<span>' . wp_kses_post( get_the_title() ) . '</span>'
            );
        }
        ?>
    </h2>

    <ol class="comment-list">
        <?php
        wp_list_comments( array(
            'style'      => 'ol',
            'short_ping' => true,
            'callback'   => 'emc_comment_template',
        ) );
        ?>
    </ol>

    <?php the_comments_navigation( array(
        'prev_text' => '<i class="fas fa-arrow-left" aria-hidden="true"></i> ' . __( 'Older Comments', 'emc-theme' ),
        'next_text' => __( 'Newer Comments', 'emc-theme' ) . ' <i class="fas fa-arrow-right" aria-hidden="true"></i>',
    ) ); ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'emc-theme' ); ?></p>
    <?php endif; ?>

    <?php
    $req       = get_option( 'require_name_email' );
    $commenter = wp_get_current_commenter();
    $req_span  = $req ? ' <span class="required" aria-hidden="true">*</span>' : '';

    comment_form( array(
        'title_reply'          => __( 'Leave a Comment', 'emc-theme' ),
        'title_reply_to'       => __( 'Reply to %s', 'emc-theme' ),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'cancel_reply_before'  => ' <small>',
        'cancel_reply_after'   => '</small>',
        'cancel_reply_link'    => __( 'Cancel reply', 'emc-theme' ),
        'label_submit'         => __( 'Post Comment', 'emc-theme' ),
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="btn btn-primary">%4$s</button>',
        'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
        'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.', 'emc-theme' )
            . ( $req ? ' ' . sprintf( __( 'Required fields are marked %s.', 'emc-theme' ), '<span class="required">*</span>' ) : '' )
            . '</p>',
        'fields' => array(
            'author'  => '<div class="comment-form-row"><p class="comment-form-author">'
                . '<label for="author">' . __( 'Name', 'emc-theme' ) . $req_span . '</label>'
                . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . ( $req ? ' required' : '' ) . '></p>',
            'email'   => '<p class="comment-form-email">'
                . '<label for="email">' . __( 'Email', 'emc-theme' ) . $req_span . '</label>'
                . '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100"' . ( $req ? ' required' : '' ) . '></p>',
            'url'     => '<p class="comment-form-url">'
                . '<label for="url">' . __( 'Website', 'emc-theme' ) . '</label>'
                . '<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200">'
                . '</p></div>',
            'cookies' => '<p class="comment-form-cookies-consent">'
                . '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">'
                . ' <label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'emc-theme' ) . '</label>'
                . '</p>',
        ),
        'comment_field' => '<p class="comment-form-comment">'
            . '<label for="comment">' . __( 'Comment', 'emc-theme' ) . ' <span class="required" aria-hidden="true">*</span></label>'
            . '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required></textarea>'
            . '</p>',
    ) );
    ?>

</section>
