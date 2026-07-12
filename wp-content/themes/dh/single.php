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
            endwhile;
            ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
