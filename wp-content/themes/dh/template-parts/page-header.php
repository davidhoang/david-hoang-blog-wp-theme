<?php
/**
 * Shared page header for archives, search, 404, and empty states.
 *
 * @package dh
 *
 * @var array $args {
 *     @type string $title       Required. Header title text (may include safe HTML).
 *     @type string $description            Optional. Supporting text below the title.
 *     @type bool   $description_allow_html When true, allow search-highlight mark tags only.
 * }
 */

$args = wp_parse_args(
    isset($args) ? $args : array(),
    array(
        'title'                  => '',
        'description'            => '',
        'description_allow_html' => false,
    )
);

if ($args['title'] === '') {
    return;
}
?>

<header class="page-header">
    <h1 class="page-header__title"><?php echo wp_kses_post($args['title']); ?></h1>
    <?php if ($args['description'] !== '') : ?>
        <div class="page-header__description<?php echo $args['description_allow_html'] ? ' page-header__description--query' : ''; ?>">
            <?php
            if ($args['description_allow_html']) {
                echo wp_kses($args['description'], dh_search_highlight_allowed_html());
            } else {
                echo wp_kses_post($args['description']);
            }
            ?>
        </div>
    <?php endif; ?>
</header>
