<?php
if (!function_exists('default_style_formats')) {
	function default_style_formats(){ 
		return array(  
			// array(  
			    // 'title' => 'Tipografía M PLUS Rounded',  
			    // 'inline' => 'span', 
			    // 'classes' => 'mplus-rounded',
			    // 'wrapper' => false,
			// ),
			array(
			    'title' => 'Titulo',
			    'block' => 'div',
			    'classes' => 'special-title-1',
			    'wrapper' => true,
			),
			array(  
			    'title' => 'Botones',
			    'items' => array(
			    	array(  
					    'title' => 'Botón Simple',  
					    'selector' => 'a',
					    'classes' => 'btn',
					),
					array(  
					    'title' => 'Botón Corporativo 1',  
					    'selector' => 'a',
					    'classes' => 'btn btn--main-color',
					),
					array(  
					    'title' => 'Botón Corporativo 2',  
					    'selector' => 'a',
					    'classes' => 'btn btn--secondary-color',
					),
					array(  
					    'title' => 'Botón Ancho Completo',  
					    'selector' => 'a',
					    'classes' => 'btn btn-block',
					)
			    )
			),
			array(  
			    'title' => 'Quitar Margen Inferior',  
			    'selector' => 'p, h1, h2, h3, h4, h5, h6, a, img',
			    'classes' => 'mb0'
			),
			array(  
			    'title' => 'Quitar Margen Superior',  
			    'selector' => 'p, h1, h2, h3, h4, h5, h6, a, img',
			    'classes' => 'mt0'
			),
			array(  
			    'title' => 'Clearfix',  
			    'selector' => 'p, h1, h2, h3, h4, h5, h6, a, img',
			    'classes' => 'clearfix'
			),
			array(  
			    'title' => 'Imagen Ancho Completo',  
			    'selector' => 'img',
			    'classes' => 'img-full-width'
			),
			array(  
			    'title' => 'Imagen Ancho Completo en móvil',  
			    'selector' => 'img',
			    'classes' => 'movil-img-full-width'
			),
			array(  
			    'title' => 'Imagen Circular',  
			    'selector' => 'img',
			    'classes' => 'circle'
			),
			array(  
			    'title' => 'Ocultar en tablet y móvil',  
			    'selector' => 'p, h1, h2, h3, h4, h5, h6, a, img',
			    'classes' => 'hide-on-med-and-down'
			),
			array(  
			    'title' => 'Ocultar en móvil',  
			    'selector' => 'p, h1, h2, h3, h4, h5, h6, a, img',
			    'classes' => 'hide-on-small-only'
			),
			array(
			    'title' => 'Texto grande',
			    'inline' => 'span',
			    'classes' => 'fit-text',
			    'wrapper' => false,
			),
			// array(
			//     'title' => 'Red Uppercase Text',
			//     'inline' => 'span',
			//     'styles' => array(
			//         'color' => '#ff0000',
			//         'fontWeight' => 'bold',
			//         'textTransform' => 'uppercase'
			//     )
			// )
		);
	}
}

/*
* Each array child is a format with it's own settings
* Notice that each array has title, block, classes, and wrapper arguments
* Title is the label which will be visible in Formats menu
* Block defines whether it is a span, div, selector, or inline style
* Classes allows you to define CSS classes
* Wrapper whether or not to add a new block-level element around any selected elements
*/
if (!function_exists('get_style_formats')) {
	function get_style_formats(){ 
		$theme_style_formats = default_style_formats();
		return $theme_style_formats; 
	}
}