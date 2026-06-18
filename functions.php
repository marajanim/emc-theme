<?php
/**
 * EMC Theme — functions.php
 *
 * Central hub for theme setup, asset enqueueing, menu registration,
 * widget areas, and custom post type includes.
 *
 * @package emc-theme
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/* ==========================================================================
   Constants
   ========================================================================== */
define( 'EMC_VERSION',   '1.0.0' );
define( 'EMC_DIR',       get_template_directory() );
define( 'EMC_URI',       get_template_directory_uri() );
define( 'EMC_ASSETS',    EMC_URI . '/assets' );

/* ==========================================================================
   1. Theme Setup
   ========================================================================== */
if ( ! function_exists( 'emc_theme_setup' ) ) :
    function emc_theme_setup() {

        // Make theme available for translation
        load_theme_textdomain( 'emc-theme', EMC_DIR . '/languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 1200, 630, true );

        // Custom image sizes
        add_image_size( 'emc-hero',      1920, 900,  true );
        add_image_size( 'emc-card',       600, 400,  true );
        add_image_size( 'emc-thumbnail',  400, 300,  true );
        add_image_size( 'emc-square',     400, 400,  true );

        // Register navigation menus
        register_nav_menus( array(
            'primary'  => __( 'Primary Navigation', 'emc-theme' ),
            'footer'   => __( 'Footer Quick Links',  'emc-theme' ),
            'mobile'   => __( 'Mobile Navigation',   'emc-theme' ),
        ) );

        // Switch default core markup to output valid HTML5
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );

        // Custom logo
        add_theme_support( 'custom-logo', array(
            'height'      => 60,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        ) );

        // Custom background
        add_theme_support( 'custom-background', array(
            'default-color' => 'F8FAFC',
        ) );

        // Add support for core custom header
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Align wide / full-width blocks (Gutenberg)
        add_theme_support( 'align-wide' );

        // Responsive embeds
        add_theme_support( 'responsive-embeds' );
    }
endif;
add_action( 'after_setup_theme', 'emc_theme_setup' );


/* ==========================================================================
   2. Content Width
   ========================================================================== */
if ( ! isset( $content_width ) ) {
    $content_width = 1280;
}


/* ==========================================================================
   2b. Auto-assign page templates by slug
   ========================================================================== */
/**
 * Automatically assign specific page templates based on the page slug,
 * so the admin doesn't need to manually set them in the editor.
 */
add_filter( 'template_include', function ( $template ) {
    if ( is_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );

        $slug_templates = array(
            'campaign' => 'template-campaign.php',
        );

        if ( isset( $slug_templates[ $slug ] ) ) {
            $custom = locate_template( $slug_templates[ $slug ] );
            if ( $custom ) {
                return $custom;
            }
        }
    }
    return $template;
} );


/* ==========================================================================
   3. Enqueue Styles & Scripts
   ========================================================================== */
if ( ! function_exists( 'emc_enqueue_assets' ) ) :
    function emc_enqueue_assets() {

        // ── Google Fonts (dynamically built from Customizer font selections) ─
        wp_enqueue_style(
            'emc-google-fonts',
            emc_get_google_fonts_url(),
            array(),
            null
        );

        // ── Font Awesome (self-hosted — reliable on local + restricted networks) ─
        $fa_css_path = EMC_DIR . '/assets/fonts/fontawesome/css/all.min.css';
        wp_enqueue_style(
            'font-awesome',
            EMC_URI . '/assets/fonts/fontawesome/css/all.min.css',
            array(),
            file_exists( $fa_css_path ) ? filemtime( $fa_css_path ) : '6.4.0'
        );


        // ── Core Theme CSS ────────────────────────────────────────────────
        $style_path = EMC_DIR . '/assets/css/style.css';
        wp_enqueue_style(
            'emc-style',
            EMC_ASSETS . '/css/style.css',
            array( 'emc-google-fonts', 'font-awesome' ),
            file_exists( $style_path ) ? filemtime( $style_path ) : EMC_VERSION
        );

        // ── Page-Specific CSS ─────────────────────────────────────────────
        // Loaded conditionally by page template (handled in template files)
        // e.g. wp_enqueue_style('emc-donate', EMC_ASSETS.'/css/donate.css', ...)

        // ── Blog CSS (archive + single posts) ────────────────────────────
        if ( is_home() || is_single() || is_category() || is_tag() || is_author() || is_search() || is_archive() ) {
            $blog_css_path = EMC_DIR . '/assets/css/blog.css';
            if ( file_exists( $blog_css_path ) ) {
                wp_enqueue_style(
                    'emc-blog',
                    EMC_ASSETS . '/css/blog.css',
                    array( 'emc-style' ),
                    filemtime( $blog_css_path )
                );
            }
        }

        // ── Core JS ───────────────────────────────────────────────────────
        wp_enqueue_script(
            'emc-script',
            EMC_ASSETS . '/js/script.js',
            array(),
            EMC_VERSION,
            true // load in footer
        );

        // ── Modal System ──────────────────────────────────────────────────
        wp_enqueue_script(
            'emc-modal',
            EMC_ASSETS . '/js/modal.js',
            array( 'emc-script' ),
            EMC_VERSION,
            true
        );

        // ── Slider / Carousel ─────────────────────────────────────────────
        wp_enqueue_script(
            'emc-slider',
            EMC_ASSETS . '/js/slider.js',
            array( 'emc-script' ),
            EMC_VERSION,
            true
        );

        // ── Prayer Times Top Bar (sitewide) ──────────────────────────────
        $topbar_js_path = EMC_DIR . '/assets/js/prayer-topbar.js';
        wp_enqueue_script(
            'emc-prayer-topbar',
            EMC_ASSETS . '/js/prayer-topbar.js',
            array(),
            file_exists( $topbar_js_path ) ? filemtime( $topbar_js_path ) : EMC_VERSION,
            true
        );
        // Pass the JSON data URL so the top bar JS can fetch it
        wp_localize_script( 'emc-prayer-topbar', 'emcPrayer', array(
            'dataUrl' => EMC_ASSETS . '/js/prayer-data.json',
        ) );

        // ── Localize script data ──────────────────────────────────────────

        wp_localize_script( 'emc-script', 'emcData', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'emc_nonce' ),
            'themeUri' => EMC_URI,
        ) );

        // ── Comments ──────────────────────────────────────────────────────
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'emc_enqueue_assets' );


/* ==========================================================================
   3b. Page-Specific Asset Enqueueing
   ========================================================================== */
if ( ! function_exists( 'emc_enqueue_page_assets' ) ) :
    function emc_enqueue_page_assets() {
        if ( ! is_page() ) {
            return;
        }

        $slug = get_post_field( 'post_name', get_queried_object_id() );

        $map = array(
            'donate'        => array( 'css' => 'donate.css',       'js' => 'donate.js' ),
            'events'        => array( 'css' => 'events.css',       'js' => 'events.js' ),
            'contact'       => array( 'css' => 'contact.css',      'js' => 'contact.js' ),
            'prayer-times'  => array( 'css' => 'prayer-times.css', 'js' => 'prayer-times.js' ),
            'media'         => array( 'css' => 'media.css',        'js' => 'media.js' ),
            'services'      => array( 'css' => 'services.css',     'js' => 'services.js' ),
            'about'         => array( 'css' => 'about.css',        'js' => 'about.js' ),
            'ramadan'       => array( 'css' => 'ramadan.css',      'js' => 'ramadan.js' ),
            'campaign'      => array( 'css' => 'campaign.css',     'js' => 'campaign.js' ),
        );

        if ( ! isset( $map[ $slug ] ) ) {
            return;
        }

        $assets   = $map[ $slug ];
        $css_path = EMC_DIR . '/assets/css/' . $assets['css'];
        $js_path  = EMC_DIR . '/assets/js/'  . $assets['js'];

        if ( file_exists( $css_path ) ) {
            wp_enqueue_style(
                'emc-page-' . $slug,
                EMC_ASSETS . '/css/' . $assets['css'],
                array( 'emc-style' ),
                filemtime( $css_path )
            );
        }

        if ( file_exists( $js_path ) ) {
            wp_enqueue_script(
                'emc-page-' . $slug,
                EMC_ASSETS . '/js/' . $assets['js'],
                array( 'emc-script' ),
                filemtime( $js_path ),
                true
            );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'emc_enqueue_page_assets' );

/* Enqueue CPT-specific assets for single/archive/taxonomy views */
if ( ! function_exists( 'emc_enqueue_cpt_assets' ) ) :
    function emc_enqueue_cpt_assets() {
        $cpt_map = array(
            'emc_event'   => array( 'css' => 'events.css',   'js' => 'events.js' ),
            'emc_service' => array( 'css' => 'services.css', 'js' => 'services.js' ),
        );

        // Map taxonomies to their parent CPT
        $tax_map = array(
            'event_category' => 'emc_event',
        );

        $post_type = '';
        if ( is_singular() ) {
            $post_type = get_post_type();
        } elseif ( is_post_type_archive() ) {
            $post_type = get_query_var( 'post_type' );
        } elseif ( is_tax() ) {
            $queried = get_queried_object();
            if ( $queried && isset( $tax_map[ $queried->taxonomy ] ) ) {
                $post_type = $tax_map[ $queried->taxonomy ];
            }
        }

        if ( ! $post_type || ! isset( $cpt_map[ $post_type ] ) ) {
            return;
        }

        $assets   = $cpt_map[ $post_type ];
        $css_path = EMC_DIR . '/assets/css/' . $assets['css'];

        if ( file_exists( $css_path ) ) {
            wp_enqueue_style(
                'emc-cpt-' . $post_type,
                EMC_ASSETS . '/css/' . $assets['css'],
                array( 'emc-style' ),
                filemtime( $css_path )
            );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'emc_enqueue_cpt_assets' );


/* ==========================================================================
   4. Widget Areas (Sidebars)
   ========================================================================== */
if ( ! function_exists( 'emc_register_sidebars' ) ) :
    function emc_register_sidebars() {
        $defaults = array(
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        );

        register_sidebar( array_merge( $defaults, array(
            'name'        => __( 'Blog Sidebar', 'emc-theme' ),
            'id'          => 'sidebar-blog',
            'description' => __( 'Widgets displayed alongside blog posts and archives.', 'emc-theme' ),
        ) ) );

        register_sidebar( array_merge( $defaults, array(
            'name'        => __( 'Footer Column 1', 'emc-theme' ),
            'id'          => 'footer-1',
            'description' => __( 'First footer column — branding & about text.', 'emc-theme' ),
        ) ) );

        register_sidebar( array_merge( $defaults, array(
            'name'        => __( 'Footer Column 2', 'emc-theme' ),
            'id'          => 'footer-2',
            'description' => __( 'Second footer column — quick links.', 'emc-theme' ),
        ) ) );

        register_sidebar( array_merge( $defaults, array(
            'name'        => __( 'Footer Column 3', 'emc-theme' ),
            'id'          => 'footer-3',
            'description' => __( 'Third footer column — community links.', 'emc-theme' ),
        ) ) );

        register_sidebar( array_merge( $defaults, array(
            'name'        => __( 'Footer Column 4', 'emc-theme' ),
            'id'          => 'footer-4',
            'description' => __( 'Fourth footer column — contact info.', 'emc-theme' ),
        ) ) );
    }
endif;
add_action( 'widgets_init', 'emc_register_sidebars' );


/* ==========================================================================
   5. Include Modular Files
   ========================================================================== */
$emc_includes = array(
    '/inc/custom-post-types.php',   // CPTs: Services, Events, Vacancies, etc.
    '/inc/meta-boxes.php',          // Native meta boxes for CPT custom fields
    '/inc/admin-columns.php',       // Custom admin list columns for all CPTs
    '/inc/customizer.php',          // Theme Customizer options
    '/inc/customizer-pages.php',    // Page Content Customizer sections (migrated from ACF)
    '/inc/helper-functions.php',    // Utility functions
    '/inc/acf-helpers.php',         // ACF wrapper (emc_acf, emc_acf_image) with fallback
    '/inc/acf-fields.php',          // ACF field group definitions for page templates
    '/inc/shortcodes.php',          // Shortcodes (prayer times, campaign bar, etc.)
    '/inc/ajax-handlers.php',       // AJAX handlers (newsletter, contact form)
    '/inc/elementor-compat.php',    // Elementor compatibility (locations, style fixes)
    '/inc/elementor-widgets.php',   // Custom Elementor widgets (donate, prayer, counter)
    // Phase 11 — Demo Import System
    '/inc/demo-data.php',           // Demo content definitions (pages, menus, theme_mods)
    '/inc/demo-import.php',         // Import engine + AJAX handler
    '/inc/setup-wizard.php',        // Admin Appearance > EMC Setup wizard UI
    // Phase 12 — Performance Optimization
    '/inc/performance.php',         // Lazy-load, resource hints, bloat removal, query optimisation
);


foreach ( $emc_includes as $file ) {
    $filepath = EMC_DIR . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    }
}


/* ==========================================================================
   5b. Auto-seed Service Posts
   Creates the 6 core emc_service posts automatically on first theme load.
   Re-runs on any environment (local / Hostinger) where posts are missing.
   ========================================================================== */
function emc_auto_seed_services() {

    // Skip if already seeded on this install
    if ( get_option( 'emc_services_seeded' ) ) {
        return;
    }

    $services = array(
        array(
            'title'   => 'Arabic Education',
            'slug'    => 'arabic-education',
            'icon'    => 'fas fa-book-open',
            'excerpt' => 'Weekend Madrasah, Arabic classes, and Quran lessons for children and adults of all levels.',
            'order'   => 1,
            'content' => '<h2>About Our Arabic Education Programme</h2>
<p>At Essex Muslim Centre, we believe that every Muslim should have the opportunity to connect with the Quran and Islamic teachings in their original language. Our Arabic Education programme serves children and adults across Chelmsford and Essex.</p>
<h3>What We Offer</h3>
<ul>
<li><strong>Weekend Madrasah (Ages 5–16):</strong> Saturday and Sunday classes covering Quran recitation with Tajweed, Islamic Studies, Arabic language, and Seerah.</li>
<li><strong>Adult Arabic Classes:</strong> Beginner to advanced conversational and classical Arabic, taught in small groups by qualified instructors.</li>
<li><strong>Quran Memorisation (Hifz):</strong> Structured programme for dedicated students with individual mentor support.</li>
<li><strong>Quran Recitation &amp; Tajweed:</strong> Perfecting pronunciation and application of Tajweed rules for all ages.</li>
</ul>
<h3>Timings</h3>
<p>Weekend classes run Saturday and Sunday mornings. Adult classes are available on weekday evenings. Contact us for the current timetable.</p>
<h3>How to Enrol</h3>
<p>Registration is open at the beginning of each term. Contact our office or visit the centre to pick up a registration form. Subsidised rates are available for families in financial need.</p>',
        ),
        array(
            'title'   => 'Nikah (Marriage)',
            'slug'    => 'nikah-marriage',
            'icon'    => 'fas fa-ring',
            'excerpt' => 'Islamic marriage ceremonies conducted by our Imam with pre-marriage guidance and support.',
            'order'   => 2,
            'content' => '<h2>Nikah Ceremonies at EMC</h2>
<p>Marriage is half of one\'s deen. At Essex Muslim Centre, our Imams conduct Islamic marriage (Nikah) ceremonies in a blessed and dignified environment.</p>
<h3>Our Services Include</h3>
<ul>
<li><strong>Nikah Ceremony:</strong> A formal Islamic marriage contract conducted in the presence of witnesses and according to Sunnah.</li>
<li><strong>Pre-Marriage Guidance:</strong> One-to-one confidential sessions with our Imam covering rights, responsibilities, and expectations in Islamic marriage.</li>
<li><strong>Documentation Support:</strong> Guidance on obtaining the relevant civil marriage documents alongside the Islamic ceremony.</li>
<li><strong>Venue:</strong> Our main hall can accommodate families and guests for the ceremony.</li>
</ul>
<h3>How to Book</h3>
<p>To book a Nikah ceremony, please contact our office at least 4 weeks in advance. Both parties and their walis (guardians) should be present for the initial consultation.</p>',
        ),
        array(
            'title'   => 'Janaza Services',
            'slug'    => 'janaza-services',
            'icon'    => 'fas fa-praying-hands',
            'excerpt' => 'Compassionate funeral prayer, ghusl, and burial coordination services available 24/7.',
            'order'   => 3,
            'content' => '<h2>Janaza Services — Available 24/7</h2>
<p>Losing a loved one is one of life\'s most difficult moments. Essex Muslim Centre is here to support your family through the entire Janaza (funeral) process with compassion, care, and full adherence to Islamic tradition.</p>
<h3>Services We Provide</h3>
<ul>
<li><strong>Ghusl (Islamic Washing):</strong> Carried out by trained volunteers in our on-site ghusl facility, with separate facilities for male and female deceased.</li>
<li><strong>Kafan (Shrouding):</strong> Preparation and wrapping of the deceased according to Sunnah.</li>
<li><strong>Janaza Prayer (Salat ul-Janaza):</strong> Congregational funeral prayer led by our Imam.</li>
<li><strong>Burial Coordination:</strong> Liaison with local cemeteries including Chelmsford City Council cemeteries for Muslim burial plots.</li>
<li><strong>Repatriation Guidance:</strong> Support for families wishing to repatriate the deceased to their home country.</li>
</ul>
<h3>Contact Us Urgently</h3>
<p>For immediate Janaza support, contact our 24/7 emergency line. Our team will guide you through every step with sensitivity and Islamic care.</p>',
        ),
        array(
            'title'   => 'Meet an Imam',
            'slug'    => 'meet-an-imam',
            'icon'    => 'fas fa-user-tie',
            'excerpt' => 'Schedule a one-to-one appointment with our Imam for spiritual guidance, counselling, or Islamic advice.',
            'order'   => 4,
            'content' => '<h2>Meet an Imam — Private Appointments</h2>
<p>Our resident Imam is available for private, confidential one-to-one appointments. Whether you are seeking Islamic guidance, spiritual support, or advice on a personal matter, we are here to help.</p>
<h3>Areas of Guidance</h3>
<ul>
<li><strong>Spiritual Counselling:</strong> Personal struggles, mental wellbeing from an Islamic perspective, and strengthening one\'s relationship with Allah.</li>
<li><strong>Family &amp; Relationship Advice:</strong> Islamic guidance on marriage, divorce, parenting, and family conflict resolution.</li>
<li><strong>Islamic Rulings (Fatawa):</strong> Questions on halal/haram, worship, business transactions, and day-to-day Islamic practice.</li>
<li><strong>Reversion Support:</strong> Dedicated guidance for those considering or who have recently embraced Islam.</li>
<li><strong>Grief &amp; Bereavement:</strong> Spiritual support for those dealing with loss.</li>
</ul>
<h3>How to Book</h3>
<p>Appointments are available on weekday afternoons and by arrangement. Please contact the office to schedule a session. All conversations are strictly confidential.</p>',
        ),
        array(
            'title'   => 'Welfare Services',
            'slug'    => 'welfare-services',
            'icon'    => 'fas fa-hand-holding-heart',
            'excerpt' => 'Food bank partnerships, financial counselling, and support for families in need.',
            'order'   => 5,
            'content' => '<h2>Community Welfare &amp; Support</h2>
<p>Islam places great emphasis on caring for those in need. Essex Muslim Centre works to support vulnerable members of our community through a range of welfare services, carried out with dignity and respect.</p>
<h3>What We Offer</h3>
<ul>
<li><strong>Emergency Food Parcels:</strong> In partnership with local food banks, we arrange emergency food support for families in crisis.</li>
<li><strong>Zakat &amp; Sadaqah Distribution:</strong> We collect and distribute Zakat and Sadaqah funds to eligible recipients in the local Muslim community.</li>
<li><strong>Financial Guidance:</strong> Signposting to halal financial advice, benefits entitlement support, and debt counselling services.</li>
<li><strong>Mental Health Referrals:</strong> Culturally sensitive referrals to NHS and third-sector mental health support services.</li>
<li><strong>Community Befriending:</strong> Connecting isolated elderly or vulnerable individuals with community volunteers.</li>
</ul>
<h3>Confidential Support</h3>
<p>All welfare enquiries are handled confidentially by our trained volunteers. To speak to a welfare officer, please contact the centre directly.</p>',
        ),
        array(
            'title'   => 'General Events',
            'slug'    => 'general-events',
            'icon'    => 'fas fa-calendar-alt',
            'excerpt' => 'Community gatherings, Islamic talks, sports days, and interfaith activities throughout the year.',
            'order'   => 6,
            'content' => '<h2>EMC Events Programme</h2>
<p>Throughout the year, Essex Muslim Centre organises a rich and varied programme of events for all members of the community — young and old, Muslim and non-Muslim alike.</p>
<h3>Types of Events</h3>
<ul>
<li><strong>Islamic Talks &amp; Lectures:</strong> Monthly talks by visiting scholars and local Imams on topics relevant to contemporary Muslim life.</li>
<li><strong>Eid Celebrations:</strong> Annual Eid ul-Fitr and Eid ul-Adha community gatherings with food, activities, and celebrations.</li>
<li><strong>Ramadan Programme:</strong> Tarawih prayers, Iftar gatherings, Laylatul Qadr events, and charity fundraisers throughout the holy month.</li>
<li><strong>Youth Sports Days:</strong> Football, cricket, and multi-sport events for young people in the community.</li>
<li><strong>Interfaith Dialogue:</strong> Open days, mosque tours, and interfaith panel discussions welcoming neighbours of all faiths.</li>
<li><strong>Fundraising Dinners:</strong> Annual gala dinners and community fundraisers supporting the building campaign.</li>
</ul>
<h3>Stay Updated</h3>
<p>Follow our Events page and social media channels to stay up to date with upcoming events. All are welcome unless otherwise stated. Many events are free to attend.</p>',
        ),
    );

    $all_done = true;

    foreach ( $services as $svc ) {
        $existing = get_page_by_path( $svc['slug'], OBJECT, 'emc_service' );
        if ( $existing ) {
            continue; // already exists, skip
        }

        $post_id = wp_insert_post( array(
            'post_type'    => 'emc_service',
            'post_title'   => $svc['title'],
            'post_name'    => $svc['slug'],
            'post_content' => $svc['content'],
            'post_excerpt' => $svc['excerpt'],
            'post_status'  => 'publish',
            'menu_order'   => $svc['order'],
        ) );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_emc_service_icon',     $svc['icon'] );
            update_post_meta( $post_id, '_emc_service_order',    $svc['order'] );
            update_post_meta( $post_id, '_emc_service_featured', '1' );
        } else {
            $all_done = false;
        }
    }

    // Flush rewrite rules so /service/slug/ URLs work immediately
    flush_rewrite_rules( false );

    // Mark as done so this block never runs again on this install
    if ( $all_done ) {
        update_option( 'emc_services_seeded', '1' );
    }
}
add_action( 'init', 'emc_auto_seed_services', 20 );


/* ==========================================================================
   6. Body Classes
   ========================================================================== */
function emc_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'singular';
    }
    if ( ! is_front_page() ) {
        $classes[] = 'inner-page';
    }
    return $classes;
}
add_filter( 'body_class', 'emc_body_classes' );


/* ==========================================================================
   7. Custom Excerpt Length
   ========================================================================== */
function emc_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'emc_excerpt_length', 999 );

function emc_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'emc_excerpt_more' );


/* ==========================================================================
   8. Security: Remove WordPress Version from Head
   ========================================================================== */
remove_action( 'wp_head', 'wp_generator' );


/* ==========================================================================
   9. Admin Bar Push-Down Fix
   ========================================================================== */
function emc_admin_bar_offset() {
    if ( is_admin_bar_showing() ) {
        echo '<style>
            .prayer-top-bar { top: 32px !important; }
            .main-header    { top: calc(32px + 90px) !important; }
            @media screen and (max-width:782px) {
                .prayer-top-bar { display: none; }
                .main-header    { top: 46px !important; }
            }
        </style>';
    }
}
add_action( 'wp_head', 'emc_admin_bar_offset' );
