<?php
/**
 * Photo archive card.
 *
 * @package dh
 */
?>

<?php
$full_image = dh_get_photo_full_image();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('photo-card'); ?>>
    <div class="photo-card__link">
        <?php if ($full_image) : ?>
            <button
                class="photo-card__media"
                type="button"
                data-dh-lightbox-open
                data-dh-lightbox-src="<?php echo esc_url($full_image['url']); ?>"
                data-dh-lightbox-alt="<?php echo esc_attr($full_image['alt']); ?>"
                data-dh-lightbox-caption="<?php echo esc_attr(dh_get_display_title()); ?>"
                data-dh-lightbox-permalink="<?php echo esc_url(get_permalink()); ?>"
                aria-label="<?php echo esc_attr(sprintf(__('View full size: %s', 'dh'), dh_get_display_title())); ?>"
            >
                <?php the_post_thumbnail('medium_large', array('loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 640px) 92vw, (max-width: 1024px) 44vw, 24vw')); ?>
            </button>
        <?php else : ?>
            <a class="photo-card__media" href="<?php the_permalink(); ?>">
                <span class="photo-card__placeholder" aria-hidden="true"></span>
            </a>
        <?php endif; ?>
        <a class="photo-card__caption" href="<?php the_permalink(); ?>">
            <h2><?php echo esc_html(dh_get_display_title()); ?></h2>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('M Y')); ?></time>
        </a>
    </div>
</article>
