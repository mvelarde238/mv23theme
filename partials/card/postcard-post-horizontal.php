<?php
use Core\Frontend\Post_Card;

global $post;
$id = $post->ID;
$title = $post->post_title;
$link = Post_Card::get_permalink($post);
$excerpt = Post_Card::get_excerpt($post, 110);
$thumb_url = Post_Card::get_thumbnail($post);
$postcard_attributes = Post_Card::build_attributes($post, $args);
$main_terms = Post_Card::get_main_taxonomy($post);
$tags = Post_Card::get_secondary_taxonomy($post);
$featured_video = Post_Card::get_featured_video($post);
?>
<div class="postcard postcard--style2" <?php echo $postcard_attributes ?>>
	<div class="postcard__content-wrapper">
		<a href="<?=$link?>" class="postcard__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);">
			<?php if($featured_video) echo $featured_video; ?>
		</a>
		<div class="postcard__content">
			<h2 class="postcard__title"><a class="trigger-post-action" href="<?=$link?>"><?php echo $title; ?></a></h2>
			<div class="postcard__postdata">
				<p class="truncate">
					<span class="postcard__date"><?php echo Post_Card::display_date($post); ?></span>

					<?php if (is_array($main_terms) && count($main_terms) > 0) {
						echo ' | <span class="postcard__categories">';
            			echo Post_Card::display_terms($main_terms,',');
						echo '</span>';
        			} ?>
				</p>

				<?php if( is_array($tags) && count($tags) > 0 ){
					echo '<p class="postcard__tags text-color-2">';
					echo Post_Card::display_terms($tags);
					echo '</p>';
				} ?>
			</div>
			<?php if($excerpt) echo '<div class="postcard__excerpt">'.$excerpt.'</div>'; ?>
			<div class="postcard__link">
				<a class="trigger-post-action" href="<?=$link?>"><?php _e('Read More','mv23theme') ?> →</a>
			</div>
		</div>
	</div>
</div>