<?php
/**
 * Archive template
 * 
 * @package Soplos
 */

get_header();
?>

<?php soplos_breadcrumbs(); ?>

<div class="container">
    
    <header class="archive-header">
        <?php
        the_archive_title('<h1 class="archive-title">', '</h1>');
        the_archive_description('<div class="archive-description">', '</div>');
        ?>
    </header>

    <?php if (have_posts()) : ?>
        
        <div class="posts-grid">
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="entry-meta">
                            <span class="posted-on">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo get_the_date(); ?>
                            </span>
                            <span class="posted-by">
                                <i class="far fa-user"></i>
                                <?php the_author(); ?>
                            </span>
                        </div>
                    </header>
                    
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <footer class="entry-footer">
                        <a href="<?php the_permalink(); ?>" class="read-more">
                            <?php _e('Read more', 'soplos'); ?> 
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </footer>
                </article>
                
            <?php endwhile; ?>
        </div>
        
        <?php
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'soplos'),
            'next_text' => __('Next', 'soplos') . ' <i class="fas fa-chevron-right"></i>',
        ));
        ?>

    <?php else : ?>
        
        <div class="no-results">
            <h2><?php _e('No posts found', 'soplos'); ?></h2>
            <p><?php _e('No posts in this archive.', 'soplos'); ?></p>
        </div>
        
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>
