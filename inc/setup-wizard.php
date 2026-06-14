<?php
/**
 * EMC Theme — inc/setup-wizard.php
 * Phase 11: Admin Setup Wizard UI (Appearance > EMC Setup).
 *
 * Provides a beautiful one-click setup experience:
 *  1. Welcome screen with feature checklist
 *  2. One-click import button (AJAX progress log)
 *  3. Post-import launch links
 *
 * @package emc-theme
 */

defined( 'ABSPATH' ) || exit;


/* ==========================================================================
   Register Admin Menu Page
   ========================================================================== */

/**
 * Register the "EMC Setup" submenu under Appearance.
 */
function emc_register_setup_page() {
    $badge = get_option( 'emc_demo_imported' ) ? '' : ' <span class="update-plugins count-1"><span class="plugin-count">!</span></span>';

    add_theme_page(
        __( 'EMC Setup Wizard', 'emc-theme' ),
        __( 'EMC Setup', 'emc-theme' ) . $badge,
        'manage_options',
        'emc-setup',
        'emc_render_setup_page'
    );
}
add_action( 'admin_menu', 'emc_register_setup_page' );


/* ==========================================================================
   Enqueue Wizard Assets
   ========================================================================== */

/**
 * Enqueue the wizard's inline CSS and localized AJAX data.
 */
function emc_setup_wizard_assets( $hook ) {
    if ( $hook !== 'appearance_page_emc-setup' ) return;

    wp_enqueue_style( 'emc-wizard-style', get_template_directory_uri() . '/assets/css/wizard.css', array(), EMC_VERSION );
    wp_enqueue_script( 'emc-wizard-script', get_template_directory_uri() . '/assets/js/wizard.js', array( 'jquery' ), EMC_VERSION, true );

    wp_localize_script( 'emc-wizard-script', 'emcWizard', array(
        'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
        'nonce'       => wp_create_nonce( 'emc_demo_import_nonce' ),
        'siteUrl'     => home_url( '/' ),
        'customizer'  => admin_url( 'customize.php' ),
        'imported'    => (bool) get_option( 'emc_demo_imported' ),
        'i18n'        => array(
            'running'   => __( 'Importing…', 'emc-theme' ),
            'done'      => __( 'Import Complete!', 'emc-theme' ),
            'error'     => __( 'Something went wrong. Check the log below.', 'emc-theme' ),
            'resetDone' => __( 'Reset. You can run the import again.', 'emc-theme' ),
        ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'emc_setup_wizard_assets' );


/* ==========================================================================
   Render Wizard Page
   ========================================================================== */

/**
 * Render the full setup wizard HTML.
 */
function emc_render_setup_page() {
    $imported    = get_option( 'emc_demo_imported' );
    $import_date = $imported ? wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $imported ) : '';
    $theme       = wp_get_theme();
    ?>
    <div class="emc-wizard-wrap">

        <!-- ── Header ────────────────────────────────────────────────────── -->
        <div class="emc-wizard-header">
            <div class="emc-wizard-logo">
                <div class="emc-wizard-logo-icon">🕌</div>
                <div>
                    <h1><?php esc_html_e( 'EMC Theme Setup', 'emc-theme' ); ?></h1>
                    <p><?php printf( esc_html__( 'Version %s', 'emc-theme' ), esc_html( $theme->get( 'Version' ) ) ); ?></p>
                </div>
            </div>

            <div class="emc-wizard-status">
                <?php if ( $imported ) : ?>
                    <span class="emc-badge emc-badge-green">
                        <span class="emc-badge-dot"></span>
                        <?php esc_html_e( 'Demo imported', 'emc-theme' ); ?>
                    </span>
                <?php else : ?>
                    <span class="emc-badge emc-badge-amber">
                        <span class="emc-badge-dot"></span>
                        <?php esc_html_e( 'Setup required', 'emc-theme' ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="emc-wizard-body">

            <!-- ── Left: Steps ───────────────────────────────────────────── -->
            <div class="emc-wizard-main">

                <?php if ( ! $imported ) : ?>
                <!-- Welcome Card -->
                <div class="emc-card">
                    <div class="emc-card-icon">🚀</div>
                    <h2><?php esc_html_e( 'One-Click Demo Import', 'emc-theme' ); ?></h2>
                    <p><?php esc_html_e( 'Click the button below to instantly set up your site with all pages, navigation menus, and recommended settings — exactly as shown in the preview.', 'emc-theme' ); ?></p>

                    <div class="emc-import-checklist">
                        <?php
                        $steps = array(
                            array( '📄', __( '14 pre-built pages', 'emc-theme' ) ),
                            array( '🗂️', __( 'Primary, Footer & Mobile menus', 'emc-theme' ) ),
                            array( '🏠', __( 'Homepage & Blog page assignments', 'emc-theme' ) ),
                            array( '🎨', __( '40+ theme settings (colours, fonts, layout)', 'emc-theme' ) ),
                            array( '⚡', __( 'Permalink structure flushed', 'emc-theme' ) ),
                        );
                        foreach ( $steps as $step ) :
                        ?>
                        <div class="emc-checklist-item">
                            <span class="emc-checklist-icon"><?php echo esc_html( $step[0] ); ?></span>
                            <span><?php echo esc_html( $step[1] ); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="emc-import-actions">
                        <button id="emc-run-import" class="emc-btn emc-btn-primary" type="button">
                            <span class="emc-btn-icon">⚡</span>
                            <?php esc_html_e( 'Run Demo Import', 'emc-theme' ); ?>
                        </button>
                        <p class="emc-import-note">
                            <strong><?php esc_html_e( 'Safe to run on a fresh WordPress install.', 'emc-theme' ); ?></strong>
                            <?php esc_html_e( 'Existing content and settings are preserved.', 'emc-theme' ); ?>
                        </p>
                    </div>
                </div>
                <?php else : ?>

                <!-- Success Card -->
                <div class="emc-card emc-card-success">
                    <div class="emc-card-icon">✅</div>
                    <h2><?php esc_html_e( 'Your site is ready!', 'emc-theme' ); ?></h2>
                    <p>
                        <?php printf(
                            esc_html__( 'Demo content was imported on %s. Your Essex Muslim Centre website is fully configured and ready to launch.', 'emc-theme' ),
                            '<strong>' . esc_html( $import_date ) . '</strong>'
                        ); ?>
                    </p>

                    <div class="emc-launch-actions">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="emc-btn emc-btn-primary">
                            <span class="emc-btn-icon">🌐</span>
                            <?php esc_html_e( 'View Your Site', 'emc-theme' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="emc-btn emc-btn-secondary">
                            <span class="emc-btn-icon">🎨</span>
                            <?php esc_html_e( 'Customise Theme', 'emc-theme' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" class="emc-btn emc-btn-ghost">
                            <span class="emc-btn-icon">📄</span>
                            <?php esc_html_e( 'Edit Pages', 'emc-theme' ); ?>
                        </a>
                    </div>

                    <div class="emc-reset-wrap">
                        <p><?php esc_html_e( 'Need to re-run the import?', 'emc-theme' ); ?>
                            <button id="emc-reset-import" class="emc-link-btn" type="button">
                                <?php esc_html_e( 'Reset import flag', 'emc-theme' ); ?>
                            </button>
                        </p>
                    </div>
                </div>

                <?php endif; ?>

                <!-- Progress / Log Section (hidden until import runs) -->
                <div id="emc-import-progress" class="emc-card emc-card-log" style="display:none;">
                    <h3>
                        <span class="emc-spinner" aria-hidden="true"></span>
                        <span id="emc-progress-title"><?php esc_html_e( 'Running import…', 'emc-theme' ); ?></span>
                    </h3>
                    <div id="emc-log-wrap">
                        <ul id="emc-log-list" class="emc-log-list"></ul>
                    </div>
                    <div id="emc-import-done" style="display:none;">
                        <div class="emc-launch-actions" style="margin-top:1.5rem;">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="emc-btn emc-btn-primary">
                                <?php esc_html_e( 'View Your Site', 'emc-theme' ); ?>
                            </a>
                            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="emc-btn emc-btn-secondary">
                                <?php esc_html_e( 'Customise Theme', 'emc-theme' ); ?>
                            </a>
                        </div>
                    </div>
                </div>

            </div><!-- /.emc-wizard-main -->

            <!-- ── Right: Sidebar Info ───────────────────────────────────── -->
            <div class="emc-wizard-sidebar">

                <div class="emc-sidebar-card">
                    <h3><?php esc_html_e( '🔗 Quick Links', 'emc-theme' ); ?></h3>
                    <ul class="emc-sidebar-links">
                        <li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">🎨 <?php esc_html_e( 'Theme Customiser', 'emc-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>">📄 <?php esc_html_e( 'All Pages', 'emc-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">🗂️ <?php esc_html_e( 'Navigation Menus', 'emc-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>">🧩 <?php esc_html_e( 'Widget Areas', 'emc-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=emc_event' ) ); ?>">📅 <?php esc_html_e( 'Events', 'emc-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=emc_service' ) ); ?>">🕌 <?php esc_html_e( 'Services', 'emc-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=emc_vacancy' ) ); ?>">💼 <?php esc_html_e( 'Vacancies', 'emc-theme' ); ?></a></li>
                    </ul>
                </div>

                <div class="emc-sidebar-card">
                    <h3><?php esc_html_e( '📋 Theme Info', 'emc-theme' ); ?></h3>
                    <table class="emc-info-table">
                        <tr>
                            <td><?php esc_html_e( 'Theme', 'emc-theme' ); ?></td>
                            <td><strong><?php echo esc_html( $theme->get( 'Name' ) ); ?></strong></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Version', 'emc-theme' ); ?></td>
                            <td><?php echo esc_html( $theme->get( 'Version' ) ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Author', 'emc-theme' ); ?></td>
                            <td><?php echo esc_html( $theme->get( 'Author' ) ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'PHP', 'emc-theme' ); ?></td>
                            <td><?php echo esc_html( PHP_VERSION ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'WordPress', 'emc-theme' ); ?></td>
                            <td><?php echo esc_html( get_bloginfo( 'version' ) ); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="emc-sidebar-card emc-sidebar-card-green">
                    <h3><?php esc_html_e( '✅ Requirements', 'emc-theme' ); ?></h3>
                    <?php emc_wizard_system_checks(); ?>
                </div>

            </div><!-- /.emc-wizard-sidebar -->

        </div><!-- /.emc-wizard-body -->

    </div><!-- /.emc-wizard-wrap -->
    <?php
}


/* ==========================================================================
   System Requirements Check
   ========================================================================== */

/**
 * Output a list of system checks with pass/fail indicators.
 */
function emc_wizard_system_checks() {
    $checks = array(
        array(
            'label'  => 'PHP ≥ 7.4',
            'pass'   => version_compare( PHP_VERSION, '7.4', '>=' ),
            'detail' => 'PHP ' . PHP_VERSION,
        ),
        array(
            'label'  => 'WordPress ≥ 6.0',
            'pass'   => version_compare( get_bloginfo( 'version' ), '6.0', '>=' ),
            'detail' => 'WP ' . get_bloginfo( 'version' ),
        ),
        array(
            'label'  => 'wp_mail() enabled',
            'pass'   => function_exists( 'wp_mail' ),
            'detail' => function_exists( 'wp_mail' ) ? 'Available' : 'Missing',
        ),
        array(
            'label'  => 'Permalink structure set',
            'pass'   => get_option( 'permalink_structure' ) !== '',
            'detail' => get_option( 'permalink_structure' ) ?: 'Plain (must change)',
        ),
        array(
            'label'  => 'GD / ImageMagick',
            'pass'   => extension_loaded( 'gd' ) || extension_loaded( 'imagick' ),
            'detail' => extension_loaded( 'imagick' ) ? 'ImageMagick' : ( extension_loaded( 'gd' ) ? 'GD' : '❌ Missing' ),
        ),
    );
    ?>
    <ul class="emc-checks-list">
        <?php foreach ( $checks as $check ) : ?>
        <li class="emc-check-item <?php echo $check['pass'] ? 'pass' : 'fail'; ?>">
            <span class="emc-check-dot"></span>
            <span class="emc-check-label"><?php echo esc_html( $check['label'] ); ?></span>
            <span class="emc-check-detail"><?php echo esc_html( $check['detail'] ); ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php
}


/* ==========================================================================
   After-Activation Redirect
   ========================================================================== */

/**
 * After theme activation, redirect to the setup wizard.
 */
function emc_after_activation_redirect() {
    if ( get_option( 'emc_demo_imported' ) ) return;

    // Set flag to redirect once
    if ( ! get_option( 'emc_activation_redirect' ) ) return;
    delete_option( 'emc_activation_redirect' );

    if ( ! is_multisite() ) {
        wp_safe_redirect( admin_url( 'themes.php?page=emc-setup' ) );
        exit;
    }
}
add_action( 'admin_init', 'emc_after_activation_redirect' );

/**
 * Set redirect flag on activation.
 */
function emc_set_activation_redirect() {
    update_option( 'emc_activation_redirect', true );
}
register_activation_hook( get_template_directory() . '/functions.php', 'emc_set_activation_redirect' );
