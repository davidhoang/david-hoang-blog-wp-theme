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
                $post_title = get_the_title();
                $post_link_label = $post_title ? $post_title : sprintf(
                    /* translators: %s: post date. */
                    esc_html__('View post from %s', 'david-hoang'),
                    get_the_date()
                );
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                    <header class="post-header">
                        <?php if ($post_title) : ?>
                        <h2 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php echo esc_html($post_title); ?></a>
                        </h2>
                        <?php endif; ?>
                        <div class="post-meta">
                            <?php echo david_hoang_posted_on(); ?>
                        </div>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-featured-image">
                            <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr($post_link_label); ?>">
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
