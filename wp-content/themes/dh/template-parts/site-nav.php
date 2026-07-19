<?php
/**
 * Primary site navigation.
 *
 * @package dh
 */
?>

<nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e('Primary menu', 'dh'); ?>">
    <div class="site-nav__inner">
        <div class="site-nav__menu main-navigation">
            <?php dh_render_primary_menu(); ?>
        </div>

        <div class="site-nav__actions">
            <?php dh_render_theme_toggle(); ?>

            <div class="site-nav__social">
                <?php dh_render_social_links(); ?>
            </div>
        </div>
    </div>
</nav>
