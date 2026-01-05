/**
 * Soplos Theme - Customizer Live Preview
 * 
 * @package Soplos
 */

(function($) {
    'use strict';

    // Primary Color
    wp.customize('soplos_primary_color', function(value) {
        value.bind(function(newVal) {
            document.documentElement.style.setProperty('--primary-color', newVal);
        });
    });

    // Secondary Color
    wp.customize('soplos_secondary_color', function(value) {
        value.bind(function(newVal) {
            document.documentElement.style.setProperty('--secondary-color', newVal);
        });
    });

    // Accent Color
    wp.customize('soplos_accent_color', function(value) {
        value.bind(function(newVal) {
            document.documentElement.style.setProperty('--accent-color', newVal);
        });
    });

})(jQuery);
