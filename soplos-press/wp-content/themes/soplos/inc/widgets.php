<?php
/**
 * Soplos Theme - Widget Areas
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register widget areas
 */
function soplos_widgets_init() {
    register_sidebar(array(
        'name'          => __('Main Sidebar', 'soplos'),
        'id'            => 'sidebar-main',
        'description'   => __('Add widgets here to appear in your sidebar.', 'soplos'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Forum Sidebar', 'soplos'),
        'id'            => 'sidebar-forum',
        'description'   => __('Widgets for the forum pages.', 'soplos'),
        'before_widget' => '<div id="%1$s" class="widget widget-forum %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Register Dynamic Footer Columns (1-4)
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer Column %d', 'soplos'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Widgets for footer column %d.', 'soplos'), $i),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ));
    }
}
add_action('widgets_init', 'soplos_widgets_init');
