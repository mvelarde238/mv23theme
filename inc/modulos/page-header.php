<?php
$page_header = new Page_Header();
$page_ID = Page::getInstance()->get_id();
$key = Page::getInstance()->get_type();
$layout = $page_header->get_layout();
$page_header_element = $page_header->get_page_header_element();

if ($page_header->get_page_header_element() != 'ninguno') echo '<div class="container">';
echo '<header '.$page_header->get_attributes().'>';

$video_background = $page_header->get_video_background();
if($video_background){
	$videos = $video_background['files']['videos'];
	if(is_array($videos) && count($videos) > 0){
		// $poster = $video_background['files']['poster'];
		$video_url = wp_get_attachment_url($videos[0]);
		$opacity = $video_background['opacity'];
    	$video_style = ($opacity != 100) ? 'style="opacity:'.($opacity/100).';"' : ''; 
        echo '<video '.$video_style.' width="100%" autoplay loop muted="muted"><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
	}
}

switch ($page_header_element) {
	case 'slider':
		$mobile_key = (constant('IS_MOBILE')) ? 'movil' : 'desktop';
		$slider = get_metadata($key,$page_ID,'slider_'.$mobile_key, true);
		if($slider) {
			if ($layout != 'layout3') echo '<div class="container">';
				echo do_shortcode($slider);
			if ($layout != 'layout3') echo '</div>';
		}
		break;
	
	case 'contenido':
		$page_header_content = get_metadata($key,$page_ID,'page_header_content', true);
		if ($page_header_content) :
			if ($layout != 'layout3') echo '<div class="container">';
				echo '<div class="componente">';
				if($page_header_content) echo do_shortcode(wpautop($page_header_content));
				echo '</div>';
			if ($layout != 'layout3') echo '</div>';
		endif;
		break;

	case 'ninguno':
		break;
		
	default:
		if ($layout != 'layout3') echo '<div class="container">';
		if (is_archive()) :
			the_archive_title( '<h1>', '</h1>' );
		elseif( is_search() ):
			$searchkey = $_GET['s'];
			echo '<h1 class="center">Resultados de búsqueda para: '.$searchkey.'</h1>';
		elseif( is_404() ):
			echo '<h1>Not Found</h1>';
		else:
			echo '<h1>'.get_the_title( $page_ID ).'</h1>';
		endif;
		if ($layout != 'layout3') echo '</div>';
}

echo '</header>';
if ($page_header_element != 'ninguno') echo '</div>';