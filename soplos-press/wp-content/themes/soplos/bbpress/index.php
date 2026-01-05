<?php
/**
 * bbPress Template - Forum Index
 * Custom template for displaying the main forums list
 * 
 * @package Soplos
 */

get_header();

// Get forum settings from customizer
$show_sidebar = get_theme_mod('soplos_forum_sidebar', true);
$forum_title = get_theme_mod('soplos_forum_title', __('Community Forums', 'soplos'));
$forum_desc = get_theme_mod('soplos_forum_description', __('Join our community. Ask questions, share knowledge, and connect with other Soplos Linux users.', 'soplos'));

// Check if sidebar has widgets
$has_sidebar_widgets = is_active_sidebar('sidebar-forum');
$show_sidebar_final = $show_sidebar && $has_sidebar_widgets;
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
    <div class="bbpress-wrapper<?php echo !$show_sidebar_final ? ' no-sidebar' : ''; ?>">
        
        <div class="bbpress-content">
            <?php
            // Forum content
            while (have_posts()) : the_post();
                the_content();
            endwhile;
            ?>
        </div>
        
        <?php if ($show_sidebar_final) : ?>
        <aside class="forum-sidebar sidebar">
            <?php dynamic_sidebar('sidebar-forum'); ?>
        </aside>
        <?php endif; ?>
        
    </div>
</div>

<?php get_footer(); ?>
