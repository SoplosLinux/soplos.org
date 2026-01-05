<?php
/**
 * bbPress Loop Topics Template
 * Custom template for displaying topics list
 * 
 * @package Soplos
 */
?>

<?php do_action('bbp_template_before_topics_loop'); ?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics">

    <li class="bbp-header">
        <ul class="forum-titles">
            <li class="bbp-topic-title"><?php esc_html_e('Topic', 'soplos'); ?></li>
            <li class="bbp-topic-voice-count"><?php esc_html_e('Voices', 'soplos'); ?></li>
            <li class="bbp-topic-reply-count"><?php esc_html_e('Replies', 'soplos'); ?></li>
            <li class="bbp-topic-freshness"><?php esc_html_e('Last Post', 'soplos'); ?></li>
        </ul>
    </li>

    <li class="bbp-body">
        <?php while (bbp_topics()) : bbp_the_topic(); ?>

            <ul id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>
                <li class="bbp-topic-title">
                    <?php if (bbp_is_user_home()) : ?>
                        <?php if (bbp_is_favorites()) : ?>
                            <span class="bbp-row-actions">
                                <?php do_action('bbp_theme_before_topic_favorites_action'); ?>
                                <?php bbp_topic_favorite_link(array('before' => '', 'favorite' => '+', 'favorited' => '-')); ?>
                                <?php do_action('bbp_theme_after_topic_favorites_action'); ?>
                            </span>
                        <?php elseif (bbp_is_subscriptions()) : ?>
                            <span class="bbp-row-actions">
                                <?php do_action('bbp_theme_before_topic_subscription_action'); ?>
                                <?php bbp_topic_subscription_link(array('before' => '', 'subscribe' => '+', 'unsubscribe' => '-')); ?>
                                <?php do_action('bbp_theme_after_topic_subscription_action'); ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php do_action('bbp_theme_before_topic_title'); ?>

                    <a class="bbp-topic-permalink" href="<?php bbp_topic_permalink(); ?>">
                        <?php bbp_topic_title(); ?>
                    </a>

                    <?php do_action('bbp_theme_after_topic_title'); ?>

                    <?php bbp_topic_pagination(); ?>

                    <?php do_action('bbp_theme_before_topic_meta'); ?>

                    <p class="bbp-topic-meta">
                        <?php do_action('bbp_theme_before_topic_started_by'); ?>
                        <span class="bbp-topic-started-by">
                            <?php printf(esc_html__('Started by: %1$s', 'soplos'), bbp_get_topic_author_link(array('size' => '14'))); ?>
                        </span>
                        <?php do_action('bbp_theme_after_topic_started_by'); ?>

                        <?php if (!bbp_is_single_forum() || (bbp_get_topic_forum_id() !== bbp_get_forum_id())) : ?>
                            <?php do_action('bbp_theme_before_topic_started_in'); ?>
                            <span class="bbp-topic-started-in">
                                <?php printf(esc_html__('in: %1$s', 'soplos'), '<a href="' . bbp_get_forum_permalink(bbp_get_topic_forum_id()) . '">' . bbp_get_forum_title(bbp_get_topic_forum_id()) . '</a>'); ?>
                            </span>
                            <?php do_action('bbp_theme_after_topic_started_in'); ?>
                        <?php endif; ?>
                    </p>

                    <?php do_action('bbp_theme_after_topic_meta'); ?>
                </li>

                <li class="bbp-topic-voice-count"><?php bbp_topic_voice_count(); ?></li>

                <li class="bbp-topic-reply-count"><?php bbp_show_lead_topic() ? bbp_topic_reply_count() : bbp_topic_post_count(); ?></li>

                <li class="bbp-topic-freshness">
                    <?php do_action('bbp_theme_before_topic_freshness_link'); ?>
                    <?php bbp_topic_freshness_link(); ?>
                    <?php do_action('bbp_theme_after_topic_freshness_link'); ?>

                    <p class="bbp-topic-meta">
                        <?php do_action('bbp_theme_before_topic_freshness_author'); ?>
                        <span class="bbp-topic-freshness-author"><?php bbp_author_link(array('post_id' => bbp_get_topic_last_active_id(), 'size' => 14)); ?></span>
                        <?php do_action('bbp_theme_after_topic_freshness_author'); ?>
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

<?php do_action('bbp_template_after_topics_loop'); ?>
