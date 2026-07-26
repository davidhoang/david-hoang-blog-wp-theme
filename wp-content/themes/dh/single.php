<?php
/**
 * Single post template.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
?>

<main id="main" class="site-main">
    <div class="site-layout">
        <div class="content-area">
            <?php
            while (have_posts()) :
                the_post();

                get_template_part('template-parts/content');

                the_post_navigation(array(
                    'prev_text' => '<span class="post-navigation__icon" aria-hidden="true">&larr;</span><span class="post-navigation__title">%title</span>',
                    'next_text' => '<span class="post-navigation__title">%title</span><span class="post-navigation__icon" aria-hidden="true">&rarr;</span>',
                ));

                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
            endwhile;
            ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
