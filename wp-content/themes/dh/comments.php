<?php
/**
 * Comments template.
 *
 * @package dh
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            if ('1' === $comments_number) {
                esc_html_e('1 Comment', 'dh');
            } else {
                printf(
                    esc_html(_n('%1$s Comment', '%1$s Comments', $comments_number, 'dh')),
                    esc_html(number_format_i18n($comments_number))
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'dh_comment',
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation(array(
            'prev_text' => esc_html__('Older Comments', 'dh'),
            'next_text' => esc_html__('Newer Comments', 'dh'),
        ));

        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'dh'); ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'          => esc_html__('Join the conversation', 'dh'),
        'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h2>',
        'comment_notes_before' => '<p class="comment-notes">' . esc_html__('Your email stays private. Required fields are marked.', 'dh') . '</p>',
        'label_submit'         => esc_html__('Post comment', 'dh'),
        'class_submit'         => 'submit comment-submit',
    ));
    ?>
</div>
