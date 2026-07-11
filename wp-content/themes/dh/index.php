<?php
/**
 * Main template.
 *
 * @package dh
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="site-container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                    <header class="post-header">
                        <h2 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="post-meta">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </div>
                    </header>

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
            <?php endwhile; ?>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('No posts found.', 'dh'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
