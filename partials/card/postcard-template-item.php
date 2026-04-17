<?php
$post_id = get_the_ID();
$title = get_the_title($post_id);

$imagen = get_the_post_thumbnail_url( $post_id, 'large' );
$has_thumbnail = ($imagen) ? 'has-post-thumbnail' : '';
$thumbnail_style = ($imagen) ? 'style="background-image: url('.$imagen.');"' : '';
?>
<div class="templates-library__item-wrapper <?=$has_thumbnail?>">
	<input class="templates-library__control" type="radio" id="templates_library_<?=$post_id?>" name="templates_library_control">
	<div class="templates-library__item">
		<div>
			<label class="templates-library__label" for="templates_library_<?=$post_id?>"></label>
			<div class="templates-library__thumb" <?=$thumbnail_style?>></div>
			<p class="templates-library__title"><?php echo $title; ?></p>
			<div class="templates-library__footer">
				<span class="templates-library__author"><i class="bi bi-person"></i> <?php the_author(); ?></span>
				<span class="templates-library__date">
					<i class="bi bi-calendar3"></i> <?php printf( '%1$s', '<time class="entry-time" datetime="' . get_the_time('Y-m-d', 	$post_id) . '" itemprop="datePublished">' . get_the_time(get_option('date_format'), $post_id) . '</time>'); ?>
				</span>
			</div>
		</div>
	</div>
	<div class="templates-library__actions">
		<div>
			<button class="button templates-library-btn" data-id="<?=$post_id?>" data-action="delete">
				<i class="bi bi-trash"></i> <?php _e('Delete', 'mv23theme'); ?></button> 
			<button class="button button-secondary templates-library-btn" data-id="<?=$post_id?>" data-action="remove-thumbnail">
				<i class="bi bi-image"></i> <?php _e('Remove Featured Image', 'mv23theme'); ?></button>
			<button class="button button-secondary templates-library-btn" data-id="<?=$post_id?>" data-action="add-thumbnail">
				<i class="bi bi-image"></i> <?php _e('Add Featured Image', 'mv23theme'); ?></button>
		</div>
		<div>
			<button class="button button-primary templates-library-btn" data-id="<?=$post_id?>" data-action="insert">
				<i class="bi bi-plus"></i> <?php _e('Insert Template', 'mv23theme'); ?></button> 
		</div>
	</div>
</div>