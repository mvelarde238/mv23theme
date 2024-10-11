<?php
namespace Core\Admin;

use Core\Theme_Options\Theme_Options;

/*
* Callback functions to filter the MCE settings
* https://codex.wordpress.org/TinyMCE_Custom_Styles
* https://www.wpexplorer.com/wordpress-tinymce-tweaks/
*/

class TinyMCE{

    public function filter_buttons_in_first_row($mce_buttons) {
        array_push($mce_buttons, 'icon_mce_button' );
        return $mce_buttons;
    }

    public function filter_buttons_in_second_row($mce_buttons){
        array_unshift($mce_buttons, 'styleselect', 'fontsizeselect', 'fontselect');
        $mce_buttons[] = 'underline';
        $mce_buttons[] = 'alignjustify';
        $mce_buttons[] = 'table';
        return $mce_buttons;
    }

    public function add_font_sizes($initArray){
        $initArray['fontsize_formats'] = "10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 34px 35px 36px 37px 38px 39px 40px 41px 42px 43px 44px 45px 55px";
        return $initArray;
    }

    public function add_font_families($initArray){
		$_theme_fonts = array();
		$theme_options = Theme_Options::getInstance();
        $theme_fonts = $theme_options->get_theme_fonts();
        if( !empty($theme_fonts['names']) ){
            foreach ($theme_fonts['names'] as $font_name) {
				$_theme_fonts[$font_name] = $font_name;
			}
        }

        $fonts = array(
            'Andale Mono' => 'monospace',
            'Arial' => 'arial,helvetica,sans-serif',
            'Arial Black' => 'arial black,avant garde',
            'Book Antiqua' => 'book antiqua,palatino',
            'Comic Sans MS' => 'comic sans ms,sans-serif',
            'Courier New' => 'courier new,courier',
            'Georgia' => 'georgia,palatino',
            'Helvetica' => 'helvetica',
            'Impact' => 'impact,chicago',
            'Symbol' => 'symbol',
            'Tahoma' => 'tahoma,arial,helvetica,sans-serif',
            'Terminal' => 'terminal,monaco',
            'Times New Roman' => 'times new roman,times',
            'Trebuchet MS' => 'trebuchet ms,geneva',
            'Verdana' => 'verdana,geneva',
            'Webdings' => 'webdings',
            'Wingdings' => 'wingdings,zapf dingbats',
        );

        $fonts = array_merge($_theme_fonts, CUSTOM_TINYMCE_FONTS, $fonts);

        $fonts_str = '';
        foreach ($fonts as $key => $item) {
            $fonts_str .= $key . '=' . $item . ';';
        }

        $initArray['font_formats'] = rtrim($fonts_str, ';');
        return $initArray;
    }

    /*
    * Each array child is a format with it's own settings
    * Notice that each array has title, block, classes, and wrapper arguments
    * Title is the label which will be visible in Formats menu
    * Block defines whether it is a span, div, selector, or inline style
    * Classes allows you to define CSS classes
    * Wrapper whether or not to add a new block-level element around any selected elements
    */
    public function get_style_formats(){ 
		return array(  
			array(  
				'title' => 'Títulos',	
				'items' => array(
					array(
						'title' => 'Titulo con línea',
						'block' => 'div',
						'classes' => 'special-title-1',
						'wrapper' => true,
					),
					array(
						'title' => 'Titulo con fondo',
						'block' => 'div',
						'classes' => 'special-title-2',
						'wrapper' => true,
					),
					array(
						'title' => 'Titulo subrayado 1',
						'block' => 'div',
						'classes' => 'special-title-3',
						'wrapper' => true,
					),
					array(
						'title' => 'Titulo subrayado 2',
						'block' => 'div',
						'classes' => 'special-title-4',
						'wrapper' => true,
					),
				)
			),
			array(  
				'title' => 'Listas',
				'items' => array(
					array(  
						'title' => 'Theme List',  
						'selector' => 'ul, ol', 
						'classes' => 'theme-list',
					),
					array(  
						'title' => 'Lista Resaltada',  
						'selector' => 'ul, ol', 
						'classes' => 'special-list-1',
					)
				)
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
					    'title' => 'Botón Blanco',  
					    'selector' => 'a',
					    'classes' => 'btn btn--white',
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
			// array(
			//     'title' => 'Texto grande',
			//     'inline' => 'span',
			//     'classes' => 'fit-text',
			//     'wrapper' => false,
			// ),
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

    public function add_style_formats($initArray) {  
        // Define the style_formats array
        $style_formats = apply_filters( 'filter_style_formats', $this->get_style_formats());
        // Insert the array, JSON ENCODED, into 'style_formats'
        $initArray['style_formats'] = json_encode( $style_formats );  
        return $initArray;  
    }

    public function get_custom_colors(){
		$theme_options = Theme_Options::getInstance();
		$theme_colors = array();
		
        $options = array('primary_color','secondary_color','font_color','headings_color','colorpicker_palette');
		foreach ($options as $option_name) {
            if( $option_name != 'colorpicker_palette' ){
                $the_color = $theme_options->get_property($option_name);
                if( $the_color ) {
					$theme_colors[] = '"'.str_replace('#', '', $the_color).'"';
					$theme_colors[] = '"'.$option_name.'"';
				}
            } else {
                $colorpicker_palette = $theme_options->get_property('colorpicker_palette');
                if( is_array($colorpicker_palette) && !empty($colorpicker_palette) ){
                    foreach ($colorpicker_palette as $item) {
                        if( $item['color'] ){
							$theme_colors[] = '"'.str_replace('#', '', $item['color']).'"';
							$theme_colors[] = '"'.$option_name.'"';
						}
                    }
                }
            }
        }

		return implode(',',$theme_colors);
	}

    public function add_theme_colors($initArray) {  
        // set text color palette
        $default_colours = '"000000", "Black",
                          "993300", "Burnt orange",
                          "333300", "Dark olive",
                          "003300", "Dark green",
                          "003366", "Dark azure",
                          "000080", "Navy Blue",
                          "333399", "Indigo",
                          "333333", "Very dark gray",
                          "800000", "Maroon",
                          "FF6600", "Orange",
                          "808000", "Olive",
                          "008000", "Green",
                          "008080", "Teal",
                          "0000FF", "Blue",
                          "666699", "Grayish blue",
                          "808080", "Gray",
                          "FF0000", "Red",
                          "FF9900", "Amber",
                          "99CC00", "Yellow green",
                          "339966", "Sea green",
                          "33CCCC", "Turquoise",
                          "3366FF", "Royal blue",
                          "800080", "Purple",
                          "999999", "Medium gray",
                          "FF00FF", "Magenta",
                          "FFCC00", "Gold",
                          "FFFF00", "Yellow",
                          "00FF00", "Lime",
                          "00FFFF", "Aqua",
                          "00CCFF", "Sky blue",
                          "993366", "Red violet",
                          "FFFFFF", "White"';
    
        $custom_colours = $this->get_custom_colors(); 
    
        // build colour grid default+custom colors
        $initArray['textcolor_map'] = '['.$default_colours.','.$custom_colours.']';
         
        return $initArray;  
    }

    public function add_table_plugin($plugins) {
        $plugins['table'] = get_template_directory_uri() .'/assets/js/mce-table-plugin.min.js';
        return $plugins;
    }

    public function add_icon_plugin($plugins) {
        $plugins['icon_mce_button'] = get_template_directory_uri() .'/assets/js/mce-icon-button.js';
        return $plugins;
    }
}
