<?php
/**
 * The template for displaying 404 pages
 *
 * @package DavidHoang
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="site-container">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('404 - Page Not Found', 'david-hoang'); ?></h1>
            </header>
            
            <div class="page-content">
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'david-hoang'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();
