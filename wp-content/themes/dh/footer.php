<?php
/**
 * Footer template.
 *
 * @package dh
 */
?>
    <footer id="colophon" class="site-footer">
        <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footer', 'dh'); ?>">
            <ul class="site-footer__links">
                <li>
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'dh'); ?></a>
                </li>
                <li>
                    <a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>"><?php esc_html_e('RSS', 'dh'); ?></a>
                </li>
            </ul>
        </nav>

        <p class="site-footer__copy">
            &copy; <?php echo esc_html(gmdate('Y')); ?>
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
        </p>

        <a href="#page" class="site-footer__top"><?php esc_html_e('Back to top', 'dh'); ?></a>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
