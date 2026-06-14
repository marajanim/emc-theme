<?php
/**
 * Template Name: Services
 * Template Post Type: page
 *
 * EMC Theme — Services page template.
 * All content editable via ACF fields (registered in inc/acf-fields.php).
 *
 * @package emc-theme
 */

get_header();

wp_enqueue_style( 'emc-page-services', EMC_ASSETS . '/css/services.css', array( 'emc-style' ), EMC_VERSION );

// Resolve common URLs once so all sections can reference them
$events_page  = get_page_by_path( 'events' );
$events_url   = $events_page ? get_permalink( $events_page ) : home_url( '/events/' );
$contact_page = get_page_by_path( 'contact' );
$contact_url  = $contact_page ? get_permalink( $contact_page ) : home_url( '/contact/' );
$prayer_page  = get_page_by_path( 'prayer-times' );
$prayer_url   = $prayer_page ? get_permalink( $prayer_page ) : home_url( '/prayer-times/' );
$about_page   = get_page_by_path( 'about' );
$about_url    = $about_page ? get_permalink( $about_page ) : home_url( '/about/' );
?>

<!-- Hero -->
<section class="services-hero">
    <div class="container">
        <div class="page-hero-content">
            <span class="badge" style="background:rgba(14,107,71,0.2);border-color:rgba(14,107,71,0.4);color:#7FFFB4;"><i class="fas fa-hands-helping"></i> <?php echo esc_html( emc_acf( 'svc_hero_badge', __( 'What We Offer', 'emc-theme' ) ) ); ?></span>
            <h1><?php echo esc_html( emc_acf( 'svc_hero_title', __( 'Our Services', 'emc-theme' ) ) ); ?></h1>
            <p><?php echo esc_html( emc_acf( 'svc_hero_desc', __( 'From daily prayers to youth education, health support to spiritual guidance — Essex Muslim Centre provides a broad range of services rooted in Islamic values.', 'emc-theme' ) ) ); ?></p>
        </div>
    </div>
</section>

<!-- Quick Nav Tabs -->
<div class="services-tab-nav" id="services-tab-nav">
    <div class="container">
        <div class="svc-tabs">
            <a href="#friday-prayers" class="svc-tab active"><i class="fas fa-praying-hands"></i> <?php echo esc_html( emc_acf( 'svc_tab_friday', 'Friday Prayers' ) ); ?></a>
            <a href="#youth" class="svc-tab"><i class="fas fa-users"></i> <?php echo esc_html( emc_acf( 'svc_tab_youth', 'Youth' ) ); ?></a>
            <a href="#reversion" class="svc-tab"><i class="fas fa-book-open"></i> <?php echo esc_html( emc_acf( 'svc_tab_reversion', 'Reversion' ) ); ?></a>
            <a href="#wellbeing" class="svc-tab"><i class="fas fa-heartbeat"></i> <?php echo esc_html( emc_acf( 'svc_tab_wellbeing', 'Wellbeing' ) ); ?></a>
        </div>
    </div>
</div>

<!-- SERVICE 1: FRIDAY PRAYERS -->
<section class="service-detail section-padding" id="friday-prayers">
    <div class="container">
        <div class="service-detail-layout">
            <div class="service-detail-content scroll-reveal">
                <div class="service-icon-lg"><i class="fas fa-praying-hands"></i></div>
                <div class="section-header left">
                    <span class="subtitle"><?php echo esc_html( emc_acf( 'svc_friday_subtitle', __( 'Salah', 'emc-theme' ) ) ); ?></span>
                    <h2><?php echo esc_html( emc_acf( 'svc_friday_heading', __( 'Friday Prayers (Jumu\'ah)', 'emc-theme' ) ) ); ?></h2>
                </div>
                <p><?php echo esc_html( emc_acf( 'svc_friday_body_1', __( 'Jumu\'ah is the most important congregational prayer of the week. At Essex Muslim Centre, we hold two Jumu\'ah Khutbahs (sermons) to accommodate our growing congregation.', 'emc-theme' ) ) ); ?></p>
                <p><?php echo esc_html( emc_acf( 'svc_friday_body_2', __( 'Our Imams deliver relevant, contemporary Khutbahs addressing the needs of Muslims living in modern Britain, drawing from authentic Islamic scholarship.', 'emc-theme' ) ) ); ?></p>

                <div class="service-time-box">
                    <h4><i class="fas fa-clock"></i> <?php esc_html_e( 'Prayer Times', 'emc-theme' ); ?></h4>
                    <div class="svc-time-grid">
                        <div class="svc-time-item">
                            <span class="svc-time-label"><?php esc_html_e( 'First Khutbah', 'emc-theme' ); ?></span>
                            <span class="svc-time-val"><?php echo esc_html( emc_acf( 'svc_friday_khutbah_1', '12:30 PM' ) ); ?></span>
                        </div>
                        <div class="svc-time-item">
                            <span class="svc-time-label"><?php esc_html_e( 'Second Khutbah', 'emc-theme' ); ?></span>
                            <span class="svc-time-val"><?php echo esc_html( emc_acf( 'svc_friday_khutbah_2', '1:30 PM' ) ); ?></span>
                        </div>
                        <div class="svc-time-item">
                            <span class="svc-time-label"><?php esc_html_e( 'Gates Open', 'emc-theme' ); ?></span>
                            <span class="svc-time-val"><?php echo esc_html( emc_acf( 'svc_friday_gates', '12:00 PM' ) ); ?></span>
                        </div>
                        <div class="svc-time-item">
                            <span class="svc-time-label"><?php esc_html_e( 'Location', 'emc-theme' ); ?></span>
                            <span class="svc-time-val"><?php echo esc_html( emc_acf( 'svc_friday_location', __( 'Main Hall', 'emc-theme' ) ) ); ?></span>
                        </div>
                    </div>
                </div>

                <div class="service-features">
                    <?php
                    $feature_defaults = array(
                        __( 'Two Jumu\'ah sessions every Friday', 'emc-theme' ),
                        __( 'Separate sisters\' prayer area', 'emc-theme' ),
                        __( 'Wudu (ablution) facilities available', 'emc-theme' ),
                        __( 'Accessible for wheelchair users', 'emc-theme' ),
                        __( 'Free parking nearby', 'emc-theme' ),
                        __( 'Daily five prayers (contact for timings)', 'emc-theme' ),
                    );
                    for ( $i = 1; $i <= 6; $i++ ) :
                        $f = emc_acf( 'svc_friday_feature_' . $i, $feature_defaults[ $i - 1 ] );
                        if ( empty( $f ) ) continue;
                    ?>
                    <div class="svc-feature"><i class="fas fa-check-circle"></i> <?php echo esc_html( $f ); ?></div>
                    <?php endfor; ?>
                </div>

                <a href="<?php echo esc_url( $prayer_url ); ?>" class="btn btn-primary" style="margin-top:2rem;">
                    <i class="fas fa-clock"></i> <?php esc_html_e( 'View Full Prayer Times', 'emc-theme' ); ?>
                </a>
            </div>
            <div class="service-detail-img scroll-reveal" style="transition-delay:0.15s;">
                <?php $friday_img = emc_acf_image( 'svc_friday_image', EMC_ASSETS . '/gallery/Friday Prayer/Friday-1-600x450.jpeg' ); ?>
                <img src="<?php echo esc_url( $friday_img ); ?>" alt="<?php esc_attr_e( 'Friday Prayers at EMC', 'emc-theme' ); ?>">
                <div class="service-img-caption">
                    <i class="fas fa-users"></i>
                    <span><?php esc_html_e( 'Hundreds gather each Friday', 'emc-theme' ); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICE 2: YOUTH -->
<section class="service-detail service-alt section-padding" id="youth">
    <div class="container">
        <div class="service-detail-layout reverse">
            <div class="service-detail-img scroll-reveal">
                <img src="<?php echo esc_url( EMC_ASSETS . '/gallery/Outdoor Activity 2024/out_1-1-300x300.jpeg' ); ?>" alt="<?php esc_attr_e( 'Youth at EMC', 'emc-theme' ); ?>" style="width:100%;border-radius:var(--radius-lg);">
            </div>
            <div class="service-detail-content scroll-reveal" style="transition-delay:0.15s;">
                <div class="service-icon-lg youth-icon"><i class="fas fa-users"></i></div>
                <div class="section-header left">
                    <span class="subtitle"><?php echo esc_html( emc_acf( 'svc_youth_subtitle', __( 'Next Generation', 'emc-theme' ) ) ); ?></span>
                    <h2><?php echo esc_html( emc_acf( 'svc_youth_heading', __( 'Youth Programmes', 'emc-theme' ) ) ); ?></h2>
                </div>
                <p><?php echo esc_html( emc_acf( 'svc_youth_body', __( 'Our youth provision is at the heart of everything we do. We provide a safe, inclusive, and engaging environment for young Muslims to develop their faith, skills, and confidence.', 'emc-theme' ) ) ); ?></p>

                <div class="youth-programmes">
                    <?php
                    $prog_defaults = array(
                        array( 'icon' => 'fas fa-book-quran',     'title' => __( 'Quran & Islamic Studies', 'emc-theme' ),    'desc' => __( 'Weekly Tajweed and Quran classes for ages 6–16.', 'emc-theme' ) ),
                        array( 'icon' => 'fas fa-futbol',         'title' => __( 'Sports & Physical Activity', 'emc-theme' ), 'desc' => __( 'Football, cricket, basketball, and fitness sessions.', 'emc-theme' ) ),
                        array( 'icon' => 'fas fa-graduation-cap', 'title' => __( 'Academic Mentorship', 'emc-theme' ),         'desc' => __( 'Exam revision support and university guidance.', 'emc-theme' ) ),
                        array( 'icon' => 'fas fa-palette',        'title' => __( 'Arts & Creativity', 'emc-theme' ),           'desc' => __( 'Calligraphy, digital arts, and creative writing workshops.', 'emc-theme' ) ),
                    );
                    for ( $i = 1; $i <= 4; $i++ ) :
                        $d     = $prog_defaults[ $i - 1 ];
                        $icon  = emc_acf( 'svc_youth_prog_' . $i . '_icon',  $d['icon'] );
                        $title = emc_acf( 'svc_youth_prog_' . $i . '_title', $d['title'] );
                        $desc  = emc_acf( 'svc_youth_prog_' . $i . '_desc',  $d['desc'] );
                        if ( empty( $title ) ) continue;
                    ?>
                    <div class="prog-card">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                        <div>
                            <strong><?php echo esc_html( $title ); ?></strong>
                            <p><?php echo esc_html( $desc ); ?></p>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <div class="service-cta-group">
                    <a href="<?php echo esc_url( $events_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'View Youth Events', 'emc-theme' ); ?></a>
                    <a href="mailto:<?php echo esc_attr( emc_option( 'emc_admin_email', 'admin@essexmuslimcentre.org' ) ); ?>?subject=<?php echo esc_attr( rawurlencode( 'Youth Programme Enquiry' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Enquire', 'emc-theme' ); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICE 3: REVERSION -->
<section class="service-detail section-padding" id="reversion">
    <div class="container">
        <div class="service-detail-layout">
            <div class="service-detail-content scroll-reveal">
                <div class="service-icon-lg reversion-icon"><i class="fas fa-book-open"></i></div>
                <div class="section-header left">
                    <span class="subtitle"><?php echo esc_html( emc_acf( 'svc_reversion_subtitle', __( 'Embrace Islam', 'emc-theme' ) ) ); ?></span>
                    <h2><?php echo esc_html( emc_acf( 'svc_reversion_heading', __( 'Reversion to Islam', 'emc-theme' ) ) ); ?></h2>
                </div>
                <p><?php echo esc_html( emc_acf( 'svc_reversion_body_1', __( 'Embracing Islam is one of the most profound and transformative journeys a person can take. At Essex Muslim Centre, we provide a warm, judgment-free welcome to all those exploring or newly embracing the faith.', 'emc-theme' ) ) ); ?></p>
                <p><?php echo esc_html( emc_acf( 'svc_reversion_body_2', __( 'Our dedicated reversion team offers one-to-one support, structured learning, and a community of friends who understand the journey.', 'emc-theme' ) ) ); ?></p>

                <div class="reversion-steps">
                    <?php
                    $step_defaults = array(
                        array( 'title' => __( 'Initial Consultation', 'emc-theme' ), 'desc' => __( 'A private, confidential meeting with one of our trained volunteers.', 'emc-theme' ) ),
                        array( 'title' => __( 'Learning Resources', 'emc-theme' ),   'desc' => __( 'Access to books, audio, and online resources tailored to your pace.', 'emc-theme' ) ),
                        array( 'title' => __( 'Shahada Support', 'emc-theme' ),       'desc' => __( 'We are honoured to witness and support your declaration of faith.', 'emc-theme' ) ),
                        array( 'title' => __( 'Ongoing Community', 'emc-theme' ),     'desc' => __( 'Integration into our welcoming community with ongoing mentorship.', 'emc-theme' ) ),
                    );
                    for ( $i = 1; $i <= 4; $i++ ) :
                        $d     = $step_defaults[ $i - 1 ];
                        $title = emc_acf( 'svc_rev_step_' . $i . '_title', $d['title'] );
                        $desc  = emc_acf( 'svc_rev_step_' . $i . '_desc',  $d['desc'] );
                        if ( empty( $title ) ) continue;
                    ?>
                    <div class="rev-step">
                        <span class="rev-step-num"><?php echo esc_html( $i ); ?></span>
                        <div>
                            <strong><?php echo esc_html( $title ); ?></strong>
                            <p><?php echo esc_html( $desc ); ?></p>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <a href="mailto:<?php echo esc_attr( emc_option( 'emc_admin_email', 'admin@essexmuslimcentre.org' ) ); ?>?subject=<?php echo esc_attr( rawurlencode( 'Reversion Support Enquiry' ) ); ?>" class="btn btn-primary" style="margin-top:2rem;">
                    <i class="fas fa-envelope"></i> <?php esc_html_e( 'Contact Our Team', 'emc-theme' ); ?>
                </a>
            </div>
            <div class="service-detail-img scroll-reveal" style="transition-delay:0.15s;">
                <img src="<?php echo esc_url( EMC_ASSETS . '/gallery/Community Support Services/New-Muslim-600x338.jpeg' ); ?>" alt="<?php esc_attr_e( 'Open Doors, Open Hearts', 'emc-theme' ); ?>" style="width:100%;border-radius:var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

<!-- SERVICE 4: WELLBEING -->
<section class="service-detail service-alt section-padding" id="wellbeing">
    <div class="container">
        <div class="service-detail-layout reverse">
            <div class="service-detail-img scroll-reveal">
                <img src="<?php echo esc_url( EMC_ASSETS . '/gallery/Quran Group Study/WhatsApp-Image-2025-08-21-at-21.04.12-300x225.jpeg' ); ?>" alt="<?php esc_attr_e( 'Health & Wellbeing', 'emc-theme' ); ?>" style="width:100%;border-radius:var(--radius-lg);">
            </div>
            <div class="service-detail-content scroll-reveal" style="transition-delay:0.15s;">
                <div class="service-icon-lg wellbeing-icon"><i class="fas fa-heartbeat"></i></div>
                <div class="section-header left">
                    <span class="subtitle"><?php echo esc_html( emc_acf( 'svc_wellbeing_subtitle', __( 'Care & Support', 'emc-theme' ) ) ); ?></span>
                    <h2><?php echo esc_html( emc_acf( 'svc_wellbeing_heading', __( 'Health & Wellbeing', 'emc-theme' ) ) ); ?></h2>
                </div>
                <p><?php echo esc_html( emc_acf( 'svc_wellbeing_body', __( 'Islam places great importance on the health of both body and mind. Our Health & Wellbeing programme brings together NHS professionals, mental health experts, and community volunteers to serve Chelmsford\'s Muslim community.', 'emc-theme' ) ) ); ?></p>

                <div class="wellbeing-grid">
                    <?php
                    $wb_defaults = array(
                        array( 'icon' => 'fas fa-stethoscope',   'title' => __( 'Free Health Checks', 'emc-theme' ),     'desc' => __( 'Blood pressure, diabetes, and BMI screening.', 'emc-theme' ) ),
                        array( 'icon' => 'fas fa-brain',         'title' => __( 'Mental Health Support', 'emc-theme' ),   'desc' => __( 'Culturally sensitive counselling and peer support.', 'emc-theme' ) ),
                        array( 'icon' => 'fas fa-apple-alt',     'title' => __( 'Nutrition Advice', 'emc-theme' ),        'desc' => __( 'Halal dietary guidance from qualified nutritionists.', 'emc-theme' ) ),
                        array( 'icon' => 'fas fa-hands-helping',  'title' => __( 'Financial Hardship Aid', 'emc-theme' ), 'desc' => __( 'Confidential support and signposting for families in need.', 'emc-theme' ) ),
                    );
                    $delay = 0;
                    for ( $i = 1; $i <= 4; $i++ ) :
                        $d     = $wb_defaults[ $i - 1 ];
                        $icon  = emc_acf( 'svc_wb_item_' . $i . '_icon',  $d['icon'] );
                        $title = emc_acf( 'svc_wb_item_' . $i . '_title', $d['title'] );
                        $desc  = emc_acf( 'svc_wb_item_' . $i . '_desc',  $d['desc'] );
                        if ( empty( $title ) ) continue;
                    ?>
                    <div class="wb-item scroll-reveal"<?php echo $delay ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                        <strong><?php echo esc_html( $title ); ?></strong>
                        <p><?php echo esc_html( $desc ); ?></p>
                    </div>
                    <?php
                        $delay = round( $delay + 0.1, 1 );
                    endfor;
                    ?>
                </div>

                <div class="service-cta-group" style="margin-top:2rem;">
                    <a href="<?php echo esc_url( $events_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'View Wellbeing Events', 'emc-theme' ); ?></a>
                    <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-outline"><?php esc_html_e( 'Get Support', 'emc-theme' ); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="services-cta">
    <div class="container">
        <div class="services-cta-inner glass-card">
            <div class="svc-cta-text">
                <h2><?php echo esc_html( emc_acf( 'svc_cta_heading', __( 'Support Our Services', 'emc-theme' ) ) ); ?></h2>
                <p><?php echo esc_html( emc_acf( 'svc_cta_desc', __( 'Every donation helps us deliver these vital services to our community. Give today and be part of something lasting.', 'emc-theme' ) ) ); ?></p>
            </div>
            <div class="svc-cta-actions">
                <?php echo emc_donate_button( __( 'Donate Now', 'emc-theme' ) ); ?>
                <a href="<?php echo esc_url( $about_url ); ?>#vacancies" class="btn btn-outline"><?php esc_html_e( 'Volunteer', 'emc-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
