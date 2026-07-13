<?php
/**
 * Search form template.
 *
 * @package dh
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field"><?php esc_html_e('Search', 'dh'); ?></label>
    <div class="search-form__inner">
        <input type="search" id="search-field" class="search-field" placeholder="<?php esc_attr_e('Search', 'dh'); ?>" value="<?php echo get_search_query(); ?>" name="s">
        <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'dh'); ?>">
            <?php echo dh_get_search_icon_svg(); ?>
        </button>
    </div>
</form>
