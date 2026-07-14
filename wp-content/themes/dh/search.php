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
                    <?php get_template_part('template-parts/content'); ?>
                <?php endwhile; ?>

                <nav class="posts-navigation" aria-label="<?php esc_attr_e('Search results', 'dh'); ?>">
                    <?php
                    the_posts_navigation(array(
                        'prev_text' => esc_html__('← Older Results', 'dh'),
                        'next_text' => esc_html__('Newer Results →', 'dh'),
                    ));
                    ?>
                </nav>
            <?php else : ?>
                <div class="no-results">
                    <p><?php esc_html_e('No results matched your search. Try different keywords.', 'dh'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
