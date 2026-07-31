<?php
/**
 * Shared page header for archives, search, 404, and empty states.
 *
 * @package dh
 *
 * @var array $args {
 *     @type string $title       Required. Header title text (may include safe HTML).
 *     @type string $description Optional. Supporting text below the title (may include safe HTML).
 * }
 */

$args = wp_parse_args(
    isset($args) ? $args : array(),
    array(
        'title'       => '',
        'description' => '',
    )
);

if ($args['title'] === '') {
    return;
}
?>

<header class="page-header">
    <h1 class="page-header__title"><?php echo wp_kses_post($args['title']); ?></h1>
    <?php if ($args['description'] !== '') : ?>
        <div class="page-header__description"><?php echo wp_kses_post($args['description']); ?></div>
    <?php endif; ?>
</header>
