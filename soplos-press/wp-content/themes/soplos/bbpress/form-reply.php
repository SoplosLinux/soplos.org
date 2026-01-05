<?php
/**
 * bbPress Reply Form Template
 * 
 * @package Soplos
 */
?>

<?php if (bbp_is_reply_edit()) : ?>

    <?php bbp_topic_tag_list(bbp_get_reply_topic_id()); ?>
    <?php bbp_single_reply_description(); ?>

<?php endif; ?>

<?php if (bbp_current_user_can_access_create_reply_form()) : ?>

    <div id="new-reply-<?php bbp_topic_id(); ?>" class="bbp-reply-form">

        <form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">

            <?php do_action('bbp_theme_before_reply_form'); ?>

            <fieldset class="bbp-form">
                <legend>
                    <?php
                    if (bbp_is_reply_edit()) {
                        printf(esc_html__('Edit Reply', 'soplos'));
                    } else {
                        esc_html_e('Reply To This Topic', 'soplos');
                    }
                    ?>
                </legend>

                <?php do_action('bbp_theme_before_reply_form_notices'); ?>

                <?php if (!bbp_is_topic_open() && !bbp_is_reply_edit()) : ?>
                    <div class="bbp-template-notice">
                        <p><?php esc_html_e('This topic is closed to new replies.', 'soplos'); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (bbp_is_forum_closed(bbp_get_topic_forum_id()) && !bbp_is_reply_edit()) : ?>
                    <div class="bbp-template-notice">
                        <p><?php esc_html_e('This forum is closed to new content.', 'soplos'); ?></p>
                    </div>
                <?php endif; ?>

                <?php do_action('bbp_template_notices'); ?>

                <div>

                    <?php do_action('bbp_theme_before_reply_form_content'); ?>

                    <?php bbp_the_content(array('context' => 'reply')); ?>

                    <?php if (!(bbp_use_wp_editor() || current_user_can('unfiltered_html'))) : ?>
                        <p class="form-allowed-tags">
                            <label><?php esc_html_e('Allowed Tags:', 'soplos'); ?></label><br />
                            <code><?php bbp_allowed_tags(); ?></code>
                        </p>
                    <?php endif; ?>

                    <?php do_action('bbp_theme_after_reply_form_content'); ?>

                    <?php do_action('bbp_theme_before_reply_form_submit_wrapper'); ?>

                    <div class="bbp-submit-wrapper">
                        <?php do_action('bbp_theme_before_reply_form_submit_button'); ?>

                        <button type="submit" id="bbp_reply_submit" name="bbp_reply_submit" class="button submit">
                            <?php esc_html_e('Submit Reply', 'soplos'); ?>
                        </button>

                        <?php do_action('bbp_theme_after_reply_form_submit_button'); ?>
                    </div>

                    <?php do_action('bbp_theme_after_reply_form_submit_wrapper'); ?>

                </div>

                <?php bbp_reply_form_fields(); ?>

            </fieldset>

            <?php do_action('bbp_theme_after_reply_form'); ?>

        </form>

    </div>

<?php elseif (bbp_is_topic_closed()) : ?>

    <div id="no-reply-<?php bbp_topic_id(); ?>" class="bbp-no-reply">
        <div class="bbp-template-notice">
            <p><?php printf(esc_html__('The topic "%s" is closed to new replies.', 'soplos'), bbp_get_topic_title()); ?></p>
        </div>
    </div>

<?php elseif (bbp_is_forum_closed(bbp_get_topic_forum_id())) : ?>

    <div id="no-reply-<?php bbp_topic_id(); ?>" class="bbp-no-reply">
        <div class="bbp-template-notice">
            <p><?php printf(esc_html__('The forum "%s" is closed to new replies.', 'soplos'), bbp_get_forum_title(bbp_get_topic_forum_id())); ?></p>
        </div>
    </div>

<?php else : ?>

    <div id="no-reply-<?php bbp_topic_id(); ?>" class="bbp-no-reply">
        <div class="bbp-template-notice">
            <p><?php printf(esc_html__('You must be logged in to reply to this topic.', 'soplos')); ?></p>
        </div>
    </div>

<?php endif; ?>
