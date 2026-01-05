<?php
/**
 * Soplos Theme - Functions and Definitions
 * 
 * @package Soplos
 * @version 1.0.1
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme Version
define('SOPLOS_VERSION', '1.0.1');

// Theme includes
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/widgets.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/customizer-output.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/general-features.php';

// bbPress support (conditional load)
if (class_exists('bbPress') || is_admin()) {
    require get_template_directory() . '/inc/bbpress.php';
}
