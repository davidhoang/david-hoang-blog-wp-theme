<?php
/**
 * Blog posts index when a static page is set as the homepage.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
?>

<main id="main" class="site-main">
    <div class="site-layout">
        <div class="content-area">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php get_template_part('template-parts/content'); ?>
                <?php endwhile; ?>

                <?php dh_the_posts_pagination(__('Posts', 'dh')); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
