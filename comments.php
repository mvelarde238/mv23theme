<?php
if (post_password_required()) return;
?>
<?php if (have_comments()) : ?>
    <div id="comments" class="comments-area">
        <h4 id="comments-title"><?php comments_number(__('0 comentarios', 'default'), __('Un comentario', 'default'), __('<span>%</span> comentarios', 'default')); ?></h4>

        <section class="commentlist">
            <?php
            function mv23_comments($comment, $args, $depth)
            {
                $GLOBALS['comment'] = $comment; ?>
                <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
                    <article class="cf">
                        <header class="comment-author">
                            <?php echo get_avatar($comment, $size = '32', ''); ?>

                            <?php printf(__('<cite class="fn">%1$s</cite> %2$s', 'default'), get_comment_author_link(), edit_comment_link(__('(Edit)', 'default'), '  ', '')) ?>
                            <time class="comment-date" datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php comment_time(__('F jS, Y', 'default')); ?> </a></time>
                        </header>

                        <section class="comment-content cf">
                            <?php if ($comment->comment_approved == '0') : ?>
                                <p class="alert-info"><?php _e('Gracias, tu comentario será publicado cuando sea aprobado.', 'default') ?></p>
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
                'reply_text'        => __('Responder', 'default'),
                'page'              => '',
                'per_page'          => '',
                'reverse_top_level' => null,
                'reverse_children'  => ''
            ));
            ?>
        </section>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="navigation comment-navigation" role="navigation">
                <div class="comment-nav-prev"><?php previous_comments_link(__('&larr; Comentarios anteriores', 'default')); ?></div>
                <div class="comment-nav-next"><?php next_comments_link(__('Mas comentarios &rarr;', 'default')); ?></div>
            </nav>
        <?php endif; ?>

        <?php if (!comments_open()) : ?>
            <p class="no-comments center"><strong><?php _e('Se han bloqueado los comentarios para esta publicación.', 'default'); ?></strong></p>
        <?php endif; ?>

    </div>
<?php endif; ?>

<?php comment_form(); ?>