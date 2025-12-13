<?php
use Core\Frontend\Post_Card;

global $post;

$postcard_args = array(
    'id' => $post->ID,
    'posttype' => $post->post_type,
    'title' => $post->post_title,
    'metadata' => array(),
    'permalink' => Post_Card::get_permalink($post),
    'permalink_text' => __('More details','mv23theme'),
    'permalink_icon' => 'bi-arrow-up-right',
    'permalink_class' => 'trigger-post-action',
    'excerpt' => Post_Card::get_excerpt($post, 110),
    'thumbnail' => Post_Card::get_thumbnail($post, 'full'),
    'featured_video' => Post_Card::get_featured_video($post),
    'attributes' => Post_Card::build_attributes($post, $args),
    'main_terms' => Post_Card::get_main_taxonomy_terms($post),
    'tags' => Post_Card::get_secondary_taxonomy_terms($post),
    'date' => '<div><p class="postcard__date">'.Post_Card::display_date($post).'</p></div>',
    'viewer_icon' => 'bi-eye',
    'style' => 'style3'
);

$_args = apply_filters( 'filter_postcard', $postcard_args, $post, $args );
?>
<div class="postcard postcard--style3" <?php echo $_args['attributes'] ?>>
    <div class="postcard__wrapper">
        <a href="<?=$_args['permalink']?>" class="postcard__image <?=$_args['permalink_class']?>" style="background-image:url(<?=$_args['thumbnail']?>);">
            <?php if($_args['featured_video']) echo $_args['featured_video']; ?>
        </a>
        <div class="postcard__content dark-mode">
            <h2 class="postcard__title"><?php echo $_args['title']; ?></h2>
            <span>→</span>
            <a class="cover-all <?=$_args['permalink_class']?>" href="<?=$_args['permalink']?>"></a>
        </div>
        <?php if( is_array($_args['tags']) && count($_args['tags']) > 0 ){
			echo '<div class="postcard__tags dark-mode">';
			echo Post_Card::display_terms($_args['tags']);
			echo '</div>';
		} ?>
    </div>
</div>