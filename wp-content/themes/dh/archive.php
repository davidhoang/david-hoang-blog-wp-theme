<?php
/**
 * Archive template (categories, tags, dates, authors).
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
get_template_part('template-parts/layout', 'start');

get_template_part(
    'template-parts/page',
    'header',
    array(
        'title'       => get_the_archive_title(),
        'description' => get_the_archive_description(),
    )
);

dh_render_archive_meta();
?>

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php get_template_part('template-parts/content'); ?>
                <?php endwhile; ?>

                <?php dh_the_posts_pagination(__('Posts', 'dh')); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
