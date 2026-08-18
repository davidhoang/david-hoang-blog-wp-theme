<?php
/**
 * Reader experience enhancements for single posts.
 *
 * Adds stable heading anchors, an automatic table of contents for longer
 * posts, and enqueues the copy-to-clipboard helpers for headings and code
 * blocks.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Headings collected from the current post content, used to build the TOC.
 *
 * @var array<int, array{level:int,id:string,text:string}>
 */
$GLOBALS['dh_reader_headings'] = array();

/**
 * Whether the reader enhancements should run for the current request.
 *
 * @return bool
 */
function dh_reader_is_enabled() {
    return is_singular('post') && in_the_loop() && is_main_query();
}

/**
 * Minimum number of headings before a table of contents is shown.
 *
 * @return int
 */
function dh_reader_toc_min_headings() {
    return max(2, (int) apply_filters('dh_toc_min_headings', 3));
}

/**
 * Inject stable ids into post headings and collect them for the TOC.
 *
 * Runs after do_blocks()/wpautop() so block markup is already rendered.
 *
 * @param string $content Post content HTML.
 * @return string
 */
function dh_reader_enhance_content($content) {
    if (!dh_reader_is_enabled()) {
        return $content;
    }

    $GLOBALS['dh_reader_headings'] = array();
    $used_ids = array();

    $content = preg_replace_callback(
        '/<h([23])\b([^>]*)>(.*?)<\/h\1>/is',
        function ($matches) use (&$used_ids) {
            $level = (int) $matches[1];
            $attributes = $matches[2];
            $inner = $matches[3];
            $text = trim(wp_strip_all_tags($inner));

            if ('' === $text) {
                return $matches[0];
            }

            if (preg_match('/\sid=("|\')(.*?)\1/i', $attributes, $id_match)) {
                $id = $id_match[2];
            } else {
                $base = sanitize_title($text);
                if ('' === $base) {
                    $base = 'section';
                }

                $id = $base;
                $suffix = 2;
                while (isset($used_ids[$id])) {
                    $id = $base . '-' . $suffix;
                    $suffix++;
                }

                $attributes .= ' id="' . esc_attr($id) . '"';
            }

            $used_ids[$id] = true;

            $GLOBALS['dh_reader_headings'][] = array(
                'level' => $level,
                'id'    => $id,
                'text'  => $text,
            );

            return '<h' . $level . $attributes . '>' . $inner . '</h' . $level . '>';
        },
        $content
    );

    if (count($GLOBALS['dh_reader_headings']) >= dh_reader_toc_min_headings()) {
        $content = dh_reader_build_toc($GLOBALS['dh_reader_headings']) . $content;
    }

    return $content;
}
add_filter('the_content', 'dh_reader_enhance_content', 20);

/**
 * Build the table of contents markup from collected headings.
 *
 * @param array<int, array{level:int,id:string,text:string}> $headings Headings.
 * @return string
 */
function dh_reader_build_toc($headings) {
    if (empty($headings)) {
        return '';
    }

    $items = '';

    foreach ($headings as $heading) {
        $items .= sprintf(
            '<li class="dh-toc__item dh-toc__item--h%1$d"><a class="dh-toc__link" href="#%2$s">%3$s</a></li>',
            (int) $heading['level'],
            esc_attr($heading['id']),
            esc_html($heading['text'])
        );
    }

    return sprintf(
        '<nav class="dh-toc" aria-labelledby="dh-toc-title">' .
            '<h2 class="dh-toc__title" id="dh-toc-title">%1$s</h2>' .
            '<ol class="dh-toc__list">%2$s</ol>' .
            '</nav>',
        esc_html__('On this page', 'dh'),
        $items
    );
}

/**
 * Enqueue reader enhancement styles and scripts on single posts.
 */
function dh_reader_enqueue_assets() {
    if (!is_singular('post')) {
        return;
    }

    dh_enqueue_theme_style('dh-reader', 'reader.css', array('dh-single'));

    wp_enqueue_script(
        'dh-reader',
        get_template_directory_uri() . '/js/reader.js',
        array(),
        DH_THEME_VERSION,
        true
    );

    wp_localize_script('dh-reader', 'dhReader', array(
        'copyCode'   => __('Copy code', 'dh'),
        'copied'     => __('Copied', 'dh'),
        'copyFailed' => __('Copy failed', 'dh'),
        'copyLink'   => __('Copy link to this section', 'dh'),
        'linkCopied' => __('Link copied', 'dh'),
    ));
}
add_action('wp_enqueue_scripts', 'dh_reader_enqueue_assets', 15);

/**
 * Print the reading progress bar markup on single posts.
 */
function dh_reader_print_progress_bar() {
    if (!is_singular('post')) {
        return;
    }
    ?>
    <div
        class="dh-reading-progress"
        role="progressbar"
        aria-label="<?php esc_attr_e('Reading progress', 'dh'); ?>"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-valuenow="0"
        hidden
    >
        <div class="dh-reading-progress__bar"></div>
    </div>
    <?php
}
add_action('wp_body_open', 'dh_reader_print_progress_bar');

