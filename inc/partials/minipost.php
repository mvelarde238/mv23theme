<?php
$title = get_the_title();
$id = get_the_ID();
$link = get_the_permalink($id);
$excerpt = get_the_excerpt($id);
$imagen = get_the_post_thumbnail_url( $id, 'full' );
$thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
$tags = get_the_tags($id);
?>
<div class="post-card post-card--style1" data-id="<?=$id?>">
	<a href="<?=$link?>" class="post-card__image trigger-post-action" style="background-image:url(<?=$thumb_url?>);"></a>
	<div class="post-card__content">
		<h2 class="post-card__title"><a class="trigger-post-action" href="<?=$link?>"><?php echo $title; ?></a></h2>
		<p class="post-card__date"><?php printf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $id) . '</time>'); ?></p>
		<p><?php echo do_shortcode(wpautop($excerpt)) ?></p>
		<a class="btn btn--main-color trigger-post-action" href="<?=$link?>">Leer más</a>
	</div>
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