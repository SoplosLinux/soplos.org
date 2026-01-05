<?php
/**
 * bbPress Loop Forums Template
 * Custom template for displaying forums list
 * 
 * @package Soplos
 */
?>

<?php do_action('bbp_template_before_forums_loop'); ?>

<ul id="forums-list-<?php bbp_forum_id(); ?>" class="bbp-forums">

    <li class="bbp-header">
        <ul class="forum-titles">
            <li class="bbp-forum-info"><?php esc_html_e('Forum', 'soplos'); ?></li>
            <li class="bbp-forum-topic-count"><?php esc_html_e('Topics', 'soplos'); ?></li>
            <li class="bbp-forum-reply-count"><?php esc_html_e('Posts', 'soplos'); ?></li>
            <li class="bbp-forum-freshness"><?php esc_html_e('Last Post', 'soplos'); ?></li>
        </ul>
    </li>

    <li class="bbp-body">
        <?php while (bbp_forums()) : bbp_the_forum(); ?>

            <ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>
                <li class="bbp-forum-info">
                    <?php if (bbp_is_user_home() && bbp_is_subscriptions()) : ?>
                        <span class="bbp-row-actions">
                            <?php do_action('bbp_theme_before_forum_subscription_action'); ?>
                            <?php bbp_forum_subscription_link(array('before' => '', 'subscribe' => '+', 'unsubscribe' => '-')); ?>
                            <?php do_action('bbp_theme_after_forum_subscription_action'); ?>
                        </span>
                    <?php endif; ?>

                    <?php do_action('bbp_theme_before_forum_title'); ?>

                    <a class="bbp-forum-title" href="<?php bbp_forum_permalink(); ?>">
                        <?php bbp_forum_title(); ?>
                    </a>

                    <?php do_action('bbp_theme_after_forum_title'); ?>

                    <?php do_action('bbp_theme_before_forum_description'); ?>

                    <div class="bbp-forum-content"><?php bbp_forum_content(); ?></div>

                    <?php do_action('bbp_theme_after_forum_description'); ?>

                    <?php do_action('bbp_theme_before_forum_sub_forums'); ?>

                    <?php bbp_list_forums(); ?>

                    <?php do_action('bbp_theme_after_forum_sub_forums'); ?>
                </li>

                <li class="bbp-forum-topic-count"><?php bbp_forum_topic_count(); ?></li>

                <li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li>

                <li class="bbp-forum-freshness">
                    <?php do_action('bbp_theme_before_forum_freshness_link'); ?>

                    <?php bbp_forum_freshness_link(); ?>

                    <?php do_action('bbp_theme_after_forum_freshness_link'); ?>

                    <p class="bbp-topic-meta">
                        <?php do_action('bbp_theme_before_topic_author'); ?>
                        <span class="bbp-topic-freshness-author"><?php bbp_author_link(array('post_id' => bbp_get_forum_last_active_id(), 'size' => 14)); ?></span>
                        <?php do_action('bbp_theme_after_topic_author'); ?>
                    </p>
                </li>
            </ul>

        <?php endwhile; ?>
    </li>

    <li class="bbp-footer">
        <div class="tr">
            <p class="td colspan4">&nbsp;</p>
        </div>
    </li>

</ul>

<?php do_action('bbp_template_after_forums_loop'); ?>
