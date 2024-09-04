<?php
use Theme_Custom_Fields\Template_Engine\Video;

global $post;
$id = $post->ID;
$title = $post->post_title;

$is_external_post = get_post_meta($id, 'external_post', true); 
$external_post = ($is_external_post) ? get_post_meta($id, 'external_post_link', true) : null; 
$link = ($external_post) ? $external_post : get_the_permalink($id);

$imagen = get_the_post_thumbnail_url( $id, 'medium' );
$thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';

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

$post_format = get_post_meta( $id, 'post_format', true );
$post_link = get_post_meta( $id, 'post_link', true );
if( $post_format == 'link' && !empty($post_link) ) $link = $post_link;

$use_featured_video = get_post_meta( $id, 'use_featured_video', true );
$featured_video = null;
if( $use_featured_video ){
	$featured_video_source = get_post_meta( $id, 'featured_video_source', true );
	
	$args = array(
		'video_source' => $featured_video_source,
		'video_type' => '',
		'loop' => 1,
        'muted' => 1,
        'autoplay' => 1
	);

	$video_meta_data = ( $featured_video_source == 'selfhosted' ) ? 'featured_video' : 'featured_video_url';
	$video_data = get_post_meta( $id, $video_meta_data, true );
	if( $featured_video_source == 'selfhosted' ) $args['video'] = $video_data;
	if( $featured_video_source == 'external' ) $args['external_url'] = $video_data;

	$featured_video = Video::get_video_data($args);
}
?>
<div class="post-card post-card--style2" data-id="<?=$id?>">
	<a href="<?=$link?>" class="post-card__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);">
		<?php if($featured_video) echo '<div class="video-background">'.$featured_video['code'].'</div>' ?>
	</a>
	<div class="post-card__content">
		<h2 class="post-card__title"><a class="trigger-post-action" href="<?=$link?>"><?php echo $title; ?></a></h2>
		<?php if($excerpt) echo '<div class="post-card__excerpt">'.$excerpt.'</div>'; ?>
		<div class="post-card__link">
			<a class="btn btn--main-color trigger-post-action" href="<?=$link?>">Leer más</a>
		</div>
	</div>
</div>