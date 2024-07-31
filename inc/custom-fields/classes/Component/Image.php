<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use Theme_Custom_Fields\Template_Engine\Video;

class Image extends Component {

    public function __construct() {
		parent::__construct(
			'Imágen',
			__( 'Image / Video', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-admin-media';
    }

	public static function get_title_template() {
		$template = '<% if( type == "video" ) { %>
            <% if( video_source == "external" ){ %>
                External video | <%= video_type %>: <%= external_url %>
            <% } else { %>
                Selfhosted video | <%= video_type %> | <%= aspect_ratio %> 
            <% } %>
        <% } else { %>
            <%= type %> | <%= aspect_ratio %> | Alignment: <%= alignment %>
        <% } %>';
		
		return $template;
	}

    public static function get_layout(){
        return 'table';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', 'Contenido' ),
            Field::create('radio', 'type', 'Seleccione el tipo de contenido:')->set_orientation('horizontal')->add_options(array(
                'image' => 'Imágen',
                'video' => 'Video'
            )),
            Field::create( 'image', 'image' )->add_dependency('type','image','='),
    
            Field::create( 'radio', 'video_source','Seleccione el origen del video:')->set_orientation( 'horizontal' )->add_options( array(
                'selfhosted' => 'Medios',
                'external' => 'Externo'
                ))->add_dependency('type', 'video', '=')->set_width(50),

            Field::create( 'embed', 'external_url', 'URL')->add_dependency('type', 'video', '=')->add_dependency('video_source','external','=')->set_width(50),
            Field::create( 'video', 'bgvideo', 'Video de Fondo' )->add_dependency('type','video','=')->add_dependency('video_source','selfhosted','=')->set_width(50),
    
            Field::create('radio', 'video_type', 'Formato:')->set_orientation('horizontal')->add_options(array(
                'playable' => 'Reproducible',
                'popable' => 'Abrir en Pop Up'
                ))
                ->add_dependency('type','video','=')
                ->add_dependency('video_source','selfhosted','=')
                // ->add_dependency('bgvideo','','NOT_NULL')
                ->add_dependency_group()
                ->add_dependency('type','video','=')
                ->add_dependency('video_source','external','=')
                ->add_dependency('external_url','','!='),
            
            Field::create( 'complex', 'video_settings' )->add_fields(array(
                Field::create( 'color', 'bgc', 'Color de Fondo' )->set_default_value('#000000')->set_width(20),
                Field::create( 'checkbox', 'autoplay', 'AutoPlay' )->set_text( 'Activar' )->set_width(20),
                Field::create( 'checkbox', 'muted', 'Muted' )->set_text( 'Activar' )->set_width(20),
                Field::create( 'checkbox', 'loop', 'Bucle' )->set_text( 'Activar' )->set_width(20),
                Field::create( 'number', 'opacity', 'Transparencia' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width(20)
                ))
                ->add_dependency('type','video','=')
                ->add_dependency('video_source','selfhosted','=')
                // ->add_dependency('bgvideo','0','>'),
                ->add_dependency_group()
                ->add_dependency('type','video','=')
                ->add_dependency('video_source','external','=')
                ->add_dependency('external_url','','!='),
    
            Field::create( 'tab', 'Tamaño' ),
            Field::create( 'image_select', 'aspect_ratio' )->add_options(array(
                'aspect-ratio-default'  => array(
                    'label' => 'default',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-default-b.png'
                ),
                'aspect-ratio-4-3'  => array(
                    'label' => '4:3',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-4-3.png'
                ),
                'aspect-ratio-1-1'  => array(
                    'label' => '1:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-1-1.png'
                ),
                'aspect-ratio-16-9'  => array(
                    'label' => '16:9',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-16-9.png'
                ),
                'aspect-ratio-2-1'  => array(
                    'label' => '2:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-2-1.png'
                ),
                'aspect-ratio-2_5-1'  => array(
                    'label' => '2.5:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-2_5-1.png'
                ),
                'aspect-ratio-4-1'  => array(
                    'label' => '4:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-4-1.png'
                ),
                'aspect-ratio-3-4'  => array(
                    'label' => '3:4',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-3-4.png'
                ),
                'aspect-ratio-9-16'  => array(
                    'label' => '9:16',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-9-16.png'
                ),
                'aspect-ratio-1-2'  => array(
                    'label' => '1:2',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-1-2.png'
                ),
                'aspect-ratio-1-2_5'  => array(
                    'label' => '1:2.5',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/images/aspect-ratio-1-2_5.png'
                ),
            )),
            Field::create( 'select', 'alignment','Alineación de la imágen')->add_options( array(
                'left' => 'Izquierda',
                'center' => 'Centrar',
                'right' => 'Derecha'
            ))->add_dependency('aspect_ratio','aspect-ratio-default','=')->add_dependency('type','image','='),
        );

		return $fields;
	}

    public static function get_common_settings() {
        // removed support for: 'video-background':
		return array( 'actions', 'main', 'margins', 'borders', 'box-shadow', 'animation', 'scroll-animations' );
	}

    public static function display( $args ){
		$args['additional_classes'] = array('componente','media');

        $image = $args['image'];
        $type = ( isset($args['type']) ) ? $args['type'] : 'image';
        $args['__type'] = $type;
        $aspect_ratio = ( isset($args['aspect_ratio']) && $args['aspect_ratio'] != 'aspect-ratio-default' ) ? $args['aspect_ratio'] : 'aspect-ratio-default';
        $element_style = '';

        if($type == 'image'){
            $image_url = wp_get_attachment_image_url( $image, IMAGE_THUMB_SIZE );
            $alignment = $args['alignment'];
            if($aspect_ratio == 'aspect-ratio-default'){
                if($alignment) $element_style = 'text-align:'.$alignment.';';
            } else {
                $element_style = 'background-image: url('.$image_url.');';
            }
        }

        if($type == 'video'){
            $video_data = Video::get_video_data($args);
            $video_source = ( isset($args['video_source']) ) ? $args['video_source'] : 'selfhosted';
            if($video_source != 'selfhosted' && $aspect_ratio == 'aspect-ratio-default') $aspect_ratio = 'aspect-ratio-16-9';
        
            $video_settings = (isset($args['video_settings'])) ? $args['video_settings'] : array('bgc'=>'#000000');
            $video_bgc = $video_settings['bgc'];
            if($video_bgc) $element_style .= 'background-color:'.$video_bgc.';';
        
            $video_type = ( isset($args['video_type']) ) ? $args['video_type'] : 'popable';
        
            if( $video_type == 'popable' ){
                $video_key = ( $video_source === 'selfhosted' ) ? 'internal' : 'external';
                $args['actions'] = array(
                    array(
                        'trigger' => 'click',
                        'action' => 'open-video-popup',
                        'video_popup' => array(
                            'video_source' => $video_source,
                            $video_key.'_video' => $video_data['url']
                        )
                    )
                );
            }
        
            $args['additional_classes'][] = $video_type;
            $args['additional_classes'][] = $video_source;
        }

        $args['additional_classes'][] = $aspect_ratio;
        
        $attributes = Template_Engine::generate_attributes( $args );
        ob_start();
        echo '<div '.$attributes.'>';
        echo Template_Engine::check_layout('start', $args);
        ?>
        <div class="media__element text-color-2" style="<?=$element_style?>">
            <?php if( $type == 'image' && $aspect_ratio == 'aspect-ratio-default'): ?>
		        <img src="<?=$image_url?>" alt="">
		    <?php endif; ?>
            <?php if($type == 'video') echo Template_Engine::check_video_background( $args ); ?>
            <?php echo Template_Engine::check_actions( $args ); ?>
	    </div>
        <?php
        echo Template_Engine::check_layout('end', $args);
        echo '</div>';
        return ob_get_clean();
	}
}

new Image();