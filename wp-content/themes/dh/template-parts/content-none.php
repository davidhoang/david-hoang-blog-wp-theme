<?php
/**
 * Empty results template.
 *
 * @package dh
 */
?>

<section class="no-results not-found">
    <header class="no-results__header">
        <h1 class="no-results__title"><?php esc_html_e('Nothing found', 'dh'); ?></h1>
    </header>

    <div class="no-results__content">
        <?php if (is_home() && current_user_can('publish_posts')) : ?>
            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: %s: URL to create a new post. */
                        __('Ready to publish your first post? <a href="%s">Get started here</a>.', 'dh'),
                        array('a' => array('href' => array()))
                    ),
                    esc_url(admin_url('post-new.php'))
                );
                ?>
            </p>
        <?php elseif (is_search()) : ?>
            <p><?php esc_html_e('No results matched your search. Try different keywords.', 'dh'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Maybe try a search?', 'dh'); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>
