<?php
/**
 * Soplos Theme - Template Functions
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get social links
 */
function soplos_get_social_links() {
    return array(
        'github'   => get_theme_mod('soplos_github_url', 'https://github.com/SoplosLinux'),
        'twitter'  => get_theme_mod('soplos_twitter_url', 'https://twitter.com/soploslinux'),
        'paypal'   => get_theme_mod('soplos_paypal_url', 'https://paypal.me/isubdes'),
        'mastodon' => get_theme_mod('soplos_mastodon_url', ''),
        'telegram' => get_theme_mod('soplos_telegram_url', ''),
        'discord'  => get_theme_mod('soplos_discord_url', ''),
        'youtube'  => get_theme_mod('soplos_youtube_url', ''),
        'rss'      => get_theme_mod('soplos_rss_url', ''),
    );
}

/**
 * Fallback menu if no menu is assigned
 */
if ( ! function_exists( 'soplos_fallback_menu' ) ) {
    function soplos_fallback_menu() {
        echo '<ul class="primary-menu">';
        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'soplos' ) . '</a></li>';

        // Add blog link
        $blog_page = get_option( 'page_for_posts' );
        if ( $blog_page ) {
            echo '<li><a href="' . esc_url( get_permalink( $blog_page ) ) . '">' . esc_html__( 'Blog', 'soplos' ) . '</a></li>';
        }

        // Add bbPress forums link if active
        if ( class_exists( 'bbPress' ) ) {
            $forums_url = get_post_type_archive_link( 'forum' );
            if ( $forums_url ) {
                echo '<li><a href="' . esc_url( $forums_url ) . '">' . esc_html__( 'Forums', 'soplos' ) . '</a></li>';
            }
        }

        echo '</ul>';
    }
}

/**
 * Display breadcrumbs
 */
function soplos_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    echo '<div class="breadcrumb-container">';
    echo '<div class="container">';
    echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'soplos' ) . '">';
    echo '<a href="' . esc_url( home_url('/') ) . '">' . esc_html__( 'Home', 'soplos' ) . '</a>';
    echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>';
    
    if (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
            echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>';
        }
        echo '<span class="breadcrumb-current">' . esc_html( get_the_title() ) . '</span>';
    } elseif (is_page()) {
        echo '<span class="breadcrumb-current">' . esc_html( get_the_title() ) . '</span>';
    } elseif (is_category()) {
        echo '<span class="breadcrumb-current">' . esc_html( single_cat_title('', false) ) . '</span>';
    } elseif (is_search()) {
        echo '<span class="breadcrumb-current">' . esc_html__( 'Search Results', 'soplos' ) . '</span>';
    } elseif (is_archive()) {
        echo '<span class="breadcrumb-current">' . esc_html( get_the_archive_title() ) . '</span>';
    } elseif (function_exists('is_bbpress') && is_bbpress()) {
        echo '<span class="breadcrumb-current">' . esc_html__( 'Forums', 'soplos' ) . '</span>';
    }
    
    echo '</nav>';
    echo '</div>';
    echo '</div>';
}

/**
 * Custom excerpt length
 */
function soplos_excerpt_length($length) {
    return get_theme_mod('soplos_excerpt_length_num', 25);
}
add_filter('excerpt_length', 'soplos_excerpt_length');

/**
 * Custom excerpt more
 */
function soplos_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'soplos_excerpt_more');
