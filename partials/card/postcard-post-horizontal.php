<?php
use Core\Builder\Template_Engine\Video;

global $post;
$id = $post->ID;
$title = $post->post_title;
$posttype = $post->post_type;
$link = get_the_permalink($id);

// tags
$tag_name = ($posttype == 'post') ? 'post_tag' : 'portfolio-tag';
$tags = get_the_terms( $id, $tag_name );

// categories
$category_name = ($posttype == 'post') ? 'category' : 'portfolio-cat';
$categories = get_the_terms( $id, $category_name );

// image
$imagen = get_the_post_thumbnail_url( $id, 'medium' );
$thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';

// excerpt
$excerpt = ( !empty($post->post_excerpt) ) ? $post->post_excerpt : $post->post_content;
$comment_length = 110;
$excerpt = strip_tags($excerpt);
if (strlen($excerpt) > $comment_length) {
    // truncate the excerpt
    $excerptCut = substr($excerpt, 0, $comment_length);
    $endPoint = strrpos($excerptCut, ' ');
    $excerpt = $endPoint? substr($excerptCut, 0, $endPoint) : substr($excerptCut, 0);
    $excerpt .= '...';
}

// post format
$post_format = get_post_meta( $id, 'post_format', true );
$post_link = get_post_meta( $id, 'post_link', true );
if( $post_format == 'link' && !empty($post_link) ) $link = $post_link;

// featured video
$use_featured_video = get_post_meta( $id, 'use_featured_video', true );
if( $use_featured_video ){
	$featured_video_source = get_post_meta( $id, 'featured_video_source', true );
	$video_meta_data = ( $featured_video_source == 'selfhosted' ) ? 'featured_video' : 'featured_video_url';
	$video_data = get_post_meta( $id, $video_meta_data, true );

	$video_settings = array(
		'video_source' => $featured_video_source,
        'classes' => 'video-background',
        'controls' => false,
        'muted' => true,
        'autoplay' => true,
        'loop' => true,
        'bgc' => '#000'
    );
	
	if( $featured_video_source == 'selfhosted' ) $video_settings['video'] = $video_data;
	if( $featured_video_source == 'external' ) $video_settings['external_url'] = $video_data;
	
    $video_data = Video::get_video_data( $video_settings );
}

// data attributes
$postcard_attributes = array( 'data-id="'.$id.'"'   );
if( !empty($args['on_click_post']) ) $postcard_attributes[] = 'data-action="'.$args['on_click_post'].'"';
if( !empty($args['on_click_scroll_to']) ) $postcard_attributes[] = 'data-scroll-to="'.$args['on_click_scroll_to'].'"';
?>
<div class="postcard postcard--style2" <?php echo implode(' ', $postcard_attributes) ?>>
	<div class="postcard__content-wrapper">
		<a href="<?=$link?>" class="postcard__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);">
			<?php if($use_featured_video) echo $video_data['code']; ?>
		</a>
		<div class="postcard__content">
			<h2 class="postcard__title"><a class="trigger-post-action" href="<?=$link?>"><?php echo $title; ?></a></h2>
			<div class="postcard__postdata">
				<p class="truncate">
				<span class="postcard__date"><?php printf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $id) . '</time>'); ?></span>

				<?php if (is_array($categories) && count($categories) > 0) {
					echo ' | <span class="postcard__categories">';
            		$count = 0;
            		foreach ($categories as $c) {
                		$cat = get_category($c);
                		echo '<a href="' . esc_attr( get_tag_link( $cat->term_id ) ) . '">' . $cat->name . '</a>';
                		$count++;
                		if ($count < count($categories)) echo ', ';
            		}
					echo '</span>';
        		}
				?>
				</p>

				<p class="postcard__tags text-color-2">
					<?php if( is_array($tags) && count($tags) > 0 ){
						foreach ($tags as $tag ) {
							$background_color = get_term_meta($tag->term_id, 'background_color', true);
							$style = ($background_color) ? ' style="background-color:' . $background_color . ';"' : ' ';
							echo '<span class="'.$tag->slug.'"><a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">' . __( $tag->name ) . '</a></span>';
						}
					} ?>
				</p>
			</div>
			<?php if($excerpt) echo '<div class="postcard__excerpt">'.$excerpt.'</div>'; ?>
			<div class="postcard__link">
				<a class="trigger-post-action" href="<?=$link?>"><?php _e('Read More','mv23theme') ?> →</a>
			</div>
		</div>
	</div>
</div>