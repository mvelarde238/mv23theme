<?php
$mobile_key = (constant('IS_MOBILE')) ? 'movil' : 'desktop';
$key = (is_archive()) ? 'term' : 'post';

$page_ID;
if(is_home()) {
	$page_ID = get_option( 'page_for_posts' );
} else if (is_archive()) {
	$archive_page_id = archive_page()->get_archive_id();
	if (!empty($archive_page_id)) {
		$page_ID = $archive_page_id;
		$key = 'post';
	} else {
		if (is_post_type_archive()) {
			$page_ID = null;
		} else {
			$page_ID = get_queried_object()->term_id;
		}
	}
} else if (is_search()) {
	$page_ID = null;
} else {
	$page_ID = get_the_ID();
	if (get_post_type($page_ID) == 'seccion_reusable') $page_ID = null;
}

if ($page_ID) {
	$page_header_element = get_metadata($key, $page_ID,'page_header_element', true);
	$id = get_metadata($key,$page_ID,'page_header_id', true);
	$layout = get_metadata($key,$page_ID,'page_header_layout', true);
	$class = get_metadata($key,$page_ID,'page_header_class', true);
	$text_color_class = get_metadata($key,$page_ID,'page_header_text_color', true);
	$no_padding = ( get_metadata($key,$page_ID,'page_header_padding', true) == 1 ) ? 'no-padding' : '';
	$parallax = ( get_metadata($key,$page_ID,'page_header_bgi_parallax', true) == 1 ) ? 'parallax' : '';
	$page_header_bgi = get_metadata($key,$page_ID,'page_header_bgi', true);
	$bgc = get_metadata($key,$page_ID,'page_header_bgc', true);
} else {
	$page_header_element = 'archive-page-header';
	$id = '';
	$layout = 'layout2';
	$class = '';
	$text_color_class = PAGE_HEADER_TEXT_COLOR;
	$no_padding = '';
	$parallax = '';
	$page_header_bgi = null;
	$bgc = PAGE_HEADER_BGC;
}

$full_width_class = ($layout == 'layout2' || $layout == 'layout3') ? 'full-width' : '';
$clases = $class .' '. $full_width_class .' '. $no_padding .' '. $text_color_class . ' '.$parallax;

$bgi = ($page_header_bgi) ? wp_get_attachment_url( $page_header_bgi, true) : false;
$style = '';
$style .= ($bgc) ? 'background-color: '.$bgc.';' : '';
$style .= ($bgi) ? 'background-image: url('.$bgi.');' : '';

if ($page_header_element != 'ninguno') echo '<div class="container">';

switch ($page_header_element) {
	case 'slider':
		$slider = get_metadata($key,$page_ID,'slider_'.$mobile_key, true);
		if($slider) { ?>
			<header class="page-header <?=$clases?>" id="<?=$id?>" style="<?=$style?>">
				<?php if ($layout != 'layout3') echo '<div class="container">'; ?>
					<?php echo do_shortcode($slider); ?>
				<?php if ($layout != 'layout3') echo '</div>'; ?>
			</header>
		<?php }
		break;
	
	case 'contenido':
		$page_header_content = get_metadata($key,$page_ID,'page_header_content', true);

		if ($page_header_content) : ?>
			<header class="page-header <?=$clases?> page-header--contenido" id="<?=$id?>" style="<?=$style?>">
				<?php if ($layout != 'layout3') echo '<div class="container">'; ?>
					<div class="componente">
						<?php if($page_header_content) echo apply_filters('the_content', $page_header_content); ?>
					</div>
				<?php if ($layout != 'layout3') echo '</div>'; ?>
			</header>
			<?php 
		endif;
		break;

	case 'ninguno':
		break;
		
	default: ?>
		<header id="<?=$id?>" class="page-header page-header--default center-align <?=$clases?>" style="<?=$style?>">
			<?php if ($layout != 'layout3') echo '<div class="container">'; ?>
			<?php if (is_archive()) :
				the_archive_title( '<h1>', '</h1>' );
			elseif( is_search() ):
				$searchkey = $_GET['s'];
				echo '<h1 class="center">Resultados de búsqueda para: '.$searchkey.'</h1>';
			else:
				echo '<h1>'.get_the_title( $page_ID ).'</h1>';
			endif; ?>
			<?php if ($layout != 'layout3') echo '</div>'; ?>
		</header>
		<?php
}

if ($page_header_element != 'ninguno') echo '</div>';