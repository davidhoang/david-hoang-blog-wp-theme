<?php
/**
 * The template for displaying all pages
 *
 * @package DavidHoang
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="site-container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                <header class="post-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="post-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'david-hoang'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
            </article>
            <?php
            
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();
