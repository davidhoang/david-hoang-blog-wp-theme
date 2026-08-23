<?php
/**
 * Image attachment content.
 *
 * @package dh
 */

$metadata     = wp_get_attachment_metadata();
$parent_id    = wp_get_post_parent_id(get_the_ID());
$caption      = wp_get_attachment_caption();
$download_url = wp_get_attachment_url();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('attachment attachment--image'); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php echo esc_html(dh_get_display_title()); ?></h1>

        <p class="attachment-meta">
            <?php if ($parent_id) : ?>
                <a href="<?php echo esc_url(get_permalink($parent_id)); ?>">
                    <?php
                    printf(
                        /* translators: %s: parent post title */
                        esc_html__('From “%s”', 'dh'),
                        esc_html(dh_get_display_title($parent_id))
                    );
                    ?>
                </a>
                <span aria-hidden="true">&middot;</span>
            <?php endif; ?>

            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>

            <?php if (!empty($metadata['width']) && !empty($metadata['height'])) : ?>
                <span aria-hidden="true">&middot;</span>
                <span>
                    <?php echo esc_html((int) $metadata['width'] . ' × ' . (int) $metadata['height']); ?>
                </span>
            <?php endif; ?>
        </p>
    </header>

    <figure class="attachment-media">
        <?php
        echo wp_get_attachment_image(
            get_the_ID(),
            'large',
            false,
            array(
                'class'         => 'attachment-media__image',
                'loading'       => 'eager',
                'fetchpriority' => 'high',
                'decoding'      => 'async',
            )
        );
        ?>

        <?php if ($caption) : ?>
            <figcaption class="attachment-media__caption"><?php echo wp_kses_post($caption); ?></figcaption>
        <?php endif; ?>
    </figure>

    <?php if (get_the_content()) : ?>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
    <?php endif; ?>

    <p class="attachment-actions">
        <?php if ($download_url) : ?>
            <a class="attachment-actions__link" href="<?php echo esc_url($download_url); ?>">
                <?php esc_html_e('View full-size file', 'dh'); ?>
            </a>
        <?php endif; ?>

        <?php if ($parent_id) : ?>
            <a class="attachment-actions__link" href="<?php echo esc_url(get_permalink($parent_id)); ?>">
                <?php esc_html_e('Return to essay', 'dh'); ?>
            </a>
        <?php endif; ?>
    </p>
</article>
