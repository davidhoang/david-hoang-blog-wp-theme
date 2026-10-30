<?php
/**
 * Search results template.
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
        'title'                  => __('Search results for:', 'dh'),
        'description'            => dh_get_highlighted_search_query_html(),
        'description_allow_html' => true,
    )
);

dh_render_search_meta();
?>

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php get_template_part('template-parts/content', 'search'); ?>
                <?php endwhile; ?>

                <?php dh_the_posts_pagination(__('Search results', 'dh')); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
