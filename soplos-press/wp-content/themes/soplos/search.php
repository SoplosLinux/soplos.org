<?php
/**
 * Search results template
 * 
 * @package Soplos
 */

get_header();
?>

<?php soplos_breadcrumbs(); ?>

<div class="container">
    
    <header class="search-header">
        <h1 class="search-title">
            <?php
            printf(
                /* translators: %s: search query */
                esc_html__('Search Results for: %s', 'soplos'),
                '<span>' . get_search_query() . '</span>'
            );
            ?>
        </h1>
        
        <div class="search-form-container">
            <?php get_search_form(); ?>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        
        <p class="search-count">
            <?php
            global $wp_query;
            printf(
                /* translators: %d: number of results */
                _n('%d result found', '%d results found', $wp_query->found_posts, 'soplos'),
                $wp_query->found_posts
            );
            ?>
        </p>
        
        <div class="posts-grid">
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <header class="entry-header">
                        <span class="post-type-label">
                            <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                        </span>
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                    </header>
                    
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <footer class="entry-footer">
                        <a href="<?php the_permalink(); ?>" class="read-more">
                            <?php _e('View', 'soplos'); ?> 
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
            <h2><?php _e('Nothing found', 'soplos'); ?></h2>
            <p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'soplos'); ?></p>
            
            <div class="search-suggestions">
                <h3><?php _e('Suggestions:', 'soplos'); ?></h3>
                <ul>
                    <li><?php _e('Check the spelling of your search terms.', 'soplos'); ?></li>
                    <li><?php _e('Try using fewer or different keywords.', 'soplos'); ?></li>
                    <li><?php _e('Make sure all words are spelled correctly.', 'soplos'); ?></li>
                </ul>
            </div>
        </div>
        
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>
