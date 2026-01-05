<?php
/**
 * Template part for displaying single post content
 * 
 * @package Soplos
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
    
    <header class="entry-header">
        <?php if (get_theme_mod('soplos_show_post_cats', true)) : ?>
            <div class="post-categories">
                <?php the_category(' '); ?>
            </div>
        <?php endif; ?>
        
        <h1 class="entry-title"><?php the_title(); ?></h1>
        
        <div class="entry-meta">
            <?php if (get_theme_mod('soplos_show_post_date', true)) : ?>
                <span class="post-date">
                    <i class="far fa-calendar-alt"></i>
                    <?php echo get_the_date(); ?>
                </span>
            <?php endif; ?>
            
            <?php if (get_theme_mod('soplos_show_post_author', true)) : ?>
                <span class="post-author">
                    <i class="far fa-user"></i>
                    <?php the_author(); ?>
                </span>
            <?php endif; ?>
            
            <span class="post-comments">
                <i class="far fa-comments"></i>
                <?php comments_number(__('No Comments', 'soplos'), __('1 Comment', 'soplos'), __('% Comments', 'soplos')); ?>
            </span>
        </div>
    </header>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>
    
    <div class="entry-content">
        <?php 
        the_content();
        
        wp_link_pages(array(
            'before' => '<div class="page-links">' . __('Pages:', 'soplos'),
            'after'  => '</div>',
        ));
        ?>
    </div>
    
    <footer class="entry-footer">
        <?php
        $tags = get_the_tags();
        if ($tags) : ?>
            <div class="post-tags">
                <i class="fas fa-tags"></i>
                <?php the_tags('', ' '); ?>
            </div>
        <?php endif; ?>
    </footer>
    
</article>
