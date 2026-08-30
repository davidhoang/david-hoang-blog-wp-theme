<?php
/**
 * Page content template.
 *
 * @package dh
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
    <header class="entry-header">
        <?php if (is_front_page()) : ?>
            <h2 class="entry-title"><?php the_title(); ?></h2>
        <?php else : ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php endif; ?>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php
            the_post_thumbnail('large', array(
                'loading'  => 'lazy',
                'decoding' => 'async',
                'sizes'    => dh_get_featured_image_sizes(),
            ));
            ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'dh'),
            'after'  => '</div>',
        ));
        ?>
    </div>
</article>
