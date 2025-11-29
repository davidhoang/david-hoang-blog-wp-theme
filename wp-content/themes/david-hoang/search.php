<?php
/**
 * The template for displaying search results
 *
 * @package DavidHoang
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="site-container">
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('Search Results for: %s', 'david-hoang'),
                        '<span>' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            </header>
            
            <?php
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', 'search');
            endwhile;
            
            the_posts_pagination();
        else :
            ?>
            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Nothing Found', 'david-hoang'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'david-hoang'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </section>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
