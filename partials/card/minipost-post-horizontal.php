<?php
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
?>
<div class="post-card post-card--style2" data-id="<?=$id?>">
	<a href="<?=$link?>" class="post-card__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);"></a>
	<div class="post-card__content">
		<h2 class="post-card__title"><a class="trigger-post-action" href="<?=$link?>"><?php echo $title; ?></a></h2>
		<?php if($excerpt) echo '<div class="post-card__excerpt">'.$excerpt.'</div>'; ?>
		<div class="post-card__link">
			<a class="btn btn--main-color trigger-post-action" href="<?=$link?>">Leer más</a>
		</div>
	</div>
</div>