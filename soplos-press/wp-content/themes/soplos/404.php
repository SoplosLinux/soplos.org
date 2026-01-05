<?php
/**
 * 404 Error Page
 * 
 * @package Soplos
 */

get_header();
?>

<div class="container">
    
    <section class="error-404">
        <h1>404</h1>
        <h2><?php _e('Page Not Found', 'soplos'); ?></h2>
        <p><?php _e('Oops! The page you are looking for seems to have wandered off. Let\'s get you back on track.', 'soplos'); ?></p>
        
        <div class="error-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                <i class="fas fa-home"></i> <?php _e('Go to Homepage', 'soplos'); ?>
            </a>
            
            <?php if (class_exists('bbPress')) : 
                $forums_url = get_post_type_archive_link('forum');
                if ($forums_url) : ?>
            <a href="<?php echo esc_url($forums_url); ?>" class="btn btn-secondary">
                <i class="fas fa-comments"></i> <?php _e('Visit Forums', 'soplos'); ?>
            </a>
            <?php endif; endif; ?>
        </div>
        
        <div class="error-search">
            <h3><?php _e('Or try searching:', 'soplos'); ?></h3>
            <?php get_search_form(); ?>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>
