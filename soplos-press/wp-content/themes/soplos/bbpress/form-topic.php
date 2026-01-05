<?php
/**
 * bbPress New Topic Form Template
 * 
 * @package Soplos
 */
?>

<?php if (bbp_is_topic_edit()) : ?>

    <?php bbp_topic_tag_list(bbp_get_topic_id()); ?>
    <?php bbp_single_topic_description(array('topic_id' => bbp_get_topic_id())); ?>

<?php endif; ?>

<?php if (bbp_current_user_can_access_create_topic_form()) : ?>

    <div id="new-topic-<?php bbp_topic_id(); ?>" class="bbp-topic-form">

        <form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">

            <?php do_action('bbp_theme_before_topic_form'); ?>

            <fieldset class="bbp-form">
                <legend>
                    <?php
                    if (bbp_is_topic_edit()) {
                        printf(esc_html__('Edit Topic "%s"', 'soplos'), bbp_get_topic_title());
                    } else {
                        esc_html_e('Create New Topic', 'soplos');
                    }
                    ?>
                </legend>

                <?php do_action('bbp_theme_before_topic_form_notices'); ?>

                <?php if (!bbp_is_topic_edit() && bbp_is_forum_closed()) : ?>
                    <div class="bbp-template-notice">
                        <p><?php esc_html_e('This forum is closed to new topics.', 'soplos'); ?></p>
                    </div>
                <?php endif; ?>

                <?php do_action('bbp_template_notices'); ?>

                <div>

                    <?php do_action('bbp_theme_before_topic_form_title'); ?>

                    <p>
                        <label for="bbp_topic_title"><?php printf(esc_html__('Topic Title (Maximum Length: %d):', 'soplos'), bbp_get_title_max_length()); ?></label><br />
                        <input type="text" id="bbp_topic_title" value="<?php bbp_form_topic_title(); ?>" size="40" name="bbp_topic_title" maxlength="<?php bbp_title_max_length(); ?>" />
                    </p>

                    <?php do_action('bbp_theme_after_topic_form_title'); ?>

                    <?php do_action('bbp_theme_before_topic_form_content'); ?>

                    <p>
                        <label for="bbp_topic_content"><?php esc_html_e('Topic Content:', 'soplos'); ?></label><br />
                        <textarea id="bbp_topic_content" rows="12" cols="60" name="bbp_topic_content"><?php bbp_form_topic_content(); ?></textarea>
                    </p>

                    <?php do_action('bbp_theme_after_topic_form_content'); ?>

                    <?php if (!(bbp_use_wp_editor() || current_user_can('unfiltered_html'))) : ?>
                        <p class="form-allowed-tags">
                            <label><?php esc_html_e('Allowed Tags:', 'soplos'); ?></label><br />
                            <code><?php bbp_allowed_tags(); ?></code>
                        </p>
                    <?php endif; ?>

                    <?php do_action('bbp_theme_before_topic_form_tags'); ?>

                    <p>
                        <label for="bbp_topic_tags"><?php esc_html_e('Topic Tags:', 'soplos'); ?></label><br />
                        <input type="text" value="<?php bbp_form_topic_tags(); ?>" size="40" name="bbp_topic_tags" id="bbp_topic_tags" />
                    </p>

                    <?php do_action('bbp_theme_after_topic_form_tags'); ?>

                    <?php do_action('bbp_theme_before_topic_form_submit_wrapper'); ?>

                    <div class="bbp-submit-wrapper">
                        <?php do_action('bbp_theme_before_topic_form_submit_button'); ?>

                        <button type="submit" id="bbp_topic_submit" name="bbp_topic_submit" class="button submit">
                            <?php esc_html_e('Submit', 'soplos'); ?>
                        </button>

                        <?php do_action('bbp_theme_after_topic_form_submit_button'); ?>
                    </div>

                    <?php do_action('bbp_theme_after_topic_form_submit_wrapper'); ?>

                </div>

                <?php bbp_topic_form_fields(); ?>

            </fieldset>

            <?php do_action('bbp_theme_after_topic_form'); ?>

        </form>

    </div>

<?php elseif (bbp_is_forum_closed()) : ?>

    <div id="no-topic-<?php bbp_topic_id(); ?>" class="bbp-no-topic">
        <div class="bbp-template-notice">
            <p><?php printf(esc_html__('The forum "%s" is closed to new topics and replies.', 'soplos'), bbp_get_forum_title()); ?></p>
        </div>
    </div>

<?php else : ?>

    <div id="no-topic-<?php bbp_topic_id(); ?>" class="bbp-no-topic">
        <div class="bbp-template-notice">
            <p><?php printf(esc_html__('You must be logged in to create new topics.', 'soplos')); ?></p>
        </div>
    </div>

<?php endif; ?>
