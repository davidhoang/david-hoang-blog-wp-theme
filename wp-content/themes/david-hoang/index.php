<?php
/**
 * The main template file
 *
 * @package DavidHoang
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="site-container">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                    <header class="post-header">
                        <h2 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="post-meta">
                            <?php echo david_hoang_posted_on(); ?>
                        </div>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-featured-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large', array('class' => 'featured-image')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <?php
                        if (is_singular()) {
                            the_content();
                        } else {
                            the_excerpt();
                        }
                        ?>
                    </div>
                </article>
                <?php
            endwhile;
            
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
            ));
        else :
            ?>
            <p><?php esc_html_e('No posts found.', 'david-hoang'); ?></p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
