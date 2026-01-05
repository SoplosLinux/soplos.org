<?php
/**
 * Search Form template
 * 
 * @package Soplos
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field-<?php echo esc_attr(uniqid()); ?>">
        <?php _e('Search for:', 'soplos'); ?>
    </label>
    <input type="search" 
           id="search-field-<?php echo esc_attr(uniqid()); ?>" 
           class="search-field" 
           placeholder="<?php esc_attr_e('Search...', 'soplos'); ?>" 
           value="<?php echo get_search_query(); ?>" 
           name="s" />
    <button type="submit" class="search-submit btn btn-primary">
        <i class="fas fa-search"></i>
        <span class="screen-reader-text"><?php _e('Search', 'soplos'); ?></span>
    </button>
</form>
