<?php
/**
 * Single photo template.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/layout', 'start');
?>

            <?php
            while (have_posts()) :
                the_post();

                get_template_part('template-parts/breadcrumbs');
                get_template_part('template-parts/content', 'photo');

                the_post_navigation(array(
                    'prev_text' => '<span class="post-navigation__icon" aria-hidden="true">&larr;</span><span class="post-navigation__title">%title</span>',
                    'next_text' => '<span class="post-navigation__title">%title</span><span class="post-navigation__icon" aria-hidden="true">&rarr;</span>',
                    'in_same_term' => false,
                    'taxonomy'     => 'category',
                ));
            endwhile;
            ?>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
