<?php
use Core\Frontend\Post_Card;

global $post;
$id = $post->ID;
$title = $post->post_title;
$link = Post_Card::get_permalink($post);
$excerpt = Post_Card::get_excerpt($post, 110);
$thumb_url = Post_Card::get_thumbnail($post, 'medium');
$postcard_attributes = Post_Card::build_attributes($post, $args);
$main_terms = Post_Card::get_main_taxonomy($post);
$tags = Post_Card::get_secondary_taxonomy($post);
$featured_video = Post_Card::get_featured_video($post);
?>
<div class="postcard postcard--style3" <?php echo $postcard_attributes ?>>
    <div class="postcard__wrapper">
        <a href="<?=$link?>" class="postcard__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);">
            <?php if($featured_video) echo $featured_video; ?>
        </a>
        <div class="postcard__content text-color-2">
            <h2 class="postcard__title"><?php echo $title; ?></h2>
            <span>→</span>
            <a class="cover-all trigger-post-action" href="<?=$link?>"></a>
        </div>
        <?php if( is_array($tags) && count($tags) > 0 ){
			echo '<div class="postcard__tags text-color-2">';
			echo Post_Card::display_terms($tags);
			echo '</div>';
		} ?>
    </div>
</div>