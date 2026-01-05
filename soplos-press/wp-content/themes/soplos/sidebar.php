<?php
/**
 * Sidebar template
 * 
 * @package Soplos
 */

if (!is_active_sidebar('sidebar-main')) {
    return;
}
?>

<aside id="secondary" class="sidebar widget-area">
    <?php dynamic_sidebar('sidebar-main'); ?>
</aside>
