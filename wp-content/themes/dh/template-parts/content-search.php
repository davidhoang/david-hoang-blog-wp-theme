<?php
/**
 * Search result item template.
 *
 * @package dh
 */

$view_url   = dh_get_post_view_url();
$is_link    = dh_is_link_post();
$link_attrs = $is_link ? ' target="_blank" rel="noopener noreferrer"' : ' rel="bookmark"';
$type_label = dh_get_search_result_type_label();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post post--search'); ?>>
    <header class="entry-header">
        <?php if ($type_label) : ?>
            <p class="search-result__meta">
                <span class="search-result__type"><?php echo esc_html($type_label); ?></span>
                <?php if ('page' !== get_post_type()) : ?>
                    <span class="search-result__separator" aria-hidden="true">&middot;</span>
                    <time class="search-result__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <h2 class="entry-title entry-title--search<?php echo get_the_title() ? '' : ' entry-title--empty'; ?>">
            <a href="<?php echo esc_url($view_url); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                <?php echo wp_kses(dh_get_highlighted_display_title(), dh_search_highlight_allowed_html()); ?>
                <?php if ($is_link) : ?>
                    <span class="entry-title__external" aria-hidden="true">&nearr;</span>
                <?php endif; ?>
            </a>
        </h2>

        <?php if ($is_link && dh_get_link_host_label()) : ?>
            <p class="entry-link-host"><?php echo esc_html(dh_get_link_host_label()); ?></p>
        <?php endif; ?>
    </header>

    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div>

    <a
        href="<?php echo esc_url($view_url); ?>"
        class="post-view"
        <?php echo $is_link ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
        aria-label="<?php echo esc_attr(sprintf(__('View result: %s', 'dh'), dh_get_display_title())); ?>"
    >
        <svg class="post-view__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">
            <path d="M3.5 8h7.5M8.5 5.25 11.75 8 8.5 10.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>
</article>
