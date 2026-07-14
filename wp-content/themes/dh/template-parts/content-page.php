<?php
/**
 * Page content template.
 *
 * @package dh
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header>

    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'dh'),
            'after'  => '</div>',
        ));
        ?>
    </div>

    <?php if (comments_open() || get_comments_number()) : ?>
        <footer class="entry-footer">
            <?php comments_template(); ?>
        </footer>
    <?php endif; ?>
</article>
