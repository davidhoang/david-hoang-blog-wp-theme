<?php
/**
 * 404 template.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/site-nav');
?>

<main id="main" class="site-main">
    <div class="site-layout">
        <div class="content-area">
            <section class="error-404 not-found">
                <header class="error-404__header">
                    <h1 class="error-404__title"><?php esc_html_e('Page not found', 'dh'); ?></h1>
                </header>

                <div class="error-404__content">
                    <p><?php esc_html_e('Nothing lives at this address. Try a search, or head back to the homepage.', 'dh'); ?></p>
                    <?php get_search_form(); ?>
                    <p class="error-404__home">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('← Back to homepage', 'dh'); ?></a>
                    </p>
                </div>
            </section>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
