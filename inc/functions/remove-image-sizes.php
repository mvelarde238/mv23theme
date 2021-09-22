<?php
/**
 * Remove unused image sizes
 *
 * @return void
 */

add_action('init', 'remove_image_sizes');

function remove_image_sizes() {
	// remove_image_size('thumbnail');
	remove_image_size('medium');
	remove_image_size('medium_large');
	remove_image_size('large');
	remove_image_size('1536x1536');
	remove_image_size('2048x2048');
	remove_image_size('woocommerce_thumbnail');
	remove_image_size('woocommerce_single');
	remove_image_size('woocommerce_gallery_thumbnail');
	remove_image_size('shop_catalog');
	remove_image_size('shop_single');
	remove_image_size('shop_thumbnail');

	// echo '<pre>';
	// print_r(get_intermediate_image_sizes()); 
	// echo '</pre>';
}