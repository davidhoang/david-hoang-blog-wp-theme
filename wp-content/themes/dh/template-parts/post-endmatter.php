<?php
/**
 * Personal end matter shown after a single post.
 *
 * @package dh
 */

$postscript    = get_post_meta(get_the_ID(), '_dh_postscript', true);
$series        = dh_get_series_context();
$tags_list     = get_the_tag_list('', ', ');
$published     = get_the_date('U');
$last_modified = get_the_modified_date('U');
$was_updated   = $last_modified > ($published + DAY_IN_SECONDS);

if (!$postscript && !$series && !$tags_list && !$was_updated) {
    return;
}
?>

<footer class="post-endmatter">
    <?php if ($postscript) : ?>
        <div class="post-endmatter__note">
            <h2 class="post-endmatter__heading"><?php esc_html_e('Postscript', 'dh'); ?></h2>
            <div class="post-endmatter__note-content">
                <?php echo wpautop(wp_kses_post($postscript)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($series) : ?>
        <section class="post-series" aria-labelledby="post-series-title">
            <p class="post-series__eyebrow">
                <?php
                printf(
                    /* translators: 1: current part number, 2: total number of parts */
                    esc_html__('Part %1$d of %2$d', 'dh'),
                    (int) $series['position'],
                    (int) $series['total']
                );
                ?>
            </p>
            <h2 class="post-series__title" id="post-series-title">
                <a href="<?php echo esc_url(get_term_link($series['term'])); ?>">
                    <?php echo esc_html($series['term']->name); ?>
                </a>
            </h2>

            <?php if ($series['previous'] || $series['next']) : ?>
                <nav class="post-series__navigation" aria-label="<?php esc_attr_e('Series navigation', 'dh'); ?>">
                    <?php if ($series['previous']) : ?>
                        <a class="post-series__link post-series__link--previous" href="<?php echo esc_url(get_permalink($series['previous'])); ?>">
                            <span class="post-series__direction"><?php esc_html_e('Earlier', 'dh'); ?></span>
                            <span><?php echo esc_html(dh_get_display_title($series['previous'])); ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if ($series['next']) : ?>
                        <a class="post-series__link post-series__link--next" href="<?php echo esc_url(get_permalink($series['next'])); ?>">
                            <span class="post-series__direction"><?php esc_html_e('Continue', 'dh'); ?></span>
                            <span><?php echo esc_html(dh_get_display_title($series['next'])); ?></span>
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if ($tags_list || $was_updated) : ?>
        <div class="post-endmatter__details">
            <?php if ($tags_list) : ?>
                <p class="entry-tags">
                    <span class="entry-tags__label"><?php esc_html_e('Filed under', 'dh'); ?></span>
                    <?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </p>
            <?php endif; ?>

            <?php if ($was_updated) : ?>
                <p class="post-endmatter__updated">
                    <?php esc_html_e('Last tended', 'dh'); ?>
                    <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"><?php echo esc_html(get_the_modified_date()); ?></time>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</footer>
