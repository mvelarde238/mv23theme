<?php
$title = get_the_title();
$post_id = get_the_ID();
$link = get_the_permalink( $post_id );

$product_card = get_post_meta($post_id,'product_card',true);
$secondary_image = wp_get_attachment_url(get_post_meta($post_id,'secondary_image',true));
$main_image = get_the_post_thumbnail_url( $post_id, 'large' );
?>
<div class="wb-product-card">
	<div class="wb-product-card__secondary-image" style="background-image: url(<?=$secondary_image?>);">
		<div class="wb-product-card__image">
			<?php if($main_image) echo '<img src="'.$main_image.'" >'; ?>
		</div>
		<div class="wb-product-card__content">
			<?php if($product_card) echo apply_filters('the_content', $product_card); ?>
		</div>		
	</div>
	<div class="wb-product-card__footer">
		<a href="!#" class="btn wb-product-card__share"><i class="fa fa-share"></i> COMPARTIR</a>
	</div>
	<div class="share-wrapper"><?php get_template_part('inc/modulos/socialshare'); ?></div>
</div>