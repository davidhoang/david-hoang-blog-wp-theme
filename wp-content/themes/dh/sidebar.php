<?php
/**
 * Sidebar template.
 *
 * @package dh
 */
?>

<aside id="secondary" class="sidebar" aria-label="<?php esc_attr_e('Sidebar', 'dh'); ?>">
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else : ?>
        <section class="widget sidebar-section">
            <h3 class="sidebar-title"><?php esc_html_e("I'm David", 'dh'); ?></h3>
            <p><?php esc_html_e('This is my personal blog. In 2025 I\'m taking web domains more seriously. Links to other places you\'ll find me:', 'dh'); ?></p>
            <ul class="sidebar-list">
                <li><a href="https://davidhoang.com">davidhoang.com</a></li>
                <li><a href="https://indieweb.social/@dh">indieweb.social/@dh</a></li>
                <li><a href="https://letterboxd.com/davidhoang/">Letterboxd</a></li>
                <li><a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>"><?php esc_html_e('RSS of this blog', 'dh'); ?></a></li>
            </ul>
        </section>
    <?php endif; ?>
</aside>
