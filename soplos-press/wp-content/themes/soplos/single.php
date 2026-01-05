<?php
/**
 * Single post template
 * 
 * @package Soplos
 */

get_header();
?>

<?php soplos_breadcrumbs(); ?>

<div class="container">
    <div class="single-post-content">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
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
                                <?php the_author_posts_link(); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (has_category() && get_theme_mod('soplos_show_post_cats', true)) : ?>
                        <span class="posted-in">
                            <i class="far fa-folder"></i>
                            <?php the_category(', '); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (comments_open()) : ?>
                        <span class="comments-count">
                            <i class="far fa-comments"></i>
                            <?php comments_number(__('No comments', 'soplos'), __('1 comment', 'soplos'), __('% comments', 'soplos')); ?>
                        </span>
                        <?php endif; ?>
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
                    <?php if (has_tag()) : ?>
                    <div class="post-tags">
                        <i class="fas fa-tags"></i>
                        <?php the_tags('', ', '); ?>
                    </div>
                    <?php endif; ?>
                </footer>
                
            </article>
            
            <!-- Post Navigation -->
            <nav class="post-navigation">
                <div class="nav-links">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ($prev_post) : ?>
                    <div class="nav-previous">
                        <a href="<?php echo get_permalink($prev_post); ?>">
                            <span class="nav-label"><i class="fas fa-chevron-left"></i> <?php _e('Previous', 'soplos'); ?></span>
                            <span class="nav-title"><?php echo get_the_title($prev_post); ?></span>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($next_post) : ?>
                    <div class="nav-next">
                        <a href="<?php echo get_permalink($next_post); ?>">
                            <span class="nav-label"><?php _e('Next', 'soplos'); ?> <i class="fas fa-chevron-right"></i></span>
                            <span class="nav-title"><?php echo get_the_title($next_post); ?></span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </nav>
            
            <!-- Comments -->
            <?php
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
            
        <?php endwhile; ?>
        
    </div>
</div>

<?php get_footer(); ?>
