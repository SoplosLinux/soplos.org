<?php
/**
 * Soplos Theme - bbPress Support
 * 
 * @package Soplos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dequeue bbPress default styles
 */
function soplos_dequeue_bbpress_styles() {
    if (class_exists('bbPress')) {
        wp_dequeue_style('bbp-default');
        wp_deregister_style('bbp-default');
    }
}
add_action('wp_enqueue_scripts', 'soplos_dequeue_bbpress_styles', 20);

/**
 * bbPress role names
 */
function soplos_bbpress_role_names($roles) {
    if (isset($roles['bbp_keymaster'])) {
        $roles['bbp_keymaster']['name'] = __('Administrator', 'soplos');
    }
    if (isset($roles['bbp_moderator'])) {
        $roles['bbp_moderator']['name'] = __('Moderator', 'soplos');
    }
    if (isset($roles['bbp_participant'])) {
        $roles['bbp_participant']['name'] = __('Member', 'soplos');
    }
    return $roles;
}
add_filter('bbp_get_dynamic_roles', 'soplos_bbpress_role_names');

/**
 * Admin notice for bbPress
 */
function soplos_admin_notice_bbpress() {
    if (!class_exists('bbPress') && current_user_can('install_plugins')) {
        $screen = get_current_screen();
        if ($screen && $screen->id === 'themes') {
            ?>
            <div class="notice notice-info is-dismissible">
                <p>
                    <strong><?php _e('Soplos Theme:', 'soplos'); ?></strong>
                    <?php _e('For full forum functionality, please install and activate the bbPress plugin.', 'soplos'); ?>
                    <a href="<?php echo admin_url('plugin-install.php?s=bbpress&tab=search&type=term'); ?>">
                        <?php _e('Install bbPress', 'soplos'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'soplos_admin_notice_bbpress');
