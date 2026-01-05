<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<?php
// Body Classes Logic
$body_extra_classes = [];

// Transparent Header
if (get_theme_mod('soplos_transparent_header_home', false) && (is_front_page() || is_home())) {
    $body_extra_classes[] = 'home-header-transparent';
}

// Top Bar presence (for margin calculations)
if (get_theme_mod('soplos_enable_topbar', false)) {
    $body_extra_classes[] = 'has-top-bar';
}

// Header Layout (for body-level styling if needed)
$header_layout = get_theme_mod('soplos_header_layout', 'default');
$body_extra_classes[] = 'body-header-layout-' . $header_layout;

// Mobile Menu Style
$mobile_menu_style = get_theme_mod('soplos_mobile_menu_style', 'default');
$body_extra_classes[] = 'mobile-menu-style-' . $mobile_menu_style;
?>
<body <?php body_class($body_extra_classes); ?>>
<?php wp_body_open(); ?>

<?php
// Header Classes & Data
$header_classes = 'site-header';
if (get_theme_mod('soplos_sticky_header', true)) {
    $header_classes .= ' sticky-enabled';
}
// Still keep header layout class on header for local styling inheritance
$header_classes .= ' header-layout-' . $header_layout;
?>

<header class="<?php echo esc_attr($header_classes); ?>" data-hide-scroll="<?php echo get_theme_mod('soplos_hide_header_scroll', false) ? 'true' : 'false'; ?>">
    <div class="container">
        <div class="site-logo">
            <?php 
            // Get custom logo URL or default to home
            $logo_url = get_theme_mod('soplos_logo_url', '');
            $logo_link = !empty($logo_url) ? $logo_url : home_url('/');
            
            // Get logo variations
            $retina_logo = get_theme_mod('soplos_retina_logo', '');
            $mobile_logo = get_theme_mod('soplos_mobile_logo', '');
            $show_title = get_theme_mod('soplos_show_site_title', true);
            $show_tagline = get_theme_mod('soplos_show_site_tagline', true);
            ?>
            <?php if (has_custom_logo()) : ?>
                <?php 
                // Get custom logo
                $custom_logo_id = get_theme_mod('custom_logo');
                $logo_img_src = wp_get_attachment_image_src($custom_logo_id, 'full');
                ?>
                <a href="<?php echo esc_url($logo_link); ?>" class="custom-logo-link" rel="home">
                    <img src="<?php echo esc_url($logo_img_src[0]); ?>" 
                         alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                         class="custom-logo"
                         <?php if (!empty($retina_logo)) : ?>
                         srcset="<?php echo esc_url($logo_img_src[0]); ?> 1x, <?php echo esc_url($retina_logo); ?> 2x"
                         <?php endif; ?>>
                    <?php if (!empty($mobile_logo)) : ?>
                    <img src="<?php echo esc_url($mobile_logo); ?>" 
                         alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                         class="mobile-logo">
                    <?php endif; ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url($logo_link); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo.png" 
                         alt="<?php bloginfo('name'); ?>" 
                         class="custom-logo"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <span class="site-title" style="display:none;"><?php bloginfo('name'); ?></span>
                </a>
            <?php endif; ?>
            
            <?php if ($show_title || $show_tagline) : ?>
            <div class="site-branding-text">
                <?php if ($show_title) : ?>
                <span class="site-title-text"><?php bloginfo('name'); ?></span>
                <?php endif; ?>
                <?php if ($show_tagline && get_bloginfo('description')) : ?>
                <span class="site-tagline"><?php bloginfo('description'); ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="header-right">
            <?php 
            $show_search = get_theme_mod('soplos_header_show_search', true);
            $search_pos = get_theme_mod('soplos_header_search_position', 'after_menu');
            ?>

            <?php if ($show_search && $search_pos === 'before_menu') : ?>
            <div class="header-search-wrapper">
                <button class="header-search-toggle" aria-label="<?php esc_attr_e('Search', 'soplos'); ?>">
                    <i class="fas fa-search"></i>
                </button>
                <div class="header-search-dropdown">
                    <?php get_search_form(); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Toggle Menu', 'soplos'); ?>" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'soplos'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'soplos_fallback_menu',
                    'depth'          => 2,
                ));
                ?>
                <?php if ($show_search) : ?>
                <div class="mobile-search-form">
                    <?php get_search_form(); ?>
                </div>
                <?php endif; ?>
            </nav>

            <?php if ($show_search && $search_pos === 'after_menu') : ?>
            <div class="header-search-wrapper">
                <button class="header-search-toggle" aria-label="<?php esc_attr_e('Search', 'soplos'); ?>">
                    <i class="fas fa-search"></i>
                </button>
                <div class="header-search-dropdown">
                    <?php get_search_form(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<?php 
// Top Bar Implementation (Below Header)
if (get_theme_mod('soplos_enable_topbar', false)) : 
    $social_pos = get_theme_mod('soplos_topbar_social_position', 'right');
    $icon_size = get_theme_mod('soplos_topbar_icon_size', 14);
?>
<div class="site-topbar social-pos-<?php echo esc_attr($social_pos); ?>">
    <div class="container">
        <div class="topbar-content">
            <?php echo wp_kses_post(get_theme_mod('soplos_topbar_text', '')); ?>
        </div>
        
        <div class="topbar-social" style="font-size: <?php echo intval($icon_size); ?>px;">
            <?php
            // Retrieve Sort Order
            $default_order = 'facebook,twitter,instagram,youtube,linkedin,tiktok,twitch,github,discord,telegram,paypal,mastodon,rss';
            $order_list = explode(',', get_theme_mod('soplos_social_order', $default_order));

            // Define Icons Map
            $icons_map = array(
                'facebook'  => 'fab fa-facebook',
                'twitter'   => 'fab fa-twitter',
                'instagram' => 'fab fa-instagram',
                'youtube'   => 'fab fa-youtube',
                'linkedin'  => 'fab fa-linkedin',
                'tiktok'    => 'fab fa-tiktok',
                'twitch'    => 'fab fa-twitch',
                'github'    => 'fab fa-github',
                'discord'   => 'fab fa-discord',
                'telegram'  => 'fab fa-telegram',
                'paypal'    => 'fab fa-paypal',
                'mastodon'  => 'fab fa-mastodon',
                'rss'       => 'fas fa-rss',
            );

            foreach ($order_list as $network) {
                // Sanitize network key
                $network = trim($network);
                if (!array_key_exists($network, $icons_map)) continue;

                $url = get_theme_mod("soplos_{$network}_url", '');
                if (!empty($url)) {
                    echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr(ucfirst($network)) . '">';
                    echo '<i class="' . esc_attr($icons_map[$network]) . '"></i>';
                    echo '</a>';
                }
            }
            ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
// Fallback menu moved to `functions.php` as `soplos_fallback_menu()` for better organization.
?>

<main id="main" class="site-main">
