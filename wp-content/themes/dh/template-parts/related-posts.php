<?php
/**
 * Related posts list.
 *
 * @package dh
 *
 * @var array $args {
 *     @type WP_Post[] $related_posts Related posts to render.
 * }
 */

$related_posts = isset($args['related_posts']) ? $args['related_posts'] : array();

if (empty($related_posts)) {
    return;
}
?>

<section class="related-posts" aria-label="<?php esc_attr_e('Related posts', 'dh'); ?>">
    <h2 class="related-posts__title"><?php esc_html_e('Related posts', 'dh'); ?></h2>
    <ul class="related-posts__list">
        <?php foreach ($related_posts as $related_post) : ?>
            <li class="related-posts__item">
                <a class="related-posts__link" href="<?php echo esc_url(get_permalink($related_post)); ?>">
                    <span class="related-posts__post-title"><?php echo esc_html(get_the_title($related_post)); ?></span>
                    <time class="related-posts__date" datetime="<?php echo esc_attr(get_the_date('c', $related_post)); ?>">
                        <?php echo esc_html(get_the_date('', $related_post)); ?>
                    </time>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
