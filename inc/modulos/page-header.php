<?php
$page_header = new Page_Header();
$page_ID = Page::getInstance()->get_id();
$key = Page::getInstance()->get_type();
$layout = $page_header->get_layout();
$page_header_element = $page_header->get_page_header_element();

if ($page_header->get_page_header_element() != 'ninguno') echo '<div class="container">';

switch ($page_header_element) {
	case 'slider':
		$mobile_key = (constant('IS_MOBILE')) ? 'movil' : 'desktop';
		$slider = get_metadata($key,$page_ID,'slider_'.$mobile_key, true);
		if($slider) { ?>
			<header <?php echo $page_header->get_attributes() ?>>
				<?php if ($layout != 'layout3') echo '<div class="container">'; ?>
					<?php echo do_shortcode($slider); ?>
				<?php if ($layout != 'layout3') echo '</div>'; ?>
			</header>
		<?php }
		break;
	
	case 'contenido':
		$page_header_content = get_metadata($key,$page_ID,'page_header_content', true);
		?>
		<header <?php echo $page_header->get_attributes() ?>>
			<?php if ($page_header_content) : ?>
				<?php if ($layout != 'layout3') echo '<div class="container">'; ?>
					<div class="componente">
						<?php if($page_header_content) echo do_shortcode(wpautop($page_header_content)); ?>
					</div>
				<?php if ($layout != 'layout3') echo '</div>'; ?>
			<?php endif; ?> 
		</header>
		<?php
		break;

	case 'ninguno':
		break;
		
	default: ?>
		<header <?php echo $page_header->get_attributes() ?>>
			<?php if ($layout != 'layout3') echo '<div class="container">'; ?>
			<?php if (is_archive()) :
				the_archive_title( '<h1>', '</h1>' );
			elseif( is_search() ):
				$searchkey = $_GET['s'];
				echo '<h1 class="center">Resultados de búsqueda para: '.$searchkey.'</h1>';
			elseif( is_404() ):
				echo '<h1>Not Found</h1>';
			else:
				echo '<h1>'.get_the_title( $page_ID ).'</h1>';
			endif; ?>
			<?php if ($layout != 'layout3') echo '</div>'; ?>
		</header>
		<?php
}

if ($page_header_element != 'ninguno') echo '</div>';