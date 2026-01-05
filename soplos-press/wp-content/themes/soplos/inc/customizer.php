<?php
/**
 * Soplos Theme - Customizer Settings
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Customizer settings
 */
function soplos_customize_register($wp_customize) {
    
    // PANEL: Soplos Theme Settings
    $wp_customize->add_panel('soplos_settings', array(
        'title'       => __('Soplos Theme Settings', 'soplos'),
        'description' => __('Customize your Soplos theme appearance and features.', 'soplos'),
        'priority'    => 30,
    ));
    
    // =============================================
    // SITE IDENTITY SECTION (title_tagline)
    // =============================================
    
    // Show/Hide Site Title
    $wp_customize->add_setting('soplos_show_site_title', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_show_site_title', array(
        'type'     => 'checkbox',
        'label'    => __('Mostrar título del sitio', 'soplos'),
        'section'  => 'title_tagline',
        'priority' => 50,
    ));
    
    // Show/Hide Site Tagline
    $wp_customize->add_setting('soplos_show_site_tagline', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_show_site_tagline', array(
        'type'     => 'checkbox',
        'label'    => __('Mostrar descripción corta', 'soplos'),
        'section'  => 'title_tagline',
        'priority' => 51,
    ));
    
    // Retina Logo (2x)
    $wp_customize->add_setting('soplos_retina_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'soplos_retina_logo', array(
        'label'       => __('Logo Retina (2x)', 'soplos'),
        'description' => __('Sube una versión del logo al doble de tamaño para pantallas de alta densidad (HiDPI/Retina).', 'soplos'),
        'section'     => 'title_tagline',
        'priority'    => 9,
    )));
    
    // Mobile Logo
    $wp_customize->add_setting('soplos_mobile_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'soplos_mobile_logo', array(
        'label'       => __('Logo Móvil', 'soplos'),
        'description' => __('Logo alternativo más pequeño para dispositivos móviles.', 'soplos'),
        'section'     => 'title_tagline',
        'priority'    => 10,
    )));
    
    // Footer Logo
    $wp_customize->add_setting('soplos_footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'soplos_footer_logo', array(
        'label'       => __('Logo del Footer', 'soplos'),
        'description' => __('Logo alternativo para mostrar en el pie de página.', 'soplos'),
        'section'     => 'title_tagline',
        'priority'    => 11,
    )));
    
    // SECTION: Colors
    $wp_customize->add_section('soplos_colors', array(
        'title'    => __('Theme Colors', 'soplos'),
        'panel'    => 'soplos_settings',
        'priority' => 10,
    ));
    
    // Primary Color
    $wp_customize->add_setting('soplos_primary_color', array(
        'default'           => '#ff7d2e',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_primary_color', array(
        'label'    => __('Primary Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));
    
    // Secondary Color
    $wp_customize->add_setting('soplos_secondary_color', array(
        'default'           => '#fec431',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_secondary_color', array(
        'label'    => __('Secondary Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));
    
    // Accent Color
    $wp_customize->add_setting('soplos_accent_color', array(
        'default'           => '#ef5a33',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_accent_color', array(
        'label'    => __('Accent Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Background Color
    $wp_customize->add_setting('soplos_bg_color', array(
        'default'           => '#252527',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_bg_color', array(
        'label'    => __('Background Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Text Color
    $wp_customize->add_setting('soplos_text_color', array(
        'default'           => '#c0c0c0',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_text_color', array(
        'label'    => __('Text Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Card Background
    $wp_customize->add_setting('soplos_card_bg', array(
        'default'           => '#303032',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_card_bg', array(
        'label'    => __('Card Background', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Heading Color
    $wp_customize->add_setting('soplos_heading_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_heading_color', array(
        'label'    => __('Heading Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Link Color
    $wp_customize->add_setting('soplos_link_color', array(
        'default'           => '#ff7d2e',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_link_color', array(
        'label'    => __('Link Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Link Hover Color
    $wp_customize->add_setting('soplos_link_hover_color', array(
        'default'           => '#fec431',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_link_hover_color', array(
        'label'    => __('Link Hover Color', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Header Background
    $wp_customize->add_setting('soplos_header_bg', array(
        'default'           => '#252527',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_header_bg', array(
        'label'    => __('Header Background', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Footer Background
    $wp_customize->add_setting('soplos_footer_bg', array(
        'default'           => '#1a1a1c',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'soplos_footer_bg', array(
        'label'    => __('Footer Background', 'soplos'),
        'section'  => 'soplos_colors',
    )));

    // Border Color
    $wp_customize->add_setting('soplos_border_color', array(
        'default'           => 'rgba(255,255,255,0.1)',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('soplos_border_color', array(
        'type'        => 'text',
        'label'       => __('Border Color', 'soplos'),
        'description' => __('Hex o rgba (ej: rgba(255,255,255,0.1))', 'soplos'),
        'section'     => 'soplos_colors',
    ));
    
    // SECTION: Header
    $wp_customize->add_section('soplos_header', array(
        'title'    => __('Header Settings', 'soplos'),
        'panel'    => 'soplos_settings',
        'priority' => 20,
    ));
    
    // Sticky Header
    $wp_customize->add_setting('soplos_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_sticky_header', array(
        'type'    => 'checkbox',
        'label'   => __('Enable Sticky Header', 'soplos'),
        'section' => 'soplos_header',
        'priority' => 10,
    ));

    // Hide Header on Scroll
    $wp_customize->add_setting('soplos_hide_header_scroll', array(
        'default'           => false,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_hide_header_scroll', array(
        'type'    => 'checkbox',
        'label'   => __('Hide Header on Scroll Down', 'soplos'),
        'description' => __('Oculta el menú al bajar y lo muestra al subir.', 'soplos'),
        'section' => 'soplos_header',
        'priority' => 11,
    ));

    // Transparent Header (Home)
    $wp_customize->add_setting('soplos_transparent_header_home', array(
        'default'           => false,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_transparent_header_home', array(
        'type'    => 'checkbox',
        'label'   => __('Transparent Header on Homepage', 'soplos'),
        'description' => __('Header transparente en inicio (sobrepone al contenido).', 'soplos'),
        'section' => 'soplos_header',
        'priority' => 12,
    ));

    // Header Layout
    $wp_customize->add_setting('soplos_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_header_layout', array(
        'type'    => 'select',
        'label'   => __('Header Layout', 'soplos'),
        'section' => 'soplos_header',
        'choices' => array(
            'default'   => __('Standard (Logo Left, Menu Right)', 'soplos'),
            'center'    => __('Centered (Logo Top, Menu Bottom)', 'soplos'),
            'expanded'  => __('Expanded (Full Width)', 'soplos'),
        ),
        'priority' => 15,
    ));

    // Mobile Menu Style
    $wp_customize->add_setting('soplos_mobile_menu_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_mobile_menu_style', array(
        'type'    => 'select',
        'label'   => __('Mobile Menu Style', 'soplos'),
        'section' => 'soplos_header',
        'choices' => array(
            'default' => __('Slide Down (Default)', 'soplos'),
            'overlay' => __('Fullscreen Overlay', 'soplos'),
            'sidebar' => __('Sidebar Slide-in (Left)', 'soplos'),
            'sidebar-right' => __('Sidebar Slide-in (Right)', 'soplos'),
        ),
        'priority' => 16,
    ));

    // =============================================
    // TOP BAR SETTINGS (Inside Header Section)
    // =============================================
    
    // Enable Top Bar
    $wp_customize->add_setting('soplos_enable_topbar', array(
        'default'           => false,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_enable_topbar', array(
        'type'    => 'checkbox',
        'label'   => __('Enable Top Bar', 'soplos'),
        'section' => 'soplos_header', // Moved to Header
        'priority' => 40,
    ));

    // Top Bar Text (Left)
    $wp_customize->add_setting('soplos_topbar_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('soplos_topbar_text', array(
        'type'    => 'textarea',
        'label'   => __('Top Bar Text', 'soplos'),
        'description' => __('Texto o HTML simple para la izquierda de la barra.', 'soplos'),
        'section' => 'soplos_header', // Moved to Header
        'priority' => 41,
    ));

    // Show Social Icons
    $wp_customize->add_setting('soplos_topbar_show_social', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_topbar_show_social', array(
        'type'    => 'checkbox',
        'label'   => __('Show Social Icons in Top Bar', 'soplos'),
        'section' => 'soplos_header', // Moved to Header
        'priority' => 42,
    ));

    // Social Icons Position
    $wp_customize->add_setting('soplos_topbar_social_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_topbar_social_position', array(
        'type'    => 'select',
        'label'   => __('Social Icons Position', 'soplos'),
        'section' => 'soplos_header',
        'choices' => array(
            'left'   => __('Left', 'soplos'),
            'center' => __('Center', 'soplos'),
            'right'  => __('Right', 'soplos'),
        ),
        'priority' => 43,
    ));

    // Social Icons Size
    $wp_customize->add_setting('soplos_topbar_icon_size', array(
        'default'           => 14,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('soplos_topbar_icon_size', array(
        'type'        => 'number',
        'label'       => __('Social Icons Size (px)', 'soplos'),
        'section'     => 'soplos_header',
        'input_attrs' => array('min' => 10, 'max' => 30),
        'priority' => 44,
    ));
    
    // Custom Logo URL
    $wp_customize->add_setting('soplos_logo_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('soplos_logo_url', array(
        'type'        => 'url',
        'label'       => __('Logo Link URL', 'soplos'),
        'description' => __('Leave empty to link to WordPress home. Enter a custom URL (e.g., https://soplos.org) to override.', 'soplos'),
        'section'     => 'soplos_header',
        'priority'    => 20,
    ));

    // Logo Size
    $wp_customize->add_setting('soplos_logo_size', array(
        'default'           => 50,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('soplos_logo_size', array(
        'type'        => 'number',
        'label'       => __('Logo Height (px)', 'soplos'),
        'section'     => 'soplos_header',
        'input_attrs' => array('min' => 20, 'max' => 200),
        'priority'    => 21,
    ));

    // Show Search in Header
    $wp_customize->add_setting('soplos_header_show_search', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_header_show_search', array(
        'type'    => 'checkbox',
        'label'   => __('Show Search in Header', 'soplos'),
        'section' => 'soplos_header',
        'priority' => 30,
    ));

    // Search Icon Position
    $wp_customize->add_setting('soplos_header_search_position', array(
        'default'           => 'after_menu',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_header_search_position', array(
        'type'    => 'select',
        'label'   => __('Search Icon Position', 'soplos'),
        'section' => 'soplos_header',
        'choices' => array(
            'before_menu' => __('Before Menu', 'soplos'),
            'after_menu'  => __('After Menu', 'soplos'),
        ),
        'priority' => 31,
    ));
    
    // SECTION: Footer
    $wp_customize->add_section('soplos_footer', array(
        'title'    => __('Footer Settings', 'soplos'),
        'panel'    => 'soplos_settings',
        'priority' => 30,
    ));
    
    // Show Footer Credits
    $wp_customize->add_setting('soplos_show_footer_credits', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_show_footer_credits', array(
        'type'    => 'checkbox',
        'label'   => __('Show Footer Credits', 'soplos'),
        'section' => 'soplos_footer',
    ));

    // Show Footer Widgets
    $wp_customize->add_setting('soplos_show_footer_widgets', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_show_footer_widgets', array(
        'type'    => 'checkbox',
        'label'   => __('Show Footer Widgets Area', 'soplos'),
        'section' => 'soplos_footer',
    ));

    // Footer Columns
    $wp_customize->add_setting('soplos_footer_columns', array(
        'default'           => '3',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_footer_columns', array(
        'type'    => 'select',
        'label'   => __('Footer Columns', 'soplos'),
        'section' => 'soplos_footer',
        'choices' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4'),
    ));

    // Footer Social Icons Enable
    $wp_customize->add_setting('soplos_footer_enable_social', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_footer_enable_social', array(
        'type'    => 'checkbox',
        'label'   => __('Show Social Icons in Footer', 'soplos'),
        'section' => 'soplos_footer',
    ));

    // Footer Social Icons Position
    $wp_customize->add_setting('soplos_footer_social_position', array(
        'default'           => 'bottom_bar',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_footer_social_position', array(
        'type'    => 'select',
        'label'   => __('Social Icons Position', 'soplos'),
        'section' => 'soplos_footer',
        'choices' => array(
            'above_widgets' => __('Above Widgets', 'soplos'),
            'bottom_bar'    => __('Bottom Bar (Copyright Area)', 'soplos'),
        ),
    ));

    // Copyright Text
    $wp_customize->add_setting('soplos_copyright_text', array(
        'default'           => '© ' . date('Y') . ' SoplosLinux. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('soplos_copyright_text', array(
        'type'    => 'text',
        'label'   => __('Copyright Text', 'soplos'),
        'section' => 'soplos_footer',
    ));
    
    // Old Social Logic Removed (Replaced by Unified Manager below)
    
    // SECTION: Forum Settings
    $wp_customize->add_section('soplos_forum', array(
        'title'       => __('Forum Settings', 'soplos'),
        'panel'       => 'soplos_settings',
        'priority'    => 50,
    ));
    
    // Forum Hero Title
    $wp_customize->add_setting('soplos_forum_title', array(
        'default'           => __('Community Forums', 'soplos'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('soplos_forum_title', array(
        'type'    => 'text',
        'label'   => __('Forum Page Title', 'soplos'),
        'section' => 'soplos_forum',
    ));
    
    // Forum Description
    $wp_customize->add_setting('soplos_forum_description', array(
        'default'           => __('Join our community. Ask questions, share knowledge, and connect with other Soplos Linux users.', 'soplos'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('soplos_forum_description', array(
        'type'    => 'textarea',
        'label'   => __('Forum Description', 'soplos'),
        'section' => 'soplos_forum',
    ));
    
    // SECTION: Layout
    $wp_customize->add_section('soplos_layout', array(
        'title'    => __('Layout Settings', 'soplos'),
        'panel'    => 'soplos_settings',
        'priority' => 60,
    ));
    
    // Blog Layout
    $wp_customize->add_setting('soplos_blog_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_blog_layout', array(
        'type'    => 'select',
        'label'   => __('Blog Posts Layout', 'soplos'),
        'section' => 'soplos_layout',
        'choices' => array(
            'grid' => __('Grid (Cards)', 'soplos'),
            'list' => __('List', 'soplos'),
        ),
    ));
    
    // Show Back to Top Button
    $wp_customize->add_setting('soplos_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_back_to_top', array(
        'type'    => 'checkbox',
        'label'   => __('Show Back to Top Button', 'soplos'),
        'section' => 'soplos_layout',
    ));


    // --- SOCIAL NETWORKS SECTION ---
    $wp_customize->add_section('soplos_social', array(
        'title'    => __('Social Networks', 'soplos'),
        'panel'    => 'soplos_settings',
        'priority' => 30,
    ));

    // Define all available networks
    $social_networks_list = array(
        'facebook'  => 'Facebook',
        'twitter'   => 'Twitter / X',
        'instagram' => 'Instagram',
        'youtube'   => 'YouTube',
        'linkedin'  => 'LinkedIn',
        'tiktok'    => 'TikTok',
        'twitch'    => 'Twitch',
        'github'    => 'GitHub',
        'discord'   => 'Discord',
        'telegram'  => 'Telegram',
        'paypal'    => 'PayPal',
        'mastodon'  => 'Mastodon',
        'rss'       => 'RSS Feed',
    );
    $default_order = implode(',', array_keys($social_networks_list));

    // Manager Control (Sort + Edit)
    require_once get_template_directory() . '/inc/custom-controls.php';
    
    $wp_customize->add_setting('soplos_social_order', array(
        'default'           => $default_order,
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new Soplos_Social_Manager_Control($wp_customize, 'soplos_social_order', array(
        'label'       => __('Social Networks Manager', 'soplos'),
        'description' => __('Drag to reorder. Enter URL to enable icon.', 'soplos'),
        'section'     => 'soplos_social',
        'choices'     => $social_networks_list,
        'priority'    => 5,
    )));

    // Individual URL Settings (Hidden Controls to ensure JS API availability)
    foreach ($social_networks_list as $net_id => $net_label) {
        $wp_customize->add_setting("soplos_{$net_id}_url", array('default' => '', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh'));
        // Note: Controls are handled by Soplos_Social_Manager_Control. No individual controls needed.
    }

    // SECTION: Typography
    $wp_customize->add_section('soplos_typography', array(
        'title'    => __('Typography', 'soplos'),
        'panel'    => 'soplos_settings',
        'priority' => 25,
    ));

    // Common Font Choices
    $font_choices = array(
        'system-ui'       => 'System Default',
        'inherit'         => 'Inherit (Use Base)',
        'Inter'           => 'Inter',
        'Roboto'          => 'Roboto',
        'Open Sans'       => 'Open Sans',
        'Lato'            => 'Lato',
        'Montserrat'      => 'Montserrat',
        'Poppins'         => 'Poppins',
        'Merriweather'    => 'Merriweather',
        'Playfair Display'=> 'Playfair Display',
        'Nunito'          => 'Nunito',
        'Raleway'         => 'Raleway',
        'Noto Sans'       => 'Noto Sans',
    );
    
    // Remove 'inherit' for Base Body
    $body_font_choices = $font_choices;
    unset($body_font_choices['inherit']);

    // --- BODY BASE ---
    $wp_customize->add_setting('soplos_body_font_family', array('default' => 'system-ui', 'sanitize_callback' => 'soplos_sanitize_select'));
    $wp_customize->add_control('soplos_body_font_family', array(
        'type' => 'select', 'label' => __('Body Font Family', 'soplos'), 'section' => 'soplos_typography', 'choices' => $body_font_choices, 'priority' => 10
    ));
    
    $wp_customize->add_setting('soplos_body_font_weight', array('default' => '400', 'sanitize_callback' => 'soplos_sanitize_select'));
    $wp_customize->add_control('soplos_body_font_weight', array(
        'type' => 'select', 'label' => __('Body Font Weight', 'soplos'), 'section' => 'soplos_typography', 'priority' => 11,
        'choices' => array('300'=>'Light 300', '400'=>'Regular 400', '500'=>'Medium 500', '600'=>'SemiBold 600', '700'=>'Bold 700')
    ));

    // --- HEADINGS BASE ---
    $heading_base_choices = $font_choices;
    unset($heading_base_choices['inherit']); // Base heading cannot inherit from itself
    
    $wp_customize->add_setting('soplos_heading_font_family', array('default' => 'system-ui', 'sanitize_callback' => 'soplos_sanitize_select'));
    $wp_customize->add_control('soplos_heading_font_family', array(
        'type' => 'select', 'label' => __('Headings Base Family', 'soplos'), 'description' => __('Default font for all headings.', 'soplos'), 'section' => 'soplos_typography', 'choices' => $heading_base_choices, 'priority' => 20
    ));

    // --- GRANULAR CONTROLS (H1 - H6) ---
    $headings = array('h1'=>'H1', 'h2'=>'H2', 'h3'=>'H3', 'h4'=>'H4', 'h5'=>'H5', 'h6'=>'H6');
    $priority = 30;

    foreach ($headings as $tag => $label) {
        // Separator
        $wp_customize->add_setting("soplos_{$tag}_separator", array('default' => ''));
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, "soplos_{$tag}_separator", array(
            'section' => 'soplos_typography', 'settings' => "soplos_{$tag}_separator", 'type' => 'hidden', 'label' => "--- $label Settings ---", 'priority' => $priority++
        )));

        // Family
        $wp_customize->add_setting("soplos_{$tag}_font_family", array('default' => 'inherit', 'sanitize_callback' => 'soplos_sanitize_select'));
        $wp_customize->add_control("soplos_{$tag}_font_family", array(
            'type' => 'select', 'label' => __("$label Family", 'soplos'), 'section' => 'soplos_typography', 'choices' => $font_choices, 'priority' => $priority++
        ));

        // Weight
        $wp_customize->add_setting("soplos_{$tag}_font_weight", array('default' => 'inherit', 'sanitize_callback' => 'soplos_sanitize_select'));
        $wp_customize->add_control("soplos_{$tag}_font_weight", array(
            'type' => 'select', 'label' => __("$label Weight", 'soplos'), 'section' => 'soplos_typography', 'priority' => $priority++,
            'choices' => array(
                'inherit' => 'Inherit', '100'=>'100', '200'=>'200', '300'=>'300', '400'=>'400', '500'=>'500', 
                '600'=>'600', '700'=>'700', '800'=>'800', '900'=>'900'
            )
        ));

        // Size (Optional: could add default sizes per tag if desired, but starting simple)
        $default_sizes = array('h1'=>42, 'h2'=>36, 'h3'=>30, 'h4'=>24, 'h5'=>20, 'h6'=>18);
        $wp_customize->add_setting("soplos_{$tag}_font_size", array('default' => $default_sizes[$tag], 'sanitize_callback' => 'absint'));
        $wp_customize->add_control("soplos_{$tag}_font_size", array(
            'type' => 'number', 'label' => __("$label Size (px)", 'soplos'), 'section' => 'soplos_typography', 'priority' => $priority++,
            'input_attrs' => array('min' => 10, 'max' => 100)
        ));
    }

    // Base Font Size
    $wp_customize->add_setting('soplos_body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('soplos_body_font_size', array(
        'type'        => 'number',
        'label'       => __('Base Font Size (px)', 'soplos'),
        'section'     => 'soplos_typography',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));

    // SECTION: Blog Settings (Expanding Layout)
    // Show Post Date
    $wp_customize->add_setting('soplos_show_post_date', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_show_post_date', array(
        'type'    => 'checkbox',
        'label'   => __('Show Post Date', 'soplos'),
        'section' => 'soplos_layout',
    ));

    // Show Post Author
    $wp_customize->add_setting('soplos_show_post_author', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_show_post_author', array(
        'type'    => 'checkbox',
        'label'   => __('Show Post Author', 'soplos'),
        'section' => 'soplos_layout',
    ));

    // Show Categories
    $wp_customize->add_setting('soplos_show_post_cats', array(
        'default'           => true,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_show_post_cats', array(
        'type'    => 'checkbox',
        'label'   => __('Show Categories', 'soplos'),
        'section' => 'soplos_layout',
    ));
    
    // Excerpt Length
    $wp_customize->add_setting('soplos_excerpt_length_num', array(
        'default'           => 25,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('soplos_excerpt_length_num', array(
        'type'        => 'number',
        'label'       => __('Excerpt Word Count', 'soplos'),
        'section'     => 'soplos_layout',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 1,
        ),
    ));

    // SECTION: General Settings
    $wp_customize->add_section('soplos_general', array(
        'title'    => __('General Settings', 'soplos'),
        'panel'    => 'soplos_settings',
        'priority' => 5,
    ));

    // --- PRELOADER ---
    $wp_customize->add_setting('soplos_enable_preloader', array(
        'default'           => false,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_enable_preloader', array(
        'type'    => 'checkbox',
        'label'   => __('Habilitar Preloader', 'soplos'),
        'description' => __('Muestra una animación de carga al entrar al sitio.', 'soplos'),
        'section' => 'soplos_general',
    ));

    $wp_customize->add_setting('soplos_preloader_type', array(
        'default'           => 'spinner',
        'sanitize_callback' => 'soplos_sanitize_select',
    ));
    $wp_customize->add_control('soplos_preloader_type', array(
        'type'    => 'select',
        'label'   => __('Tipo de Preloader', 'soplos'),
        'section' => 'soplos_general',
        'choices' => array(
            'spinner' => __('Spinner', 'soplos'),
            'logo'    => __('Imagen/Logo', 'soplos'),
            'bar'     => __('Barra de progreso', 'soplos'),
        ),
    ));

    // Preloader Image
    $wp_customize->add_setting('soplos_preloader_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'soplos_preloader_image', array(
        'label'       => __('Imagen del Preloader', 'soplos'),
        'description' => __('Sube una imagen para mostrar durante la carga. Recomendado: PNG transparente, máx 150px.', 'soplos'),
        'section'     => 'soplos_general',
    )));

    // --- READING PROGRESS BAR ---
    $wp_customize->add_setting('soplos_reading_progress', array(
        'default'           => false,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_reading_progress', array(
        'type'    => 'checkbox',
        'label'   => __('Barra de progreso de lectura', 'soplos'),
        'description' => __('Muestra una barra indicando cuánto has leído del artículo.', 'soplos'),
        'section' => 'soplos_general',
    ));

    // --- MAINTENANCE MODE ---
    $wp_customize->add_setting('soplos_maintenance_mode', array(
        'default'           => false,
        'sanitize_callback' => 'soplos_sanitize_checkbox',
    ));
    $wp_customize->add_control('soplos_maintenance_mode', array(
        'type'    => 'checkbox',
        'label'   => __('Modo Mantenimiento', 'soplos'),
        'description' => __('Muestra una página de mantenimiento a visitantes (admins pueden ver el sitio).', 'soplos'),
        'section' => 'soplos_general',
    ));

    // Maintenance Title
    $wp_customize->add_setting('soplos_maintenance_title', array(
        'default'           => __('Sitio en Mantenimiento', 'soplos'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('soplos_maintenance_title', array(
        'type'    => 'text',
        'label'   => __('Título de Mantenimiento', 'soplos'),
        'section' => 'soplos_general',
    ));

    // Maintenance Message
    $wp_customize->add_setting('soplos_maintenance_message', array(
        'default'           => __('Estamos realizando mejoras. Volvemos pronto.', 'soplos'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('soplos_maintenance_message', array(
        'type'    => 'textarea',
        'label'   => __('Mensaje de Mantenimiento', 'soplos'),
        'section' => 'soplos_general',
    ));

    // Maintenance Logo
    $wp_customize->add_setting('soplos_maintenance_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'soplos_maintenance_logo', array(
        'label'       => __('Logo de Mantenimiento', 'soplos'),
        'description' => __('Logo personalizado para la página de mantenimiento.', 'soplos'),
        'section'     => 'soplos_general',
    )));

    // Maintenance Background
    $wp_customize->add_setting('soplos_maintenance_bg', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'soplos_maintenance_bg', array(
        'label'       => __('Fondo de Mantenimiento', 'soplos'),
        'description' => __('Imagen de fondo para la página de mantenimiento.', 'soplos'),
        'section'     => 'soplos_general',
    )));

    // --- GOOGLE ANALYTICS ---
    $wp_customize->add_setting('soplos_google_analytics', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('soplos_google_analytics', array(
        'type'        => 'text',
        'label'       => __('Google Analytics ID', 'soplos'),
        'description' => __('Introduce tu ID de seguimiento (ej: G-XXXXXXXXXX o UA-XXXXXXXXX-X).', 'soplos'),
        'section'     => 'soplos_general',
    ));

    // --- CUSTOM SCRIPTS ---
    $wp_customize->add_setting('soplos_head_scripts', array(
        'default'           => '',
        'sanitize_callback' => 'soplos_sanitize_scripts',
    ));
    $wp_customize->add_control('soplos_head_scripts', array(
        'type'        => 'textarea',
        'label'       => __('Scripts en <head>', 'soplos'),
        'description' => __('Código para insertar antes de </head> (ej: meta tags, pixels).', 'soplos'),
        'section'     => 'soplos_general',
    ));

    $wp_customize->add_setting('soplos_footer_scripts', array(
        'default'           => '',
        'sanitize_callback' => 'soplos_sanitize_scripts',
    ));
    $wp_customize->add_control('soplos_footer_scripts', array(
        'type'        => 'textarea',
        'label'       => __('Scripts antes de </body>', 'soplos'),
        'description' => __('Código para insertar antes de </body> (ej: chat widgets).', 'soplos'),
        'section'     => 'soplos_general',
    ));

    // --- CUSTOM CSS ---
    $wp_customize->add_setting('soplos_custom_css_code', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));
    $wp_customize->add_control('soplos_custom_css_code', array(
        'type'    => 'textarea',
        'label'   => __('Custom CSS', 'soplos'),
        'description' => __('Añade tu propio código CSS para personalizar estilos.', 'soplos'),
        'section' => 'soplos_general',
    ));
}
add_action('customize_register', 'soplos_customize_register');

/**
 * Sanitize checkbox
 */
function soplos_sanitize_checkbox($input) {
    return (bool) $input;
}

/**
 * Sanitize select
 */
function soplos_sanitize_select($input, $setting) {
    // Cannot use sanitize_key because fonts have spaces (e.g. "Open Sans")
    // We trust that checking against the valid choices keys is sufficient sanitization
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize scripts (allow tags for trusted admins only)
 */
function soplos_sanitize_scripts($input) {
    if (current_user_can('unfiltered_html')) {
        return $input;
    }
    return wp_kses_post($input);
}
