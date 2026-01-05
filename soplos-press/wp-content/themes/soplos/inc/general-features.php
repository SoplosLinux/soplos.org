<?php
/**
 * Soplos Theme - General Features
 * Implements: Preloader, Reading Progress, Maintenance Mode, Analytics, Custom Scripts
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output custom scripts in <head>
 */
function soplos_head_scripts() {
    // Google Analytics
    $ga_id = get_theme_mod('soplos_google_analytics', '');
    if (!empty($ga_id)) {
        if (strpos($ga_id, 'G-') === 0) {
            // GA4
            ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '<?php echo esc_js($ga_id); ?>');
            </script>
            <?php
        } else {
            // Universal Analytics
            ?>
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
                ga('create', '<?php echo esc_js($ga_id); ?>', 'auto');
                ga('send', 'pageview');
            </script>
            <?php
        }
    }
    
    // Custom head scripts
    $head_scripts = get_theme_mod('soplos_head_scripts', '');
    if (!empty($head_scripts)) {
        echo $head_scripts;
    }
}
add_action('wp_head', 'soplos_head_scripts', 99);

/**
 * Output custom scripts before </body>
 */
function soplos_footer_scripts() {
    $footer_scripts = get_theme_mod('soplos_footer_scripts', '');
    if (!empty($footer_scripts)) {
        echo $footer_scripts;
    }
}
add_action('wp_footer', 'soplos_footer_scripts', 99);

/**
 * Preloader HTML
 */
function soplos_preloader() {
    if (!get_theme_mod('soplos_enable_preloader', false)) {
        return;
    }
    
    $type = get_theme_mod('soplos_preloader_type', 'spinner');
    $preloader_image = get_theme_mod('soplos_preloader_image', '');
    ?>
    <div id="soplos-preloader" class="preloader preloader-<?php echo esc_attr($type); ?>">
        <?php if ($type === 'spinner') : ?>
            <div class="preloader-spinner"></div>
        <?php elseif ($type === 'logo') : ?>
            <div class="preloader-logo">
                <?php if (!empty($preloader_image)) : ?>
                    <img src="<?php echo esc_url($preloader_image); ?>" alt="<?php esc_attr_e('Cargando...', 'soplos'); ?>">
                <?php elseif (has_custom_logo()) : ?>
                    <?php 
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo_img = wp_get_attachment_image_src($custom_logo_id, 'medium');
                    if ($logo_img) : ?>
                        <img src="<?php echo esc_url($logo_img[0]); ?>" alt="<?php bloginfo('name'); ?>">
                    <?php endif; ?>
                <?php else : ?>
                    <span><?php bloginfo('name'); ?></span>
                <?php endif; ?>
            </div>
        <?php elseif ($type === 'bar') : ?>
            <div class="preloader-bar"></div>
        <?php endif; ?>
    </div>
    <?php
}
add_action('wp_body_open', 'soplos_preloader');

/**
 * Reading progress bar
 */
function soplos_reading_progress() {
    if (!get_theme_mod('soplos_reading_progress', false)) {
        return;
    }
    
    // Only on single posts
    if (!is_single()) {
        return;
    }
    ?>
    <div id="reading-progress" class="reading-progress-bar"></div>
    <?php
}
add_action('wp_body_open', 'soplos_reading_progress');

/**
 * Maintenance mode
 */
function soplos_maintenance_mode() {
    // Check if maintenance mode is enabled
    if (!get_theme_mod('soplos_maintenance_mode', false)) {
        return;
    }
    
    // Allow logged in users who can edit (admins, editors)
    if (is_user_logged_in() && current_user_can('edit_posts')) {
        return;
    }
    
    // Don't block login page, admin, or customizer preview
    if (is_admin()) {
        return;
    }
    
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (strpos($request_uri, 'wp-login') !== false || 
        strpos($request_uri, 'wp-admin') !== false ||
        strpos($request_uri, 'customize_changeset') !== false) {
        return;
    }
    
    $title_text = get_theme_mod('soplos_maintenance_title', __('Sitio en Mantenimiento', 'soplos'));
    $message = get_theme_mod('soplos_maintenance_message', __('Estamos realizando mejoras. Volvemos pronto.', 'soplos'));
    $maintenance_logo = get_theme_mod('soplos_maintenance_logo', '');
    $maintenance_bg = get_theme_mod('soplos_maintenance_bg', '');
    $site_name = get_bloginfo('name');
    $lang = get_bloginfo('language');
    
    // Background styles
    $bg_style = '';
    if (!empty($maintenance_bg)) {
        $bg_style = 'background: url(' . esc_url($maintenance_bg) . ') center/cover no-repeat fixed !important;';
    }
    
    // Styled maintenance page - full standalone HTML
    ?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($site_name); ?> - <?php echo esc_html($title_text); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #252527;
            background: linear-gradient(135deg, #252527 0%, #1a1a1c 100%);
            <?php echo $bg_style; ?>
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c0c0c0;
        }
        .maintenance-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .maintenance-container {
            text-align: center;
            padding: 50px 40px;
            max-width: 550px;
            background: rgba(37, 37, 39, 0.95);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }
        .maintenance-icon {
            font-size: 4rem;
            margin-bottom: 25px;
            filter: drop-shadow(0 4px 8px rgba(255, 125, 46, 0.3));
        }
        .maintenance-logo {
            max-height: 80px;
            max-width: 250px;
            margin-bottom: 30px;
        }
        h1 {
            color: #ff7d2e;
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: 700;
        }
        p {
            font-size: 1.15rem;
            line-height: 1.7;
            margin-bottom: 30px;
            color: #e0e0e0;
        }
        .site-name {
            font-size: 0.9rem;
            color: #888;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <div class="maintenance-overlay">
        <div class="maintenance-container">
            <?php 
            // Logo priority: custom maintenance logo > site logo > emoji
            if (!empty($maintenance_logo)) : ?>
                <img src="<?php echo esc_url($maintenance_logo); ?>" alt="<?php echo esc_attr($site_name); ?>" class="maintenance-logo">
            <?php elseif (has_custom_logo()) : 
                $custom_logo_id = get_theme_mod('custom_logo');
                $logo_img = wp_get_attachment_image_src($custom_logo_id, 'medium');
                if ($logo_img) : ?>
                    <img src="<?php echo esc_url($logo_img[0]); ?>" alt="<?php echo esc_attr($site_name); ?>" class="maintenance-logo">
                <?php endif;
            else : ?>
                <div class="maintenance-icon">🔧</div>
            <?php endif; ?>
            <h1><?php echo esc_html($title_text); ?></h1>
            <p><?php echo esc_html($message); ?></p>
            <div class="site-name"><?php echo esc_html($site_name); ?></div>
        </div>
    </div>
</body>
</html>
    <?php
    status_header(503);
    header('Retry-After: 3600');
    exit;
}
add_action('template_redirect', 'soplos_maintenance_mode');

/**
 * Enqueue preloader and reading progress scripts
 */
function soplos_general_features_scripts() {
    $preloader_enabled = get_theme_mod('soplos_enable_preloader', false);
    $reading_progress = get_theme_mod('soplos_reading_progress', false);
    
    if ($preloader_enabled || $reading_progress) {
        wp_add_inline_script('soplos-main', soplos_get_inline_js());
    }
}
add_action('wp_enqueue_scripts', 'soplos_general_features_scripts');

/**
 * Get inline JS for preloader and reading progress
 */
function soplos_get_inline_js() {
    $js = '';
    
    // Preloader JS
    if (get_theme_mod('soplos_enable_preloader', false)) {
        $js .= "
        window.addEventListener('load', function() {
            var preloader = document.getElementById('soplos-preloader');
            if (preloader) {
                preloader.classList.add('loaded');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }
        });
        ";
    }
    
    // Reading progress JS
    if (get_theme_mod('soplos_reading_progress', false)) {
        $js .= "
        document.addEventListener('DOMContentLoaded', function() {
            var progressBar = document.getElementById('reading-progress');
            if (progressBar) {
                window.addEventListener('scroll', function() {
                    var scrollTop = window.scrollY;
                    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    var progress = (scrollTop / docHeight) * 100;
                    progressBar.style.width = progress + '%';
                });
            }
        });
        ";
    }
    
    return $js;
}
