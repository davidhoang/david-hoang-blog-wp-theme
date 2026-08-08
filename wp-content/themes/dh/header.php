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

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'dh'); ?></a>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="site-hero">
            <?php
            $dh_hero_image_url = dh_get_hero_image_url();
            $dh_hero_classes   = 'site-hero__shader';
            $dh_hero_style     = '';

            if ($dh_hero_image_url) {
                $dh_hero_classes .= ' site-hero__shader--has-image';
                $dh_hero_style = sprintf(
                    '--dh-hero-image: url("%s");',
                    esc_url($dh_hero_image_url)
                );
            }
            ?>
            <div
                class="<?php echo esc_attr($dh_hero_classes); ?>"
                data-dh-hero-shader
                <?php if ($dh_hero_image_url) : ?>
                    data-image-url="<?php echo esc_url($dh_hero_image_url); ?>"
                <?php endif; ?>
                <?php if ($dh_hero_style) : ?>
                    style="<?php echo esc_attr($dh_hero_style); ?>"
                <?php endif; ?>
                aria-hidden="true"
            ></div>

            <div class="site-hero__inner">
                <div class="site-hero__intro">
                    <?php if (has_custom_logo()) : ?>
                        <div class="site-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php elseif (is_front_page()) : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                        </h1>
                    <?php else : ?>
                        <p class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                        </p>
                    <?php endif; ?>

                    <?php if (is_front_page()) : ?>
                        <h2 class="site-description"><?php echo esc_html(dh_get_tagline()); ?></h2>
                    <?php else : ?>
                        <p class="site-description"><?php echo esc_html(dh_get_tagline()); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
