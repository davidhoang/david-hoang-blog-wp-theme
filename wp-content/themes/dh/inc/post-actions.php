<?php
/**
 * Sharing and subscription actions for single posts.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Proof of Concept subscription call-to-action settings.
 *
 * @return array{enabled: bool, title: string, text: string, url: string, label: string}
 */
function dh_get_subscribe_cta() {
    return array(
        'enabled' => (bool) get_theme_mod('dh_subscribe_enabled', true),
        'title'   => get_theme_mod('dh_subscribe_title', __('Keep reading with Proof of Concept', 'dh')),
        'text'    => get_theme_mod('dh_subscribe_text', __('Essays on design, technology, and entrepreneurship, delivered by email.', 'dh')),
        'url'     => get_theme_mod('dh_subscribe_url', 'https://www.proofofconcept.pub/'),
        'label'   => get_theme_mod('dh_subscribe_label', __('Subscribe', 'dh')),
    );
}

/**
 * Render sharing controls and the optional newsletter CTA.
 */
function dh_render_post_actions() {
    $cta = dh_get_subscribe_cta();
    ?>
    <div class="post-actions">
        <section class="post-share" aria-labelledby="post-share-title">
            <h2 class="post-actions__eyebrow" id="post-share-title"><?php esc_html_e('Share this post', 'dh'); ?></h2>
            <div class="post-share__controls">
                <button
                    class="post-share__button"
                    type="button"
                    data-dh-native-share
                    data-url="<?php echo esc_url(get_permalink()); ?>"
                    data-title="<?php echo esc_attr(dh_get_display_title()); ?>"
                    hidden
                >
                    <?php esc_html_e('Share', 'dh'); ?>
                </button>
                <button
                    class="post-share__button"
                    type="button"
                    data-dh-copy-link
                    data-url="<?php echo esc_url(get_permalink()); ?>"
                >
                    <?php esc_html_e('Copy link', 'dh'); ?>
                </button>
                <span class="post-share__status" data-dh-share-status role="status" aria-live="polite"></span>
            </div>
        </section>

        <?php if ($cta['enabled'] && $cta['url']) : ?>
            <aside class="subscribe-cta" aria-labelledby="subscribe-cta-title">
                <div>
                    <p class="post-actions__eyebrow"><?php esc_html_e('Proof of Concept', 'dh'); ?></p>
                    <h2 class="subscribe-cta__title" id="subscribe-cta-title"><?php echo esc_html($cta['title']); ?></h2>
                    <?php if ($cta['text']) : ?>
                        <p class="subscribe-cta__text"><?php echo esc_html($cta['text']); ?></p>
                    <?php endif; ?>
                </div>
                <a class="subscribe-cta__link" href="<?php echo esc_url($cta['url']); ?>">
                    <?php echo esc_html($cta['label']); ?>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </aside>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Enqueue progressive enhancement for sharing controls.
 */
function dh_post_actions_enqueue_assets() {
    if (!is_singular('post')) {
        return;
    }

    wp_enqueue_script(
        'dh-post-actions',
        get_template_directory_uri() . '/js/post-actions.js',
        array(),
        DH_THEME_VERSION,
        true
    );

    wp_localize_script('dh-post-actions', 'dhPostActions', array(
        'copied'     => __('Link copied', 'dh'),
        'copyFailed' => __('Could not copy the link', 'dh'),
    ));
}
add_action('wp_enqueue_scripts', 'dh_post_actions_enqueue_assets', 16);
