<?php
/**
 * Search results template.
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
        'title'       => __('Search results for:', 'dh'),
        'description' => get_search_query(),
    )
);
?>

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php get_template_part('template-parts/content'); ?>
                <?php endwhile; ?>

                <?php dh_the_posts_pagination(__('Search results', 'dh')); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
