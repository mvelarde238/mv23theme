<?php
$id = get_the_ID();
$title = get_the_title($id);

$imagen = get_the_post_thumbnail_url( $id, 'large' );
$has_thumbnail = ($imagen) ? 'has-post-thumbnail' : '';
$thumbnail_style = ($imagen) ? 'style="background-image: url('.$imagen.');"' : '';
?>
<div class="components-library__item">
	<input class="components-library__radio" type="radio" id="component_library_<?=$id?>" name="component_library">
	<div>
		<label class="components-library__label" for="component_library_<?=$id?>"></label>
		<div class="components-library__imagen <?=$has_thumbnail?>" <?=$thumbnail_style?>></div>
		<p class="components-library__title"><?php echo $title; ?></p>
		<div class="components-library__footer">
			<span class="components-library__author"><i class="fa fa-user"></i> <?php the_author(); ?></span>
			<span class="components-library__date"><i class="fa fa-calendar"></i> <?php printf( '%1$s','<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $id) . '</time>'); ?></span>
			<button>Usar</button> <button>Previsualizar</button> <button>Eliminar</button>
		</div>
	</div>
</div>