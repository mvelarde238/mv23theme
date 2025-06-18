<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Gallery extends Component {

    public function __construct() {
		parent::__construct(
			'gallery',
			__( 'Gallery', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-images-alt2';
    }

    public static function get_title_template() {
		$template = '<% if ( source == "manual" && gallery != "" || source == "wp-media" && wp_media_folder != 0 ){ %>
            <%= source %>
            <%= ( source == "wp-media" ) ? " | Folder ID: "+ wp_media_folder : ""  %>
            <%= ( source == "manual" ) ? " | Media IDs: "+ gallery : ""  %>
        <% } else { %>
            This component is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array();

        $sources = array(
            'manual' => 'Seleccionar imágenes'
        );
        if(WPMEDIAFOLDER_IS_ACTIVE) $sources = array_merge( array('wp-media' => 'Seleccionar folder'), $sources );
        
        // basic fields
        $fields[] = Field::create( 'tab', __('Content','mv23theme') );
        $fields[] = Field::create( 'radio', 'source', 'Fuente')->set_orientation('horizontal')->add_options( $sources )->set_width(100);
        $fields[] = Field::create( 'gallery', 'gallery' )->add_dependency('source', 'manual', '=')->hide_label()->set_width(100);
        
        if(WPMEDIAFOLDER_IS_ACTIVE) {
            // wp media fields
            $fields[] = Field::create( 'select', 'wp_media_folder' )->add_terms( 'wpmf-category' )->fancy()->set_width(25)->add_dependency('source', 'wp-media', '=');
            $fields[] = Field::create( 'message', 'wp_media_folder_message', 'Página de creación de galerías' )->set_description('<a href="'.admin_url().'upload.php" target="_blank">WP Media Folders</a>')->add_dependency('source', 'wp-media', '=')->set_width(70);    
        }
        
        // gallery settings
        $fields[] = Field::create( 'tab', 'Gallery Settings' );
        $gallery_settings = array(
            // Field::create( 'checkbox', 'autoinsert' )->set_text( '¿Autoinsertar las imágenes agregadas a la galerîa?' ), // the shortcode needs the attachments id's
            Field::create( 'select', 'display', 'Tipo')->add_options( array(
                'default' => 'Default',
                'slider' => 'Slider',
                'masonry' => 'Masonry',
                // 'porfolio' => 'Portfolio'
            ))->set_width(14),
            Field::create( 'number', 'columns', 'Columnas' )->set_default_value(4)->set_width(10),
            Field::create( 'select', 'link', 'Acción al hacer click')->add_options( array(
                'none' => 'Ninguna',
                'file' => 'Mostrar en Pop-up',
                'post' => 'Página de imágen',
                'custom' => 'Link Personalizado'
            ))->set_default_value('file')->set_width(14),
            Field::create( 'select', 'size', 'Image size')->add_options( array(
                'thumbnail' => 'Thumbnail',
                'medium' => 'Medium',
                'large' => 'Large',
                'full' => 'Full',
            ))->set_default_value('large')->set_width(14),
            Field::create( 'select', 'targetsize', 'Lightbox size')->add_options( array(
                'thumbnail' => 'Thumbnail',
                'medium' => 'Medium',
                'large' => 'Large',
                'full' => 'Full',
            ))->set_default_value('full')->add_dependency('link','file','=')->set_width(14),
            // Field::create( 'select', 'orderby', 'Ordenar por')->add_options( array(
            //     'custom' => 'Personalizado',
            //     'rand' => 'Random',
            //     'title' => 'Tìtulo',
            //     'date' => 'Fecha'
            // ))->add_dependency('../wp_media_folder','0','!=')->set_width(14),
            // Field::create( 'select', 'order', 'Orden')->add_options( array(
            //     'DESC' => 'Descendente',
            //     'ASC' => 'Ascendente',
            // ))->set_width(14),
        );
        
        if( !MASONRY_IS_ACTIVE ){
            $gallery_settings[] = Field::create( 'message', 'masonry_message', __('Activate Masonry') )->set_description('You have to activate masonry gallery to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate Masonry Gallery</a>')->add_dependency('display', 'masonry', '=')->set_attr( 'style', 'background:#470e0e;color:#fff;width:100%;' );;
        }
        $fields[] = Field::create( 'complex', 'wp_media_folder_settings', 'Settings' )->add_fields( $gallery_settings )->set_layout('rows')->hide_label();
        
        // images settings
        $fields[] = Field::create( 'tab', 'Images Settings' );
        $fields[] = Field::create( 'image_select', 'aspect_ratio', __('Images aspect ratio','mv23theme') )->add_options(array(
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
                'default' => array(
                    'label' => 'default',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-default-b.png'
                ),
            ));
        $fields[] = Field::create( 'checkbox', 'force_fullwidth_images', __('Force fullwidth images','mv23theme') )->set_text( 'Activar' );
        // ROW QUANTITY
        // $fields[] = Field::create( 'complex', 'items_in', __('Items visibles') )->add_fields(array(
        //     Field::create( 'number', 'l_items', __('Items on desktop','mv23theme') )->set_placeholder('4')->set_default_value('4')->set_width( 30 ),
        //     Field::create( 'number', 't_items', __('Items on tablet','mv23theme') )->set_placeholder('3')->set_default_value('3')->set_width( 30 ),
        //     Field::create( 'number', 'm_items', __('Items on mobile','mv23theme') )->set_placeholder('2')->set_default_value('2')->set_width( 30 )
        // ));
        // ITEMS GAP
        $fields[] = Field::create( 'complex', 'items_gap', __('Space between items') )->add_fields(array(
            Field::create( 'number', 'l_gap', __('Gap on desktop','mv23theme') )->set_placeholder('20')->set_default_value('20')->set_suffix('px')->set_width( 30 ),
            Field::create( 'number', 't_gap', __('Gap on tablet','mv23theme') )->set_placeholder('20')->set_default_value('20')->set_suffix('px')->set_width( 30 ),
            Field::create( 'number', 'm_gap', __('Gap on mobile','mv23theme') )->set_placeholder('20')->set_default_value('20')->set_suffix('px')->set_width( 30 )
        ));

        $fields[] = Field::create( 'tab', 'Advanced' );
        $fields[] = Field::create( 'text', 'gallery_id' )->set_width(30);
        $fields[] = Field::create( 'message', 'gallery_id_usage', 'Usar la siguiente clase para abrir la galería:' )->set_description('show-gallery--{gallery_id}')->add_dependency('gallery_id','','!=')->set_width(70);
        $fields[] = Field::create( 'checkbox', 'hide_gallery','Ocultar la galería' )->set_text( 'Activar' );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');
        $args['__type'] = 'theme-gallery-comp';

        $hide_gallery = ( isset($args['hide_gallery']) ) ? $args['hide_gallery'] : false;
        if($hide_gallery) $args['additional_classes'][] = 'hide';

        $source = $args['source'] ?? 'manual';
        $settings = $args['wp_media_folder_settings'] ?? array(
            'link' => 'file',
            'columns' => 4,
            'size' => 'large',
            'targetsize' => 'full',
            'display' => 'default'
        );
        $aspect_ratio = ( isset($args['aspect_ratio']) && $args['aspect_ratio'] != 'aspect-ratio-default' ) ? $args['aspect_ratio'] : 'aspect-ratio-default';
        $shortcode_name = ($source === 'manual') ? 'theme_gallery' : 'theme_gallery';
        $gallery_id = (isset($args['gallery_id'])) ? $args['gallery_id'] : '';

        $shortcode = '['.$shortcode_name.' link="'.$settings['link'].'" columns="'.$settings['columns'].'"  size="'.$settings['size'].'" targetsize="'.$settings['targetsize'].'" aspectratio="'.$aspect_ratio.'" display="'.$settings['display'].'" gallery_id="'.$gallery_id.'"';

        if($source == 'wp-media'){
        	$wp_media_folder = $args['wp_media_folder'] ?? 0;
        	if($wp_media_folder){
        		$shortcode .= ' wpmf_folder_id="'.$wp_media_folder.'" wpmf_autoinsert="1"';
        	}
        } else {
        	$gallery = $args['gallery'];
        	$ids = implode(',',$gallery);
        	$shortcode .= ' ids="'.$ids.'"';
        }

        if(isset($args['items_gap'])){
            $shortcode .= ' d_gap="'.$args['items_gap']['l_gap'].'px"';
            $shortcode .= ' l_gap="'.$args['items_gap']['l_gap'].'px"';
            $shortcode .= ' t_gap="'.$args['items_gap']['t_gap'].'px"'; 
            $shortcode .= ' m_gap="'.$args['items_gap']['m_gap'].'px"';
        }

        $force_fullwidth_images = ( isset($args['force_fullwidth_images']) && $args['force_fullwidth_images'] ) ? '1' : '0';
        $shortcode .= ' force_fullwidth_images="'.$force_fullwidth_images.'"';

        $shortcode .= ']';

        $additional_styles = array();
        if($aspect_ratio != 'mv23theme') $additional_styles[] = '--aspect-ratio:'.$aspect_ratio;
        $args['additional_styles'] = $additional_styles;
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($shortcode) echo do_shortcode($shortcode);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Gallery();