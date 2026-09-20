<?php
/**
 * Photo archive card.
 *
 * @package dh
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('photo-card'); ?>>
    <a class="photo-card__link" href="<?php the_permalink(); ?>">
        <div class="photo-card__media">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', array('loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 640px) 92vw, (max-width: 1024px) 44vw, 24vw')); ?>
            <?php else : ?>
                <span class="photo-card__placeholder" aria-hidden="true"></span>
            <?php endif; ?>
        </div>
        <div class="photo-card__caption">
            <h2><?php echo esc_html(dh_get_display_title()); ?></h2>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('M Y')); ?></time>
        </div>
    </a>
</article>
