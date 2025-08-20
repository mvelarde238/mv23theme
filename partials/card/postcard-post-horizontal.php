<?php
use Core\Frontend\Post_Card;

$postcard_args = array(
    'id' => $post->ID,
    'posttype' => $post->post_type,
    'title' => $post->post_title,
    'metadata' => array(),
    'permalink' => Post_Card::get_permalink($post),
    'permalink_text' => __('Read More','mv23theme'),
    'permalink_icon' => 'bi-arrow-up-right',
    'permalink_class' => 'trigger-post-action',
    'excerpt' => Post_Card::get_excerpt($post, 110),
    'thumbnail' => Post_Card::get_thumbnail($post, 'full'),
    'featured_video' => Post_Card::get_featured_video($post),
    'attributes' => Post_Card::build_attributes($post, $args),
    'main_terms' => Post_Card::get_main_taxonomy_terms($post),
    'tags' => Post_Card::get_secondary_taxonomy_terms($post),
    'date' => '<span class="postcard__date">'.Post_Card::display_date($post).'</span>',
    'viewer_icon' => 'bi-arrow-right',
    'style' => 'style2'
);

$_args = apply_filters( 'filter_postcard', $postcard_args, $post, $args );
?>
<div class="postcard postcard--style2" <?php echo $_args['attributes'] ?>>
	<div class="postcard__content-wrapper">
		<a href="<?=$_args['permalink']?>" class="postcard__image <?=$_args['permalink_class']?> style="background-image:url(<?=$_args['thumbnail']?>);">
			<?php if($_args['featured_video']) echo $_args['featured_video']; ?>
		</a>
		<div class="postcard__content">
			<h2 class="postcard__title">
				<a class="<?=$_args['permalink_class']?> href="<?=$_args['permalink']?>"><?php echo $_args['title']; ?></a>
			</h2>
			<div class="postcard__postdata">
				<p class="truncate">
					<?php echo $_args['date']; ?>

					<?php if (is_array($_args['main_terms']) && count($_args['main_terms']) > 0) {
						echo ' | <span class="postcard__categories">';
            			echo Post_Card::display_terms($_args['main_terms'],',');
						echo '</span>';
        			} ?>
				</p>

				<?php if( is_array($_args['tags']) && count($_args['tags']) > 0 ){
					echo '<p class="postcard__tags text-color-2">';
					echo Post_Card::display_terms($_args['tags']);
					echo '</p>';
				} ?>
			</div>
			<?php if($_args['excerpt']) echo '<div class="postcard__excerpt">'.$_args['excerpt'].'</div>'; ?>
			<div class="postcard__link">
				<a class="<?=$_args['permalink_class']?> href="<?=$_args['permalink']?>">
					<?php if( $_args['permalink_text'] ) echo $_args['permalink_text']; ?>
                    <?php if( $_args['permalink_icon'] ) echo ' <i class="bi '.$_args['permalink_icon'].'"></i>'; ?>
				</a>
			</div>
		</div>
	</div>
</div>