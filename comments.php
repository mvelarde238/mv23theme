<?php
if (post_password_required()) return;
?>
<?php if (have_comments()) : ?>
    <div id="comments" class="comments-area">
        <h4 id="comments-title"><?php comments_number(__('0 comments', 'mv23theme'), __('One comment', 'mv23theme'), __('<span>%</span> comments', 'mv23theme')); ?></h4>

        <section class="commentlist">
            <?php
            function mv23_comments($comment, $args, $depth)
            {
                $GLOBALS['comment'] = $comment; ?>
                <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
                    <article class="cf">
                        <header class="comment-author">
                            <?php echo get_avatar($comment, $size = '32', ''); ?>
                            <?php 
                            /* translators: %1$s: comment author link, %2$s: comment link */
                            printf(__('<cite class="fn">%1$s</cite> %2$s', 'mv23theme'), get_comment_author_link(), edit_comment_link(__('(Edit)', 'mv23theme'), '  ', '')) ?>
                            <time class="comment-date" datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php comment_time(__('F jS, Y', 'mv23theme')); ?> </a></time>
                        </header>

                        <section class="comment-content cf">
                            <?php if ($comment->comment_approved == '0') : ?>
                                <p class="alert-info"><?php _e('Thank you, your comment will be published once approved.', 'mv23theme') ?></p>
                            <?php endif; ?>
                            <?php comment_text() ?>
                        </section>

                        <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                    </article>
                </div>
            <?php
            }

            wp_list_comments(array(
                'style'             => 'ol',
                'short_ping'        => true,
                'avatar_size'       => 40,
                'callback'          => 'mv23_comments',
                'type'              => 'all',
                'reply_text'        => __('Reply', 'mv23theme'),
                'page'              => '',
                'per_page'          => '',
                'reverse_top_level' => null,
                'reverse_children'  => ''
            ));
            ?>
        </section>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="navigation comment-navigation" role="navigation">
                <div class="comment-nav-prev"><?php previous_comments_link(__('&larr; Previous comments', 'mv23theme')); ?></div>
                <div class="comment-nav-next"><?php next_comments_link(__('More comments &rarr;', 'mv23theme')); ?></div>
            </nav>
        <?php endif; ?>

        <?php if (!comments_open()) : ?>
            <p class="no-comments center"><strong><?php _e('Comments are not allowed on this post.', 'mv23theme'); ?></strong></p>
        <?php endif; ?>

    </div>
<?php endif; ?>

<?php comment_form(); ?>