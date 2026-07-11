<?php
/**
 * Post content template.
 *
 * @package dh
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <header class="entry-header">
        <?php if (is_singular()) : ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php else : ?>
            <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h2>
        <?php endif; ?>
    </header>

    <?php dh_entry_meta(); ?>

    <?php if (!is_singular() && has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if (is_singular()) {
            the_content();

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'dh'),
                'after'  => '</div>',
            ));
        } else {
            the_content();
        }
        ?>
    </div>

    <?php if (is_singular() && (comments_open() || get_comments_number())) : ?>
        <footer class="entry-footer">
            <?php comments_template(); ?>
        </footer>
    <?php endif; ?>
</article>
