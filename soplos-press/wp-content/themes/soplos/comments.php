<?php
/**
 * Comments template
 * 
 * @package Soplos
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                /* translators: 1: comment count, 2: title */
                _nx(
                    '%1$s Comment on &ldquo;%2$s&rdquo;',
                    '%1$s Comments on &ldquo;%2$s&rdquo;',
                    $comment_count,
                    'comments title',
                    'soplos'
                ),
                number_format_i18n($comment_count),
                get_the_title()
            );
            ?>
        </h2>
        
        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
            ));
            ?>
        </ol>
        
        <?php
        the_comments_navigation();
        ?>
        
    <?php endif; ?>
    
    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments"><?php _e('Comments are closed.', 'soplos'); ?></p>
    <?php endif; ?>
    
    <?php
    comment_form(array(
        'title_reply'          => __('Leave a Comment', 'soplos'),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.', 'soplos') . '</p>',
        'label_submit'         => __('Post Comment', 'soplos'),
        'class_submit'         => 'btn btn-primary',
    ));
    ?>
    
</div>
