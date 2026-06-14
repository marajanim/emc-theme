<?php
/**
 * Template Part: Stats / Counters Strip
 * All four counters driven by Customizer (emc_hp_counters section).
 * @package emc-theme
 */

$heading = emc_option( 'emc_counters_heading', '' );

$counters = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $defaults = array(
        1 => array( 'icon' => 'fas fa-mosque',         'num' => '2018',   'label' => __( 'Founded',            'emc-theme' ) ),
        2 => array( 'icon' => 'fas fa-users',          'num' => '500+',   'label' => __( 'Families Served',    'emc-theme' ) ),
        3 => array( 'icon' => 'fas fa-praying-hands',  'num' => '10+',    'label' => __( 'Weekly Services',    'emc-theme' ) ),
        4 => array( 'icon' => 'fas fa-envelope',       'num' => '1,200+', 'label' => __( 'Newsletter Readers', 'emc-theme' ) ),
    );
    $counters[] = array(
        'icon'  => emc_option( "emc_counter_{$i}_icon",  $defaults[ $i ]['icon'] ),
        'num'   => emc_option( "emc_counter_{$i}_num",   $defaults[ $i ]['num'] ),
        'label' => emc_option( "emc_counter_{$i}_label", $defaults[ $i ]['label'] ),
    );
}
?>
<section class="counters-strip section-padding" aria-labelledby="<?php echo $heading ? 'counters-heading' : false; ?>">
    <div class="container">
        <?php if ( $heading ) : ?>
        <div class="section-header">
            <h2 id="counters-heading"><?php echo esc_html( $heading ); ?></h2>
        </div>
        <?php endif; ?>

        <div class="counters-grid">
            <?php foreach ( $counters as $i => $counter ) : ?>
            <div class="counter-item scroll-reveal" style="transition-delay:<?php echo esc_attr( $i * 0.1 ); ?>s">
                <div class="counter-icon" aria-hidden="true">
                    <i class="<?php echo esc_attr( $counter['icon'] ); ?>"></i>
                </div>
                <div
                    class="counter-num"
                    data-target="<?php echo esc_attr( preg_replace( '/[^\d]/', '', $counter['num'] ) ); ?>"
                    data-suffix="<?php echo esc_attr( preg_replace( '/[\d,]/', '', $counter['num'] ) ); ?>"
                >
                    <?php echo esc_html( $counter['num'] ); ?>
                </div>
                <div class="counter-label"><?php echo esc_html( $counter['label'] ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
