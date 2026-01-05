<?php
/**
 * Page template
 * 
 * @package Soplos
 */

get_header();
?>

<?php soplos_breadcrumbs(); ?>

<div class="container">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="page-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
            
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="page-featured-image">
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
            
        </article>
        
        <?php
        // If comments are open or we have at least one comment
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
        
    <?php endwhile; ?>
    
</div>

<?php get_footer(); ?>
