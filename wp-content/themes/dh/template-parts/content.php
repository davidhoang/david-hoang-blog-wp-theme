<?php
/**
 * Post content template.
 *
 * @package dh
 */
?>

<?php if (!is_singular() && (is_home() || is_archive())) : ?>
    <?php dh_the_year_divider(); ?>
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <header class="entry-header">
        <?php if (is_singular()) : ?>
            <h1 class="entry-title<?php echo get_the_title() ? '' : ' entry-title--empty'; ?>"><?php echo esc_html(dh_get_display_title()); ?></h1>
        <?php else : ?>
            <?php dh_the_entry_kicker(); ?>
            <?php
            $view_url   = dh_get_post_view_url();
            $is_link    = dh_is_link_post();
            $link_attrs = $is_link ? ' target="_blank" rel="noopener noreferrer bookmark"' : ' rel="bookmark"';
            ?>
            <h2 class="entry-title entry-title--index<?php echo get_the_title() ? '' : ' entry-title--empty'; ?>">
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
        <?php endif; ?>
    </header>

    <?php if (is_singular('post')) : ?>
        <?php dh_entry_meta(); ?>
        <?php dh_render_link_post_outbound(); ?>
    <?php endif; ?>

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php
            $thumbnail_attrs = array(
                'decoding' => 'async',
                'sizes'    => dh_get_featured_image_sizes(),
            );

            if (is_singular()) {
                // Featured image is the likely LCP element on a single post, so
                // load it eagerly with a high fetch priority instead of lazily.
                $thumbnail_attrs['loading']       = 'eager';
                $thumbnail_attrs['fetchpriority'] = 'high';
                the_post_thumbnail('large', $thumbnail_attrs);
            } else {
                $is_first_in_loop = dh_should_eager_load_loop_thumbnail();

                $thumbnail_attrs['loading'] = $is_first_in_loop ? 'eager' : 'lazy';

                if ($is_first_in_loop) {
                    $thumbnail_attrs['fetchpriority'] = 'high';
                }
                ?>
                <a href="<?php echo esc_url(dh_get_post_view_url()); ?>"<?php echo dh_is_link_post() ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <?php the_post_thumbnail('large', $thumbnail_attrs); ?>
                </a>
                <?php
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (is_singular()) : ?>
        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'dh'),
                'after'  => '</div>',
            ));
            ?>
        </div>
    <?php else : ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>

    <?php if (is_singular('post')) : ?>
        <?php get_template_part('template-parts/post', 'endmatter'); ?>
    <?php endif; ?>

    <?php if (!is_singular()) : ?>
        <a href="<?php echo esc_url(dh_get_post_view_url()); ?>" class="post-view"<?php echo dh_is_link_post() ? ' target="_blank" rel="noopener noreferrer"' : ''; ?> aria-label="<?php echo esc_attr(sprintf(__('View post: %s', 'dh'), dh_get_display_title())); ?>">
            <svg class="post-view__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">
                <path d="M3.5 8h7.5M8.5 5.25 11.75 8 8.5 10.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    <?php endif; ?>
</article>
