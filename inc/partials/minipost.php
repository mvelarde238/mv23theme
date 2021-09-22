<?php
global $wp_query;
$index = $wp_query->current_post + 1;

$title = get_the_title();
$id = get_the_ID();
$resumen = get_the_excerpt();
$link = get_the_permalink($id);
$imagen = get_the_post_thumbnail_url( $id, 'large' );
?>
<div class="post-card post-card--style1">
	<div class="post-card__imagen" style="background-image: url(<?=$imagen?>);"></div>
	<p class="post-card__title"><?php echo $title; ?></p>
	<p class="post-card__date"><i class="fa fa-calendar"></i> <?php printf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $id) . '</time>'); ?></p>
	<a class="post-card__link" href="<?=$link?>"></a>
</div>