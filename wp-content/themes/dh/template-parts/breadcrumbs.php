<?php
/**
 * Visible breadcrumb trail for posts, pages, and archives.
 *
 * Uses the same item list as the BreadcrumbList JSON-LD helper.
 *
 * @package dh
 */

$items = dh_get_breadcrumb_items();

if (count($items) < 2) {
    return;
}

$last_index = count($items) - 1;
?>

<nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'dh'); ?>">
    <ol class="breadcrumbs__list">
        <?php foreach ($items as $index => $item) : ?>
            <li class="breadcrumbs__item">
                <?php if ($index < $last_index) : ?>
                    <a class="breadcrumbs__link" href="<?php echo esc_url($item['url']); ?>">
                        <?php echo esc_html($item['name']); ?>
                    </a>
                    <span class="breadcrumbs__separator" aria-hidden="true">/</span>
                <?php else : ?>
                    <span class="breadcrumbs__current" aria-current="page">
                        <?php echo esc_html($item['name']); ?>
                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
