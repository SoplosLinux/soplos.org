<?php
/**
 * Template Name: Forums Page
 * Template for displaying bbPress forums on a static page
 * 
 * @package Soplos
 */

get_header();

// Get forum settings from customizer
$forum_title = get_theme_mod('soplos_forum_title', __('Community Forums', 'soplos'));
$forum_desc = get_theme_mod('soplos_forum_description', __('Join our community. Ask questions, share knowledge, and connect with other Soplos Linux users.', 'soplos'));
?>

<!-- Forum Hero Section -->
<div class="forum-hero">
    <div class="container">
        <h1><?php echo esc_html($forum_title); ?></h1>
        <p><?php echo esc_html($forum_desc); ?></p>
    </div>
</div>

<?php soplos_breadcrumbs(); ?>

<div class="container">
    <div class="bbpress-wrapper no-sidebar">
        <div class="bbpress-content">
            <?php
            while (have_posts()) : the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
