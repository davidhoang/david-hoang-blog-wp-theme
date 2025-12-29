<?php
/**
 * Template for displaying search forms
 *
 * @package DavidHoang
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field" class="screen-reader-text"><?php echo _x('Search for:', 'label', 'david-hoang'); ?></label>
    <div class="search-form-wrapper">
        <input 
            type="search" 
            id="search-field"
            class="search-field" 
            placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'david-hoang'); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
        />
        <button type="submit" class="search-submit"><?php echo _x('Search', 'submit button', 'david-hoang'); ?></button>
    </div>
</form>
