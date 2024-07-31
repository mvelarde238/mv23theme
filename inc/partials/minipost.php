<?php
global $post;
$id = $post->ID;
$title = $post->post_title;
$link = get_the_permalink($id);
$imagen = get_the_post_thumbnail_url( $id, 'full' );
$thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
$tags = get_the_tags($id);

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
<div class="post-card post-card--style1" data-id="<?=$id?>">
	<a href="<?=$link?>" class="post-card__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);"></a>
	<div class="post-card__content">
		<h2 class="post-card__title"><a class="trigger-post-action" href="<?=$link?>"><?php echo $title; ?></a></h2>
		<p class="post-card__date"><?php printf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $id) . '</time>'); ?></p>
		<p><?php echo do_shortcode(wpautop($excerpt)) ?></p>
		<a class="btn btn--main-color trigger-post-action" href="<?=$link?>">Leer más</a>
		<div class="post-card__tags">
			<?php  
			if( is_array($tags) && count($tags) > 0 ){
				foreach ($tags as $tag ) {
					echo '<span class="'.$tag->slug.'">'.$tag->name.'</span>';
				}
			}
			?>
		</div>
	</div>
</div>