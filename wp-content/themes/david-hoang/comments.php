<?php
/**
 * The template for displaying comments
 *
 * @package DavidHoang
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php
    if (have_comments()) :
        ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            printf(
                esc_html(_n('%1$s Comment', '%1$s Comments', $comments_number, 'david-hoang')),
                number_format_i18n($comments_number)
            );
            ?>
            <span class="comments-title-post">
                <span class="comments-title-arrow">→</span>
                <span class="comments-title-post-name"><?php echo esc_html(get_the_title()); ?></span>
            </span>
        </h2>
        
        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'callback' => 'david_hoang_comment',
                'style' => 'ol',
            ));
            ?>
        </ol>
        
        <?php
        // Comment pagination
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
            ?>
            <nav id="comment-nav-below" class="comment-navigation">
                <div class="nav-previous">
                    <?php previous_comments_link(esc_html__('&larr; Older Comments', 'david-hoang')); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link(esc_html__('Newer Comments &rarr;', 'david-hoang')); ?>
                </div>
            </nav>
            <?php
        endif;
        
        if (!comments_open() && get_comments_number()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'david-hoang'); ?></p>
            <?php
        endif;
    endif;
    ?>
    
    <?php
    comment_form();
    ?>
</div>
