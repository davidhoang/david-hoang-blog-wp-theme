<?php
/**
 * Template part for displaying search results
 *
 * @package DavidHoang
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <header class="post-header">
        <h2 class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="post-meta">
            <?php echo david_hoang_posted_on(); ?>
        </div>
    </header>
    
    <div class="post-content">
        <?php the_excerpt(); ?>
    </div>
</article>
