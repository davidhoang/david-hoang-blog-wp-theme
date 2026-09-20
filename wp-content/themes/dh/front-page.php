<?php
/**
 * Front page template for a static homepage.
 *
 * @package dh
 */

get_header();

get_template_part('template-parts/layout', 'start');

$featured_essay = dh_get_featured_essay();
$recent_essays  = dh_get_home_essays(4, $featured_essay ? $featured_essay->ID : 0);
$recent_photos  = dh_get_home_photos(4);
$posts_page_id  = (int) get_option('page_for_posts');
$essays_url     = $posts_page_id ? get_permalink($posts_page_id) : home_url('/blog/');
?>

            <main class="editorial-home">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php if (trim((string) get_the_content())) : ?>
                        <section class="editorial-home__intro">
                            <div class="entry-content"><?php the_content(); ?></div>
                        </section>
                    <?php endif; ?>
                <?php endwhile; ?>

                <?php if ($featured_essay) : ?>
                    <?php
                    $post = $featured_essay; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                    setup_postdata($post);
                    ?>
                    <section class="home-feature" aria-labelledby="home-feature-title">
                        <p class="editorial-section__eyebrow"><?php esc_html_e('Featured essay', 'dh'); ?></p>
                        <div class="home-feature__grid">
                            <div class="home-feature__copy">
                                <h1 class="home-feature__title" id="home-feature-title">
                                    <a href="<?php the_permalink(); ?>"><?php echo esc_html(dh_get_display_title()); ?></a>
                                </h1>
                                <div class="home-feature__meta"><?php dh_the_entry_dateline(); ?></div>
                                <div class="home-feature__summary"><?php the_excerpt(); ?></div>
                                <a class="editorial-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read the essay', 'dh'); ?> <span aria-hidden="true">&rarr;</span></a>
                            </div>
                            <?php if (has_post_thumbnail()) : ?>
                                <a class="home-feature__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                    <?php the_post_thumbnail('large', array('loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 768px) 92vw, 48vw')); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </section>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>

                <?php if ($recent_essays) : ?>
                    <section class="editorial-section" aria-labelledby="recent-essays-title">
                        <div class="editorial-section__header">
                            <h2 id="recent-essays-title"><?php esc_html_e('Recent writing', 'dh'); ?></h2>
                            <a href="<?php echo esc_url($essays_url); ?>"><?php esc_html_e('All essays', 'dh'); ?></a>
                        </div>
                        <ol class="home-essay-list">
                            <?php foreach ($recent_essays as $post) : ?>
                                <?php setup_postdata($post); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span><?php echo esc_html(dh_get_display_title()); ?></span>
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('M j, Y')); ?></time>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                        <?php wp_reset_postdata(); ?>
                    </section>
                <?php endif; ?>

                <?php if ($recent_photos) : ?>
                    <section class="editorial-section" aria-labelledby="recent-photos-title">
                        <div class="editorial-section__header">
                            <h2 id="recent-photos-title"><?php esc_html_e('Recent photographs', 'dh'); ?></h2>
                            <a href="<?php echo esc_url(get_post_type_archive_link('photo')); ?>"><?php esc_html_e('Photo archive', 'dh'); ?></a>
                        </div>
                        <div class="photo-grid photo-grid--home">
                            <?php foreach ($recent_photos as $post) : ?>
                                <?php setup_postdata($post); ?>
                                <?php get_template_part('template-parts/photo', 'card'); ?>
                            <?php endforeach; ?>
                        </div>
                        <?php wp_reset_postdata(); ?>
                    </section>
                <?php endif; ?>
            </main>

<?php
get_template_part('template-parts/layout', 'end');
get_footer();
