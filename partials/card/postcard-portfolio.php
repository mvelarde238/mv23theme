<?php
use Theme_Custom_Fields\Template_Engine\Video;

global $post;
$id = $post->ID;
$title = $post->post_title;
$link = get_the_permalink($id);
$imagen = get_the_post_thumbnail_url( $id, 'full' );
$thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
$tags = get_the_terms($id,'portfolio-tag');

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

$postcard_attributes = array( 'data-id="'.$id.'"'   );
if( !empty($args['on_click_post']) ) $postcard_attributes[] = 'data-action="'.$args['on_click_post'].'"';
if( !empty($args['on_click_scroll_to']) ) $postcard_attributes[] = 'data-scroll-to="'.$args['on_click_scroll_to'].'"';
?>
<div class="postcard postcard--style3" <?php echo implode(' ', $postcard_attributes) ?>>
    <div class="postcard__wrapper">
        <a href="<?=$link?>" class="postcard__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);">
            <?php if($featured_video) echo '<div class="video-background">'.$featured_video['code'].'</div>' ?>
        </a>
        <div class="postcard__content text-color-2">
            <h2 class="postcard__title"><?php echo $title; ?></h2>
            <span>→</span>
            <a class="cover-all trigger-post-action" href="<?=$link?>"></a>
        </div>
        <div class="postcard__tags text-color-2">
            <?php  
            if( is_array($tags) && count($tags) > 0 ){
                foreach ($tags as $tag ) {
                    echo '<span><a href="' . esc_attr( get_term_link( $tag->slug, 'portfolio-tag' ) ) . '" class="'.$tag->slug.'">' . __( $tag->name ) . '</a></span>';
                }
            }
            ?>
        </div>
    </div>
</div>