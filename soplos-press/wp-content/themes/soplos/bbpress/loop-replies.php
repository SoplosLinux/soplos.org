<?php
/**
 * bbPress Loop Replies Template
 * Custom template for displaying replies list
 * 
 * @package Soplos
 */
?>

<?php do_action('bbp_template_before_replies_loop'); ?>

<ul id="topic-<?php bbp_topic_id(); ?>-replies" class="forums bbp-replies">

    <li class="bbp-body">
        <?php while (bbp_replies()) : bbp_the_reply(); ?>

            <div id="reply-<?php bbp_reply_id(); ?>" class="bbp-reply-post <?php bbp_reply_class(); ?>">
                
                <div class="bbp-reply-author">
                    <?php do_action('bbp_theme_before_reply_author_details'); ?>

                    <div class="bbp-author-avatar">
                        <?php bbp_reply_author_link(array('show_role' => false, 'type' => 'avatar')); ?>
                    </div>

                    <span class="bbp-author-name">
                        <?php bbp_reply_author_link(array('show_role' => false, 'type' => 'name')); ?>
                    </span>

                    <span class="bbp-author-role">
                        <?php bbp_reply_author_role(); ?>
                    </span>

                    <?php do_action('bbp_theme_after_reply_author_details'); ?>
                </div>

                <div class="bbp-reply-content">
                    <div class="bbp-reply-header">
                        <span class="bbp-reply-post-date"><?php bbp_reply_post_date(); ?></span>
                        <a href="<?php bbp_reply_url(); ?>" class="bbp-reply-permalink">#<?php bbp_reply_id(); ?></a>
                    </div>

                    <?php do_action('bbp_theme_before_reply_content'); ?>

                    <div class="bbp-reply-entry">
                        <?php bbp_reply_content(); ?>
                    </div>

                    <?php do_action('bbp_theme_after_reply_content'); ?>

                    <?php do_action('bbp_theme_before_reply_admin_links'); ?>

                    <div class="bbp-reply-admin-links">
                        <?php bbp_reply_admin_links(); ?>
                    </div>

                    <?php do_action('bbp_theme_after_reply_admin_links'); ?>
                </div>

            </div>

        <?php endwhile; ?>
    </li>

</ul>

<?php do_action('bbp_template_after_replies_loop'); ?>
