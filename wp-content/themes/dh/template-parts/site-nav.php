<?php
/**
 * Primary site navigation.
 *
 * @package dh
 */
?>

<nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e('Primary menu', 'dh'); ?>">
    <div class="site-nav__inner">
        <button
            type="button"
            class="site-nav__toggle"
            data-dh-nav-toggle
            data-label-open="<?php esc_attr_e('Open menu', 'dh'); ?>"
            data-label-close="<?php esc_attr_e('Close menu', 'dh'); ?>"
            aria-controls="site-nav-panel"
            aria-expanded="false"
            aria-label="<?php esc_attr_e('Open menu', 'dh'); ?>"
        >
            <span class="site-nav__toggle-bars" aria-hidden="true">
                <span class="site-nav__toggle-bar"></span>
                <span class="site-nav__toggle-bar"></span>
                <span class="site-nav__toggle-bar"></span>
            </span>
        </button>

        <div id="site-nav-panel" class="site-nav__panel" data-dh-nav-panel>
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
    </div>
</nav>
