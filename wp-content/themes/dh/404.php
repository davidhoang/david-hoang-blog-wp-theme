<?php
/**
 * 404 template.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
get_template_part('template-parts/layout', 'start');
?>

            <section class="error-404 not-found">
                <?php
                get_template_part(
                    'template-parts/page',
                    'header',
                    array(
                        'title' => __('Page not found', 'dh'),
                    )
                );
                ?>

                <div class="error-404__content">
                    <p><?php esc_html_e('Nothing lives at this address. Try a search, or head back to the homepage.', 'dh'); ?></p>
                    <?php get_search_form(); ?>
                    <p class="error-404__home">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('← Back to homepage', 'dh'); ?></a>
                    </p>
                </div>
            </section>

            <?php dh_render_recovery_paths(); ?>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
