<?php
/**
 * Template part for displaying posts in a loop
 * 
 * @package Soplos
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="post-content">
        <header class="entry-header">
            <?php if (get_theme_mod('soplos_show_post_cats', true)) : ?>
                <div class="post-categories">
                    <?php the_category(' '); ?>
                </div>
            <?php endif; ?>
            
            <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            
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
            </div>
        </header>
        
        <div class="entry-excerpt">
            <?php the_excerpt(); ?>
        </div>
        
        <a href="<?php the_permalink(); ?>" class="read-more">
            <?php _e('Read More', 'soplos'); ?>
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    
</article>
