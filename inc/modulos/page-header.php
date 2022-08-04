<?php
$page_header = new Page_Header();
$page_ID = Page::getInstance()->get_id();
$key = Page::getInstance()->get_type();
$layout = $page_header->get_layout();
$page_header_element = $page_header->get_page_header_element();

if ($layout != 'layout3') echo '<div class="container">';
echo '<header '.$page_header->get_attributes().'>';
$page_header->print_video_background();
if ($page_header_element != 'ninguno' && $layout == 'layout2') echo '<div class="container">';

switch ($page_header_element) {
	case 'slider':
		$page_header->print_custom_content();
		break;
	
	case 'contenido':
		$page_header->print_custom_content();
		break;

	case 'ninguno':
		break;
		
	default:
		echo '<div class="componente">';
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
		echo '</div>';
}

if ($page_header_element != 'ninguno' && $layout == 'layout2') echo '</div>';
echo '</header>';
if ($layout != 'layout3') echo '</div>';