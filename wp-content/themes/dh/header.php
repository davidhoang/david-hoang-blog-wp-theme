<?php
/**
 * Header template.
 *
 * @package dh
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="site-header-inner">
            <?php if (is_front_page() && is_home()) : ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                </h1>
            <?php else : ?>
                <p class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                </p>
            <?php endif; ?>

            <?php if (is_front_page() && is_home()) : ?>
                <h2 class="site-description"><?php echo esc_html(dh_get_tagline()); ?></h2>
            <?php else : ?>
                <p class="site-description"><?php echo esc_html(dh_get_tagline()); ?></p>
            <?php endif; ?>

            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary menu', 'dh'); ?>">
                <?php dh_render_primary_menu(); ?>
            </nav>
        </div>
    </header>
