<?php
/**
 * Search result item template.
 *
 * @package dh
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post post--search'); ?>>
    <header class="entry-header">
        <h2 class="entry-title<?php echo get_the_title() ? '' : ' entry-title--empty'; ?>">
            <?php if (get_the_title()) : ?>
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php esc_html_e('(Untitled)', 'dh'); ?></a>
            <?php endif; ?>
        </h2>
    </header>

    <?php if ('post' === get_post_type()) : ?>
        <?php dh_entry_meta(); ?>
    <?php endif; ?>

    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div>

    <a href="<?php the_permalink(); ?>" class="post-view" aria-label="<?php echo esc_attr(sprintf(__('View post%s', 'dh'), get_the_title() ? ': ' . get_the_title() : '')); ?>">
        <svg class="post-view__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">
            <path d="M3.5 8h7.5M8.5 5.25 11.75 8 8.5 10.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>
</article>
