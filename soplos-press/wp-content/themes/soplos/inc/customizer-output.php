<?php
/**
 * Soplos Theme - Customizer CSS Output
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output customizer CSS
 */
function soplos_customizer_css() {
    // Colors
    $primary       = esc_attr(get_theme_mod('soplos_primary_color', '#ff7d2e'));
    $secondary     = esc_attr(get_theme_mod('soplos_secondary_color', '#fec431'));
    $accent        = esc_attr(get_theme_mod('soplos_accent_color', '#ef5a33'));
    $bg_color      = esc_attr(get_theme_mod('soplos_bg_color', '#252527'));
    $text_color    = esc_attr(get_theme_mod('soplos_text_color', '#c0c0c0'));
    $card_bg       = esc_attr(get_theme_mod('soplos_card_bg', '#303032'));
    $heading_color = esc_attr(get_theme_mod('soplos_heading_color', '#ffffff'));
    $link_color    = esc_attr(get_theme_mod('soplos_link_color', '#ff7d2e'));
    $link_hover    = esc_attr(get_theme_mod('soplos_link_hover_color', '#fec431'));
    $header_bg     = esc_attr(get_theme_mod('soplos_header_bg', '#252527'));
    $footer_bg     = esc_attr(get_theme_mod('soplos_footer_bg', '#1a1a1c'));
    $border_color  = esc_attr(get_theme_mod('soplos_border_color', 'rgba(255,255,255,0.1)'));

    // Typography - Body Base
    $body_font = get_theme_mod('soplos_body_font_family', 'system-ui');
    $body_weight = get_theme_mod('soplos_body_font_weight', '400');
    $body_size = absint(get_theme_mod('soplos_body_font_size', '16'));
    
    // Typography - Heading Base
    $heading_base = get_theme_mod('soplos_heading_font_family', 'system-ui');

    // Helper to get font stack
    $get_font_stack = function($font) {
        if ($font === 'system-ui' || $font === 'inherit') return 'inherit'; 
        return '"' . esc_attr($font) . '", sans-serif';
    };

    // Global Variables
    $css = ":root {\n";
    // ... Colors ...
    $css .= "  --primary-color: {$primary};\n";
    $css .= "  --secondary-color: {$secondary};\n";
    $css .= "  --accent-color: {$accent};\n";
    $css .= "  --dark-color: {$bg_color};\n";
    $css .= "  --text-color: {$text_color};\n";
    $css .= "  --bg-card: {$card_bg};\n";
    $css .= "  --light-text: {$heading_color};\n";
    $css .= "  --link-color: {$link_color};\n";
    $css .= "  --link-hover: {$link_hover};\n";
    $css .= "  --header-bg: {$header_bg};\n";
    $css .= "  --footer-bg: {$footer_bg};\n";
    $css .= "  --border-color: {$border_color};\n";
    
    // Base Fonts Variables
    $css .= "  --font-body: " . ($body_font === 'system-ui' ? 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif' : '"'.esc_attr($body_font).'", sans-serif') . ";\n";
    $css .= "  --font-heading-base: " . ($heading_base === 'system-ui' ? 'system-ui, sans-serif' : '"'.esc_attr($heading_base).'", sans-serif') . ";\n";
    
    $css .= "  --base-font-size: {$body_size}px;\n";
    $css .= "}\n\n";

    // Apply Body Styles
    $css .= "body { font-family: var(--font-body); font-weight: ".esc_attr($body_weight)."; font-size: var(--base-font-size); background-color: var(--dark-color); color: var(--text-color); }\n";
    
    // Apply Heading Base Color
    $css .= "h1, h2, h3, h4, h5, h6 { color: var(--light-text); font-family: var(--font-heading-base); }\n"; 

    // Granular Headings Loop
    $tags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
    foreach ($tags as $tag) {
        $family = get_theme_mod("soplos_{$tag}_font_family", 'inherit');
        $weight = get_theme_mod("soplos_{$tag}_font_weight", 'inherit');
        $size   = get_theme_mod("soplos_{$tag}_font_size", '');

        $rule = "";
        
        // Family overrides base?
        if ($family !== 'inherit') {
            $rule .= "  font-family: " . ($family === 'system-ui' ? 'system-ui, sans-serif' : '"'.esc_attr($family).'", sans-serif') . ";\n";
        }
        
        // Weight overrides?
        if ($weight !== 'inherit') {
            $rule .= "  font-weight: " . esc_attr($weight) . ";\n";
        }

        // Size overrides?
        if (!empty($size)) {
            $rule .= "  font-size: " . absint($size) . "px;\n";
        }

        if (!empty($rule)) {
            $css .= "{$tag} {\n{$rule}}\n";
        }
    }
    $css .= "a { color: var(--link-color); }\n";
    $css .= "a:hover { color: var(--link-hover); }\n";
    $css .= ".site-header { background-color: var(--header-bg); }\n";
    $css .= ".site-footer { background-color: var(--footer-bg); }\n";
    $css .= ".card, .post-card, .widget { background-color: var(--bg-card); }\n";

    if (!empty($custom_css)) {
        $css .= "\n/* Custom CSS from Customizer */\n" . $custom_css . "\n";
    }

    wp_add_inline_style('soplos-style', $css);
}
add_action('wp_enqueue_scripts', 'soplos_customizer_css', 20);

