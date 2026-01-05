<?php
/**
 * Soplos Theme - Setup
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function soplos_theme_setup() {
    // Load translations
    load_theme_textdomain('soplos', get_template_directory() . '/languages');
    // Add support for automatic feed links
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    
    // Custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 50,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add support for full and wide align images
    add_theme_support('align-wide');
    
    // HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary'   => __('Primary Menu', 'soplos'),
        'footer'    => __('Footer Menu', 'soplos'),
    ));
    
    // Add support for bbPress
    add_theme_support('bbpress');
}
add_action('after_setup_theme', 'soplos_theme_setup');
