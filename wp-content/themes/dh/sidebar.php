<?php
/**
 * Sidebar template.
 *
 * @package dh
 */

$sidebar      = dh_get_sidebar_fallback();
$author_name  = get_theme_mod('dh_author_name', 'David Hoang');
$author_url   = get_theme_mod('dh_author_url', 'https://davidhoang.com');
$author_photo = (int) get_theme_mod('dh_author_photo', 0);
$photo_url    = $author_photo ? wp_get_attachment_image_url($author_photo, 'thumbnail') : '';
?>

<aside id="secondary" class="sidebar" aria-label="<?php esc_attr_e('Sidebar', 'dh'); ?>">
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else : ?>
        <section class="widget sidebar-section h-card">
            <?php if ($photo_url) : ?>
                <img
                    class="h-card__photo u-photo"
                    src="<?php echo esc_url($photo_url); ?>"
                    alt=""
                    width="64"
                    height="64"
                    loading="lazy"
                    decoding="async"
                >
            <?php endif; ?>

            <h3 class="sidebar-title p-name">
                <?php if ($author_url) : ?>
                    <a class="u-url" href="<?php echo esc_url($author_url); ?>" rel="me"><?php echo esc_html($sidebar['title']); ?></a>
                <?php else : ?>
                    <?php echo esc_html($sidebar['title']); ?>
                <?php endif; ?>
            </h3>

            <?php if ($sidebar['bio']) : ?>
                <p class="p-note"><?php echo esc_html($sidebar['bio']); ?></p>
            <?php endif; ?>

            <?php if (!empty($sidebar['links'])) : ?>
                <ul class="sidebar-list">
                    <?php foreach ($sidebar['links'] as $link) : ?>
                        <li>
                            <a href="<?php echo esc_url($link['url']); ?>"<?php echo (0 === strpos($link['url'], home_url())) ? '' : ' rel="me"'; ?>>
                                <?php echo esc_html($link['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($author_name && $author_url) : ?>
                <span class="screen-reader-text">
                    <a class="u-url p-name" href="<?php echo esc_url($author_url); ?>" rel="me"><?php echo esc_html($author_name); ?></a>
                </span>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</aside>
