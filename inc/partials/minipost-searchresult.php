<?php
$id = get_the_ID();
$link = get_the_permalink($id);
$imagen = get_the_post_thumbnail_url( $id, 'medium' );
$thumb_url = ($imagen) ? $imagen : get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
?>
<div class="search-result">
	<a href="<?=$link?>" class="search-result__thumb" style="background-image:url(<?=$thumb_url?>);"></a>
	<div class="search-result__content">
		<h5><a href="<?=$link?>"><?php the_title() ?></a></h5>
		<p><?php the_excerpt(); ?></p>
	</div>
</div>