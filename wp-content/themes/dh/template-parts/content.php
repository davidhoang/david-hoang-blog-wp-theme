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
            <h2 class="entry-title<?php echo get_the_title() ? '' : ' entry-title--empty'; ?>">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo wp_kses(dh_get_highlighted_display_title(), dh_search_highlight_allowed_html()); ?></a>
            </h2>
        <?php endif; ?>
    </header>

    <?php if (is_singular('post')) : ?>
        <?php dh_entry_meta(); ?>
    <?php else : ?>
        <div class="entry-kicker">
            <?php if (is_tax('series')) : ?>
                <?php $series_context = dh_get_series_context(); ?>
                <?php if ($series_context) : ?>
                    <span>
                        <?php
                        printf(
                            /* translators: 1: current part number, 2: total number of parts */
                            esc_html__('Part %1$d of %2$d', 'dh'),
                            (int) $series_context['position'],
                            (int) $series_context['total']
                        );
                        ?>
                    </span>
                    <span aria-hidden="true">&middot;</span>
                <?php endif; ?>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" rel="bookmark">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
            </a>
        </div>
    <?php endif; ?>

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php
            $thumbnail_attrs = array(
                'decoding' => 'async',
                'sizes'    => '(max-width: 1024px) 92vw, min(46rem, 100vw)',
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
                <a href="<?php the_permalink(); ?>">
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
        <a href="<?php the_permalink(); ?>" class="post-view" aria-label="<?php echo esc_attr(sprintf(__('View post: %s', 'dh'), dh_get_display_title())); ?>">
            <svg class="post-view__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">
                <path d="M3.5 8h7.5M8.5 5.25 11.75 8 8.5 10.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    <?php endif; ?>
</article>
