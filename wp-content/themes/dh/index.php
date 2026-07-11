<?php
/**
 * Main template.
 *
 * @package dh
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="site-layout">
        <div class="content-area">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php get_template_part('template-parts/content'); ?>
                <?php endwhile; ?>

                <nav class="posts-navigation" aria-label="<?php esc_attr_e('Posts', 'dh'); ?>">
                    <?php
                    the_posts_navigation(array(
                        'prev_text' => esc_html__('← Older Posts', 'dh'),
                        'next_text' => esc_html__('Newer Posts →', 'dh'),
                    ));
                    ?>
                </nav>
            <?php else : ?>
                <p><?php esc_html_e('No posts found.', 'dh'); ?></p>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
