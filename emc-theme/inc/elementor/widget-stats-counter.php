<?php
/**
 * EMC Stats Counter — Elementor Widget
 * Animated stat counters with icon, number, and label.
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;

class EMC_Widget_Stats_Counter extends \Elementor\Widget_Base {

    public function get_name()  { return 'emc_stats_counter'; }
    public function get_title() { return __( 'Stats Counter', 'emc-theme' ); }
    public function get_icon()  { return 'eicon-counter'; }
    public function get_categories() { return array( 'emc-widgets' ); }
    public function get_keywords()   { return array( 'stats', 'counter', 'number', 'animate', 'emc' ); }

    protected function register_controls() {

        $this->start_controls_section( 'section_content', array(
            'label' => __( 'Counter', 'emc-theme' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ) );

        $this->add_control( 'icon', array(
            'label'   => __( 'Font Awesome Icon Class', 'emc-theme' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'fas fa-users',
        ) );

        $this->add_control( 'number', array(
            'label'   => __( 'Number / Value', 'emc-theme' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '500',
        ) );

        $this->add_control( 'suffix', array(
            'label'   => __( 'Suffix', 'emc-theme' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '+',
        ) );

        $this->add_control( 'label', array(
            'label'   => __( 'Label', 'emc-theme' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Community Members', 'emc-theme' ),
        ) );

        $this->add_control( 'animate', array(
            'label'        => __( 'Animate on Scroll', 'emc-theme' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'emc-theme' ),
            'label_off'    => __( 'No', 'emc-theme' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );

        $this->end_controls_section();

        /* ── Style ─────────────────────────────────────────────────────── */
        $this->start_controls_section( 'section_style', array(
            'label' => __( 'Style', 'emc-theme' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ) );

        $this->add_control( 'icon_color', array(
            'label'     => __( 'Icon Color', 'emc-theme' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#1a6b3a',
            'selectors' => array( '{{WRAPPER}} .emc-counter-icon' => 'color: {{VALUE}};' ),
        ) );

        $this->add_control( 'number_color', array(
            'label'     => __( 'Number Color', 'emc-theme' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0D1B2A',
            'selectors' => array( '{{WRAPPER}} .emc-counter-number' => 'color: {{VALUE}};' ),
        ) );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'number_typography',
                'label'    => __( 'Number Typography', 'emc-theme' ),
                'selector' => '{{WRAPPER}} .emc-counter-number',
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s       = $this->get_settings_for_display();
        $icon    = $s['icon']    ?? 'fas fa-users';
        $number  = $s['number']  ?? '0';
        $suffix  = $s['suffix']  ?? '';
        $label   = $s['label']   ?? '';
        $animate = ( $s['animate'] ?? 'yes' ) === 'yes';
        $num_int = preg_replace( '/[^0-9]/', '', $number );
        ?>
        <div class="emc-counter-widget<?php echo $animate ? ' scroll-reveal' : ''; ?>"
             <?php echo $animate ? 'data-target="' . esc_attr( $num_int ) . '"' : ''; ?>>
            <div class="emc-counter-icon" aria-hidden="true">
                <i class="<?php echo esc_attr( $icon ); ?>"></i>
            </div>
            <div class="emc-counter-number" <?php echo $animate ? 'aria-live="polite"' : ''; ?>>
                <span class="counter-num"><?php echo esc_html( $animate ? '0' : $number ); ?></span>
                <span class="counter-suffix"><?php echo esc_html( $suffix ); ?></span>
            </div>
            <div class="emc-counter-label"><?php echo esc_html( $label ); ?></div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <div class="emc-counter-widget">
            <div class="emc-counter-icon" aria-hidden="true">
                <i class="{{ settings.icon }}"></i>
            </div>
            <div class="emc-counter-number">
                <span class="counter-num">{{ settings.animate === 'yes' ? '0' : settings.number }}</span>
                <span class="counter-suffix">{{ settings.suffix }}</span>
            </div>
            <div class="emc-counter-label">{{ settings.label }}</div>
        </div>
        <?php
    }
}
