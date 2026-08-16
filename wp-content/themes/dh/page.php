<?php
/**
 * Page template.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
get_template_part('template-parts/layout', 'start');
?>

            <?php
            while (have_posts()) :
                the_post();

                get_template_part('template-parts/breadcrumbs');
                get_template_part('template-parts/content', 'page');

                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
            endwhile;
            ?>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
