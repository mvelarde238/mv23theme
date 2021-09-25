<?php
$post_id = get_the_ID();
$title = get_the_title($post_id);

$imagen = get_the_post_thumbnail_url( $post_id, 'large' );
$has_thumbnail = ($imagen) ? 'has-post-thumbnail' : '';
$thumbnail_style = ($imagen) ? 'style="background-image: url('.$imagen.');"' : '';
?>
<div class="components-library__item-wrapper">
	<input class="components-library__control" type="radio" id="component_library_<?=$post_id?>" name="component_library_control">
	<div class="components-library__item">
		<div>
			<label class="components-library__label" for="component_library_<?=$post_id?>"></label>
			<div class="components-library__imagen <?=$has_thumbnail?>" <?=$thumbnail_style?>></div>
			<p class="components-library__title"><?php echo $title; ?></p>
			<div class="components-library__footer">
				<span class="components-library__author"><i class="fa fa-user"></i> <?php the_author(); ?></span>
				<span class="components-library__date"><i class="fa fa-calendar"></i> <?php printf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$post_id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $post_id) . '</time>'); ?></span>
			</div>
		</div>
	</div>
	<div class="components-library__actions">
		<button class="button button-primary components-library-btn" data-id="<?=$post_id?>" data-action="select" class="button"><span class="dashicons dashicons-plus uf-button-icon"></span> Agregar</button> 
		<!-- <button class="button">Previsualizar</button> <button class="button">Eliminar</button> -->
	</div>
</div>