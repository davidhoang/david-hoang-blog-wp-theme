<?php
/**
 * Archive template (categories, tags, dates, authors).
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
?>

<main id="main" class="site-main">
    <div class="site-layout">
        <div class="content-area">
            <header class="archive-header">
                <?php the_archive_title('<h1 class="archive-title">', '</h1>'); ?>
                <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
            </header>

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
