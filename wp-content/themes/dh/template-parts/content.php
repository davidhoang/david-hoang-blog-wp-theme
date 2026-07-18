<?php
/**
 * Post content template.
 *
 * @package dh
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <header class="entry-header">
        <?php if (is_singular()) : ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php else : ?>
            <h2 class="entry-title<?php echo get_the_title() ? '' : ' entry-title--empty'; ?>">
                <?php if (get_the_title()) : ?>
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                <?php endif; ?>
            </h2>
        <?php endif; ?>
    </header>

    <?php if (is_singular('post')) : ?>
        <?php dh_entry_meta(); ?>
    <?php endif; ?>

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php if (is_singular()) : ?>
                <?php the_post_thumbnail('large'); ?>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('large'); ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content();

        if (is_singular()) {
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'dh'),
                'after'  => '</div>',
            ));
        }
        ?>
    </div>

    <?php if (is_singular('post')) : ?>
        <?php
        $tags_list = get_the_tag_list('', ', ');
        if ($tags_list) :
            ?>
            <div class="entry-tags">
                <span class="entry-tags__label"><?php esc_html_e('Tags:', 'dh'); ?></span>
                <?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!is_singular()) : ?>
        <a href="<?php the_permalink(); ?>" class="post-view" aria-label="<?php echo esc_attr(sprintf(__('View post%s', 'dh'), get_the_title() ? ': ' . get_the_title() : '')); ?>">
            <svg class="post-view__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">
                <path d="M3.5 8h7.5M8.5 5.25 11.75 8 8.5 10.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    <?php endif; ?>
</article>
