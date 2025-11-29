<?php
/**
 * The template for displaying single posts
 *
 * @package DavidHoang
 */

get_header();
?>

<main id="main" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        if (has_post_thumbnail()) :
            ?>
            <div class="post-featured-image-hero">
                <div class="site-container">
                    <?php the_post_thumbnail('large', array('class' => 'featured-image-hero')); ?>
                </div>
            </div>
            <?php
        endif;
        ?>
        
        <div class="site-container single-post-container">
            <div class="content-wrapper">
                <article id="post-<?php the_ID(); ?>" <?php post_class('post single-post'); ?>>
                    <header class="post-header">
                        <h1 class="post-title"><?php the_title(); ?></h1>
                        <div class="post-meta">
                            <?php echo david_hoang_posted_on(); ?>
                        </div>
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
                    
                    <footer class="post-footer">
                        <?php
                        // Post navigation
                        the_post_navigation(array(
                            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'david-hoang') . '</span> <span class="nav-title">%title</span>',
                            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'david-hoang') . '</span> <span class="nav-title">%title</span>',
                        ));
                        
                        // Comments
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                    </footer>
                </article>
            </div>
            
            <?php get_sidebar(); ?>
        </div>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
