<?php
$title = get_the_title();
$id = get_the_ID();

$is_external_post = get_post_meta($id, 'external_post', true); 
$external_post = ($is_external_post) ? get_post_meta($id, 'external_post_link', true) : null; 
$link = ($external_post) ? $external_post : get_the_permalink($id);

$content = get_the_content($id);
$imagen = get_the_post_thumbnail_url( $id, 'medium' );
$thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
$categories = wp_get_post_categories($id);

$excerpt = get_the_excerpt($id);
$comment_length = 110;
$string = strip_tags($excerpt);
if (strlen($string) > $comment_length) {
    // truncate string
    $stringCut = substr($string, 0, $comment_length);
    $endPoint = strrpos($stringCut, ' ');

    $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
    $string .= '...';
}
?>
<div class="post-card post-card--style2" data-id="<?=$id?>">
	<a href="<?=$link?>" class="post-card__image expander-open" style="background-image:url(<?=$thumb_url?>);"></a>
	<div class="post-card__content">
		<h2 class="post-card__title"><a class="expander-open" href="<?=$link?>"><?php echo $title; ?></a></h2>
		<?php 
		if($excerpt) {
			echo '<div class="post-card__excerpt">'.$string.'</div>';
		} else {
			echo '<div class="post-card__excerpt">'.$content.'</div>';
		}
		?>
		<div class="post-card__link">
			<a class="btn btn--main-color expander-open" href="<?=$link?>">Leer más</a>
		</div>
	</div>
</div>