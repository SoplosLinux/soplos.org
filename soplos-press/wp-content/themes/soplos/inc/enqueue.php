<?php
/**
 * Soplos Theme - Enqueue Scripts & Styles
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles
 */
function soplos_enqueue_scripts() {
    // Main stylesheet
    wp_enqueue_style(
        'soplos-style',
        get_stylesheet_uri(),
        array(),
        SOPLOS_VERSION
    );
    
    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
        array(),
        '6.0.0'
    );
    
    // Navigation script
    wp_enqueue_script(
        'soplos-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        array(),
        SOPLOS_VERSION,
        true
    );
    
    // Main script
    wp_enqueue_script(
        'soplos-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        SOPLOS_VERSION,
        true
    );
    
    // bbPress specific styles
    if (class_exists('bbPress')) {
        wp_enqueue_style(
            'soplos-bbpress',
            get_template_directory_uri() . '/assets/css/bbpress.css',
            array('soplos-style'),
            SOPLOS_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'soplos_enqueue_scripts');

/**
 * Enqueue Google Fonts (Smart Loader)
 */
function soplos_enqueue_google_fonts() {
    $fonts_request = array(); // Format: 'Family' => array(weight, weight)

    // Helper to add font to request
    $add_font = function(&$request, $family, $weight) {
        if ($family === 'system-ui' || $family === 'inherit' || empty($family)) return;
        
        if (!isset($request[$family])) {
            $request[$family] = array();
        }
        if ($weight === 'inherit' || empty($weight)) {
            // If inherit weight, usually we want standard weights available
             $request[$family][] = '400';
             $request[$family][] = '700';
        } else {
             $request[$family][] = $weight;
        }
    };

    // 1. Body Font
    $body_font = get_theme_mod('soplos_body_font_family', 'system-ui');
    $body_weight = get_theme_mod('soplos_body_font_weight', '400');
    $add_font($fonts_request, $body_font, $body_weight);

    // 2. Headings Base
    $heading_base = get_theme_mod('soplos_heading_font_family', 'system-ui');
    // Ensure base heading carries standard weights
    $add_font($fonts_request, $heading_base, '400');
    $add_font($fonts_request, $heading_base, '700');

    // 3. Granular Headings (H1-H6)
    $tags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
    foreach ($tags as $tag) {
        $h_family = get_theme_mod("soplos_{$tag}_font_family", 'inherit');
        $h_weight = get_theme_mod("soplos_{$tag}_font_weight", 'inherit');

        // Resolve inheritance for Family
        $target_family = ($h_family === 'inherit') ? $heading_base : $h_family;
        
        // Add valid font/weight combo
        $add_font($fonts_request, $target_family, $h_weight);
    }

    if (empty($fonts_request)) {
        return;
    }

    // Build URL Args
    $font_args = array();
    foreach ($fonts_request as $family => $weights) {
        $unique_weights = array_unique($weights);
        sort($unique_weights);
        // Google Fonts expects: Family:wght@400;700
        $font_args[] = 'family=' . str_replace(' ', '+', $family) . ':wght@' . implode(';', $unique_weights);
    }

    // Manual URL construction to avoid add_query_arg encoding issues or duplicate key removal
    $fonts_url = 'https://fonts.googleapis.com/css2?' . implode('&', $font_args) . '&display=swap';

    wp_enqueue_style('soplos-google-fonts', $fonts_url, array(), null);
}
add_action('wp_enqueue_scripts', 'soplos_enqueue_google_fonts');
