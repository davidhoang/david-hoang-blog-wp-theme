<?php
/**
 * Reading font switcher.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Available reading styles for end users.
 *
 * Keys are stored in localStorage / data-font. Labels describe the reading
 * experience rather than a specific typeface name.
 *
 * @return array<string, array{label: string, description: string}>
 */
function dh_get_reading_fonts() {
    return array(
        'editorial' => array(
            'label'       => __('Editorial', 'dh'),
            'description' => __('Magazine serif for immersive reading.', 'dh'),
        ),
        'book'      => array(
            'label'       => __('Book', 'dh'),
            'description' => __('Soft book face tuned for long articles.', 'dh'),
        ),
        'sans'      => array(
            'label'       => __('Sans', 'dh'),
            'description' => __('Clean sans-serif for clear scanning.', 'dh'),
        ),
        'mono'      => array(
            'label'       => __('Mono', 'dh'),
            'description' => __('Monospaced typewriter rhythm.', 'dh'),
        ),
        'clear'     => array(
            'label'       => __('Clear', 'dh'),
            'description' => __('High-legibility face for easier reading.', 'dh'),
        ),
    );
}

/**
 * Default reading style slug.
 *
 * @return string
 */
function dh_get_default_reading_font() {
    return 'editorial';
}

/**
 * Inline script that applies the saved reading font before first paint.
 */
function dh_print_reading_font_boot_script() {
    $fonts   = array_keys(dh_get_reading_fonts());
    $default = dh_get_default_reading_font();
    ?>
    <script>
    (function () {
        try {
            var key = 'dh-reading-font';
            var allowed = <?php echo wp_json_encode($fonts); ?>;
            var stored = localStorage.getItem(key);
            var font = allowed.indexOf(stored) !== -1
                ? stored
                : <?php echo wp_json_encode($default); ?>;

            document.documentElement.setAttribute('data-font', font);
        } catch (e) {
            // no-op
        }
    })();
    </script>
    <?php
}
add_action('wp_head', 'dh_print_reading_font_boot_script', 1);

/**
 * Aa icon for the font switcher toggle.
 */
function dh_get_font_switcher_icon() {
    return '<svg class="font-switcher__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><path d="M3.25 12.5 6.1 3.5h1.3l2.85 9M4.2 9.75h3.85M10.35 12.5l1.55-4.35h.75L14.2 12.5M11.05 10.35h2.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * Render the reading font switcher control.
 */
function dh_render_font_switcher() {
    $fonts   = dh_get_reading_fonts();
    $default = dh_get_default_reading_font();
    $menu_id = 'dh-font-switcher-menu';

    echo '<div class="font-switcher">';

    printf(
        '<button type="button" class="font-switcher__toggle" data-dh-font-toggle aria-haspopup="true" aria-expanded="false" aria-controls="%1$s" aria-label="%2$s" title="%2$s">%3$s</button>',
        esc_attr($menu_id),
        esc_attr__('Choose reading font', 'dh'),
        dh_get_font_switcher_icon()
    );

    printf(
        '<div id="%1$s" class="font-switcher__menu" data-dh-font-menu hidden>',
        esc_attr($menu_id)
    );

    echo '<p class="font-switcher__heading" id="dh-font-switcher-heading">' . esc_html__('Reading style', 'dh') . '</p>';
    echo '<ul class="font-switcher__list" role="listbox" aria-labelledby="dh-font-switcher-heading">';

    foreach ($fonts as $slug => $font) {
        $is_default = ($slug === $default);

        printf(
            '<li><button type="button" class="font-switcher__option" data-dh-font-option="%1$s" data-font-preview="%1$s" role="option" aria-selected="%2$s"><span class="font-switcher__option-label">%3$s</span><span class="font-switcher__option-desc">%4$s</span></button></li>',
            esc_attr($slug),
            $is_default ? 'true' : 'false',
            esc_html($font['label']),
            esc_html($font['description'])
        );
    }

    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

/**
 * Enqueue reading font switcher script.
 */
function dh_enqueue_font_switcher_script() {
    wp_enqueue_script(
        'dh-theme-font-switcher',
        get_template_directory_uri() . '/js/theme-font-switcher.js',
        array(),
        defined('DH_THEME_VERSION') ? DH_THEME_VERSION : '0.17.0',
        true
    );

    $fonts = array();

    foreach (dh_get_reading_fonts() as $slug => $font) {
        $fonts[ $slug ] = array(
            'label'       => $font['label'],
            'description' => $font['description'],
        );
    }

    wp_localize_script(
        'dh-theme-font-switcher',
        'dhReadingFont',
        array(
            'defaultFont' => dh_get_default_reading_font(),
            'fonts'       => $fonts,
            'labels'      => array(
                'toggle' => __('Choose reading font', 'dh'),
            ),
        )
    );
}
add_action('wp_enqueue_scripts', 'dh_enqueue_font_switcher_script');
