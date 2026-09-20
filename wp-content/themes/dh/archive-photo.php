<?php
/**
 * Photography archive.
 *
 * @package dh
 */

get_header();
get_template_part('template-parts/layout', 'start');
get_template_part('template-parts/breadcrumbs');
get_template_part(
    'template-parts/page',
    'header',
    array(
        'title'       => __('Photographs', 'dh'),
        'description' => __('Observations, places, and small moments collected through a camera.', 'dh'),
    )
);
?>

            <?php if (have_posts()) : ?>
                <div class="photo-grid">
                    <?php while (have_posts()) : ?>
                        <?php the_post(); ?>
                        <?php get_template_part('template-parts/photo', 'card'); ?>
                    <?php endwhile; ?>
                </div>

                <?php dh_the_posts_pagination(__('Photographs', 'dh')); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
