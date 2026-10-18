<?php
/**
 * Single photo content.
 *
 * @package dh
 */

$full_image = dh_get_photo_full_image();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('photo-single'); ?>>
    <header class="entry-header">
        <h1 class="entry-title<?php echo get_the_title() ? '' : ' entry-title--empty'; ?>">
            <?php echo esc_html(dh_get_display_title()); ?>
        </h1>
        <div class="entry-meta photo-single__meta">
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
            <?php
            $archive_link = get_post_type_archive_link('photo');

            if ($archive_link) :
                ?>
                <span class="entry-meta__separator" aria-hidden="true">&middot;</span>
                <a href="<?php echo esc_url($archive_link); ?>"><?php esc_html_e('All photographs', 'dh'); ?></a>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($full_image) : ?>
        <figure class="photo-single__figure">
            <button
                class="photo-single__zoom"
                type="button"
                data-dh-lightbox-open
                data-dh-lightbox-src="<?php echo esc_url($full_image['url']); ?>"
                data-dh-lightbox-alt="<?php echo esc_attr($full_image['alt']); ?>"
                data-dh-lightbox-caption="<?php echo esc_attr(wp_strip_all_tags(get_the_excerpt())); ?>"
                aria-label="<?php esc_attr_e('View full-size photograph', 'dh'); ?>"
            >
                <?php
                echo wp_get_attachment_image(
                    $full_image['id'],
                    'large',
                    false,
                    array(
                        'class'         => 'photo-single__image',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                        'decoding'      => 'async',
                        'sizes'         => dh_get_featured_image_sizes(),
                    )
                );
                ?>
            </button>
            <?php if (get_the_excerpt()) : ?>
                <figcaption class="photo-single__caption"><?php the_excerpt(); ?></figcaption>
            <?php endif; ?>
        </figure>
    <?php endif; ?>

    <?php if (trim((string) get_the_content())) : ?>
        <div class="entry-content photo-single__notes">
            <?php the_content(); ?>
        </div>
    <?php endif; ?>

    <?php
    $tags_list = get_the_tag_list('', ', ');

    if ($tags_list) :
        ?>
        <footer class="photo-single__footer">
            <p class="entry-tags">
                <span class="entry-tags__label"><?php esc_html_e('Tagged', 'dh'); ?></span>
                <?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </p>
        </footer>
    <?php endif; ?>
</article>
