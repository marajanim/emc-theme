<?php
/**
 * Template Part: Meet the Team
 * Driven by the emc_team CPT. Requires team members to be published in admin.
 * @package emc-theme
 */

$heading    = emc_option( 'emc_team_heading',    __( 'Meet Our Team', 'emc-theme' ) );
$subheading = emc_option( 'emc_team_subheading', __( 'Our People', 'emc-theme' ) );
$per_page   = max( 1, (int) emc_option( 'emc_team_per_page', 6 ) );

$team_query = new WP_Query( array(
    'post_type'      => 'emc_team',
    'posts_per_page' => $per_page,
    'post_status'    => 'publish',
    'meta_key'       => '_emc_team_order',
    'orderby'        => 'meta_value_num title',
    'order'          => 'ASC',
) );

if ( ! $team_query->have_posts() ) {
    return; // hide section entirely if no team members published
}
?>
<section class="team-section section-padding" id="team" aria-labelledby="team-heading">
    <div class="container">
        <div class="section-header">
            <span class="subtitle"><?php echo esc_html( $subheading ); ?></span>
            <h2 id="team-heading"><?php echo esc_html( $heading ); ?></h2>
        </div>

        <div class="team-grid">
            <?php
            $delay = 0;
            while ( $team_query->have_posts() ) : $team_query->the_post();
                $role     = get_post_meta( get_the_ID(), '_emc_team_role',     true );
                $facebook = get_post_meta( get_the_ID(), '_emc_team_facebook', true );
                $twitter  = get_post_meta( get_the_ID(), '_emc_team_twitter',  true );
                $linkedin = get_post_meta( get_the_ID(), '_emc_team_linkedin', true );
            ?>
            <div class="team-card scroll-reveal" style="transition-delay:<?php echo esc_attr( $delay ); ?>s">
                <div class="team-photo">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'emc-square', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
                    <?php else : ?>
                        <div class="team-photo-placeholder" aria-hidden="true">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="team-info">
                    <h3 class="team-name"><?php the_title(); ?></h3>
                    <?php if ( $role ) : ?>
                    <p class="team-role"><?php echo esc_html( $role ); ?></p>
                    <?php endif; ?>
                    <div class="team-bio">
                        <?php the_excerpt(); ?>
                    </div>
                    <?php if ( $facebook || $twitter || $linkedin ) : ?>
                    <div class="team-social" aria-label="<?php esc_attr_e( 'Social links', 'emc-theme' ); ?>">
                        <?php if ( $facebook ) : ?>
                        <a href="<?php echo esc_url( $facebook ); ?>" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook" aria-hidden="true"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ( $twitter ) : ?>
                        <a href="<?php echo esc_url( $twitter ); ?>" aria-label="X / Twitter" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-x-twitter" aria-hidden="true"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ( $linkedin ) : ?>
                        <a href="<?php echo esc_url( $linkedin ); ?>" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-linkedin" aria-hidden="true"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $delay = round( $delay + 0.1, 1 );
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
