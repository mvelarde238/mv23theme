<?php
$post_id = get_the_ID();
$title = get_the_title($post_id);

$imagen = get_the_post_thumbnail_url( $post_id, 'large' );
$has_thumbnail = ($imagen) ? 'has-post-thumbnail' : '';
$thumbnail_style = ($imagen) ? 'style="background-image: url('.$imagen.');"' : '';
?>
<div class="mv23-library__item-wrapper <?=$has_thumbnail?>">
	<input class="mv23-library__control" type="radio" id="mv23_library_<?=$post_id?>" name="mv23_library_control">
	<div class="mv23-library__item">
		<div>
			<label class="mv23-library__label" for="mv23_library_<?=$post_id?>"></label>
			<div class="mv23-library__imagen" <?=$thumbnail_style?>></div>
			<p class="mv23-library__title"><?php echo $title; ?></p>
			<div class="mv23-library__footer">
				<span class="mv23-library__author"><i class="fa fa-user"></i> <?php the_author(); ?></span>
				<span class="mv23-library__date"><i class="fa fa-calendar"></i> <?php printf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$post_id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $post_id) . '</time>'); ?></span>
			</div>
		</div>
	</div>
	<div class="mv23-library__actions">
		<div>
			<button class="button mv23-library-btn" data-id="<?=$post_id?>" data-action="delete"><span class="dashicons dashicons-trash uf-button-icon"></span> Eliminar</button> 
			<button class="button mv23-library-btn" data-id="<?=$post_id?>" data-action="remove-thumbnail">Quitar Imagen</button>
			<button class="button mv23-library-btn" data-id="<?=$post_id?>" data-action="add-thumbnail">Asignar Imagen</button>
		</div>
		<div>
			<button class="button button-primary mv23-library-btn" data-id="<?=$post_id?>" data-action="select"><span class="dashicons dashicons-plus uf-button-icon"></span> Agregar</button> 
		</div>
	</div>
</div>