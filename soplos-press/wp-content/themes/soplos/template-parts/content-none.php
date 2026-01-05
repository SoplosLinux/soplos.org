<?php
/**
 * Template part for displaying a message when no posts are found
 * 
 * @package Soplos
 */
?>

<section class="no-results not-found">
    
    <header class="page-header">
        <h1 class="page-title"><?php _e('Nothing Found', 'soplos'); ?></h1>
    </header>
    
    <div class="page-content">
        <?php if (is_search()) : ?>
            <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'soplos'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'soplos'); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
    
</section>
