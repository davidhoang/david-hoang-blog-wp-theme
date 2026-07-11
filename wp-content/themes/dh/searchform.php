<?php
/**
 * Search form template.
 *
 * @package dh
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field"><?php esc_html_e('Search for:', 'dh'); ?></label>
    <input type="search" id="search-field" class="search-field" placeholder="<?php esc_attr_e('Search for:', 'dh'); ?>" value="<?php echo get_search_query(); ?>" name="s">
    <button type="submit" class="search-submit"><?php esc_html_e('Search', 'dh'); ?></button>
</form>
