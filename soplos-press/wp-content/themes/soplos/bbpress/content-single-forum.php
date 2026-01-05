<?php
/**
 * bbPress Content Single Forum Template
 * 
 * @package Soplos
 */
?>

<div id="bbpress-forums">

    <?php bbp_breadcrumb(); ?>

    <?php do_action('bbp_template_before_single_forum'); ?>

    <?php if (post_password_required()) : ?>

        <?php bbp_get_template_part('form', 'protected'); ?>

    <?php else : ?>

        <?php bbp_forum_subscription_link(); ?>

        <?php do_action('bbp_template_before_forum_description'); ?>

        <div class="bbp-forum-description">
            <?php the_content(); ?>
        </div>

        <?php do_action('bbp_template_after_forum_description'); ?>

        <?php if (bbp_has_forums()) : ?>

            <?php bbp_get_template_part('loop', 'forums'); ?>

        <?php endif; ?>

        <?php if (!bbp_is_forum_category() && bbp_has_topics()) : ?>

            <?php bbp_get_template_part('pagination', 'topics'); ?>

            <?php bbp_get_template_part('loop', 'topics'); ?>

            <?php bbp_get_template_part('pagination', 'topics'); ?>

        <?php elseif (!bbp_is_forum_category()) : ?>

            <div class="bbp-template-notice">
                <p><?php esc_html_e('There are no topics in this forum yet.', 'soplos'); ?></p>
            </div>

        <?php endif; ?>

        <?php if (!bbp_is_forum_category()) : ?>

            <?php bbp_get_template_part('form', 'topic'); ?>

        <?php endif; ?>

    <?php endif; ?>

    <?php do_action('bbp_template_after_single_forum'); ?>

</div>
