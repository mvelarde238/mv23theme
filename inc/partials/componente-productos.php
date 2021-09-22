<?php
$tipo = $componente['__type'];
$posts_grid_style = $componente['posts_grid_style'];
$posts_population = $componente['posts_population'];

$args_query = array( 
    'post_type' => 'product',
);

if ($posts_population == 'manual') {
	$postss = $componente['products'];
	$posts_ids = array();
	foreach ($postss as $post) {
	    array_push($posts_ids, str_replace('post_','',$post) );
	};
	$args_query['post__in'] = $posts_ids;
	$args_query['orderby'] = 'post__in';

} else if ($posts_population == 'auto') {
	$category = $componente['category'];
	$qty = $componente['posts_population_qty'];

	$args_query['posts_per_page'] = $qty;
	$args_query['tax_query'] = array(
	    array (
	        'taxonomy' => 'product_cat',
	        'terms' => $category,
	    )
	);

} else {
	$postss = array();
}
?>
<div class="componente <?=$tipo?> <?=$posts_grid_style?>">
	<?php
	$query = new WP_Query( $args_query ); 

	if ($query->have_posts()) :
	    echo '<div class="products-listing">';
			while ( $query->have_posts() ) : $query->the_post();
				get_template_part( 'inc/partials/minipost', 'product' );
			endwhile;
		echo '</div>';
	endif;
	wp_reset_postdata();
	?>
</div> 