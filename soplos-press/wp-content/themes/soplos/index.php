<?php
/**
 * Main template file
 * 
 * @package Soplos
 */

get_header();

// Get layout setting
$layout = get_theme_mod('soplos_blog_layout', 'grid');
?>

<?php soplos_breadcrumbs(); ?>

<div class="container">
    <div class="posts-wrapper">
        
        <?php if (is_home() && !is_front_page()) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            
            <div class="posts-grid <?php echo esc_attr('layout-' . $layout); ?>">
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
                                <?php if (get_theme_mod('soplos_show_post_date', true)) : ?>
                                    <span class="posted-on">
                                        <i class="far fa-calendar-alt"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (get_theme_mod('soplos_show_post_author', true)) : ?>
                                    <span class="posted-by">
                                        <i class="far fa-user"></i>
                                        <?php the_author(); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (get_theme_mod('soplos_show_post_cats', true) && has_category()) : ?>
                                <span class="posted-in">
                                    <i class="far fa-folder"></i>
                                    <?php the_category(', '); ?>
                                </span>
                                <?php endif; ?>
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
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'soplos'),
                'next_text' => __('Next', 'soplos') . ' <i class="fas fa-chevron-right"></i>',
            ));
            ?>

        <?php else : ?>
            
            <div class="no-results">
                <h2><?php _e('No posts found', 'soplos'); ?></h2>
                <p><?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'soplos'); ?></p>
                <?php get_search_form(); ?>
            </div>
            
        <?php endif; ?>
        
    </div>
</div>

<?php get_footer(); ?>
