<?php
/**
 * Search results template.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
?>

<main id="main" class="site-main">
    <div class="site-layout">
        <div class="content-area">
            <header class="search-header">
                <h1 class="search-title">
                    <?php esc_html_e('Search results for:', 'dh'); ?>
                    <span class="search-query"><?php echo esc_html(get_search_query()); ?></span>
                </h1>
            </header>

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php get_template_part('template-parts/content', 'search'); ?>
                <?php endwhile; ?>

                <?php dh_the_posts_pagination(__('Search results', 'dh')); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
