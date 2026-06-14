<?php
/**
 * EMC Prayer Times Compact — Elementor Widget
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;

class EMC_Widget_Prayer_Times extends \Elementor\Widget_Base {

    public function get_name()  { return 'emc_prayer_times'; }
    public function get_title() { return __( 'Prayer Times', 'emc-theme' ); }
    public function get_icon()  { return 'eicon-clock'; }
    public function get_categories() { return array( 'emc-widgets' ); }
    public function get_keywords()   { return array( 'prayer', 'salah', 'times', 'mosque', 'emc' ); }

    protected function register_controls() {

        $this->start_controls_section( 'section_content', array(
            'label' => __( 'Prayer Times', 'emc-theme' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ) );

        $this->add_control( 'display_mode', array(
            'label'   => __( 'Display Mode', 'emc-theme' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'compact',
            'options' => array(
                'compact' => __( 'Compact (next prayer)', 'emc-theme' ),
                'full'    => __( 'Full strip (all prayers)', 'emc-theme' ),
            ),
        ) );

        $this->add_control( 'heading', array(
            'label'     => __( 'Heading', 'emc-theme' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => __( 'Prayer Times', 'emc-theme' ),
            'condition' => array( 'display_mode' => 'full' ),
        ) );

        $this->add_control( 'show_link', array(
            'label'        => __( 'Link to Prayer Times Page', 'emc-theme' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'emc-theme' ),
            'label_off'    => __( 'No', 'emc-theme' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );

        $this->end_controls_section();
    }

    protected function render() {
        $s    = $this->get_settings_for_display();
        $mode = $s['display_mode'] ?? 'compact';

        if ( 'compact' === $mode ) {
            ?>
            <div class="header-prayer-compact emc-widget-prayer-compact"
                 id="elementor-prayer-compact-<?php echo esc_attr( $this->get_id() ); ?>"
                 aria-live="polite">
                <i class="fas fa-clock" aria-hidden="true"></i>
                <span class="emc-prayer-next-label">
                    <?php esc_html_e( 'Next Prayer', 'emc-theme' ); ?>
                </span>
                <span class="emc-prayer-name" id="el-prayer-name-<?php echo esc_attr( $this->get_id() ); ?>">—</span>
                <span class="emc-prayer-countdown" id="el-prayer-countdown-<?php echo esc_attr( $this->get_id() ); ?>">—</span>
            </div>
            <?php
        } else {
            $prayer_page = get_page_by_path( 'prayer-times' );
            $prayer_url  = $prayer_page ? get_permalink( $prayer_page ) : home_url( '/prayer-times/' );
            $heading     = $s['heading'] ?? __( 'Prayer Times', 'emc-theme' );
            ?>
            <div class="emc-prayer-widget-full">
                <h3 class="emc-prayer-widget-heading">
                    <i class="fas fa-mosque" aria-hidden="true"></i>
                    <?php echo esc_html( $heading ); ?>
                </h3>
                <div class="prayer-times-strip emc-prayer-strip-widget">
                    <?php
                    $prayers = array(
                        'fajr'    => __( 'Fajr',    'emc-theme' ),
                        'dhuhr'   => __( 'Dhuhr',   'emc-theme' ),
                        'asr'     => __( 'Asr',     'emc-theme' ),
                        'maghrib' => __( 'Maghrib', 'emc-theme' ),
                        'isha'    => __( 'Isha\'a',  'emc-theme' ),
                    );
                    foreach ( $prayers as $key => $name ) :
                    ?>
                    <div class="prayer-time-item" id="el-pt-<?php echo esc_attr( $key . '-' . $this->get_id() ); ?>">
                        <span class="prayer-name"><?php echo esc_html( $name ); ?></span>
                        <span class="prayer-time">—</span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if ( ( $s['show_link'] ?? 'yes' ) === 'yes' ) : ?>
                <a href="<?php echo esc_url( $prayer_url ); ?>" class="btn btn-outline btn-sm emc-prayer-link">
                    <?php esc_html_e( 'Full Prayer Times', 'emc-theme' ); ?>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php
        }
    }

    protected function content_template() {
        ?>
        <# if ( settings.display_mode === 'compact' ) { #>
        <div class="header-prayer-compact">
            <i class="fas fa-clock" aria-hidden="true"></i>
            <span><?php echo esc_js( __( 'Next Prayer', 'emc-theme' ) ); ?></span>
            <span>—</span>
        </div>
        <# } else { #>
        <div class="emc-prayer-widget-full">
            <h3 class="emc-prayer-widget-heading">
                <i class="fas fa-mosque"></i> {{ settings.heading }}
            </h3>
            <p style="color:#999;font-size:0.85em"><?php echo esc_js( __( 'Prayer times loaded via JavaScript on the front end.', 'emc-theme' ) ); ?></p>
        </div>
        <# } #>
        <?php
    }
}
