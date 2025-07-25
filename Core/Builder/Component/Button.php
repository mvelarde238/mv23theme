<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Button extends Component {

    public function __construct() {
		parent::__construct(
			'button',
			__( 'Button', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-button';
    }

    public static function get_title_template() {
		$template = '<%= text %>';
		
		return $template;
	}

    public static function get_layout(){
        return 'table';
    }

	public static function get_fields() {

        $button_styles = apply_filters( 'filter_core_button_styles', array(
            'btn btn--main-color' => 'Botón Corporativo 1',
            'btn btn--secondary-color' => 'Botón Corporativo 2',
            'btn btn--white' => 'Botón Blanco',
            'btn' => 'Botón Simple',
            '' => 'Link'
        ));

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme') ), 
            Field::create( 'text', 'text', 'Texto del botón')->set_width(25),
            Field::create( 'select', 'style', 'Estilo')->add_options( $button_styles )->set_width(25),
    
            Field::create( 'radio', 'type','Tipo')->set_orientation( 'horizontal' )->add_options( array(
                'link' => 'Link',
                'download' => 'Descarga',
            ))->set_width(25),
    
            Field::create( 'file', 'file', 'File' )->add_dependency('type','download','=')->set_width(25),
    
            Field::create( 'radio', 'url_type','Destino:')->set_orientation( 'horizontal' )->add_options( array(
                'interna' => 'Página Interna',
                'externa' => 'Otro',
            ))->add_dependency('type','link','=')->set_width(25),
            Field::create( 'wp_object', 'post', '' )->set_button_text( 'Selecciona la página' )->add_dependency('type','link','=')->add_dependency('url_type','interna','=')->set_width(25),
            Field::create( 'text', 'url', '' )->add_dependency('type','link','=')->add_dependency('url_type','externa','=')->set_width(25),
    
            Field::create( 'checkbox', 'new_tab', 'Abrir en una nueva ventana' )->set_text( 'Activar' )->set_width(25),
    
            Field::create( 'tab', 'Icono' ),
            Field::create( 'icon', 'icon', 'Icono' )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_width( 25 ),
            Field::create( 'radio', 'icon_position', 'Posición')->add_options( array(
                'left' => 'Izquierda',
                'right' => 'Derecha'
            ))->set_orientation( 'horizontal' )->set_width(25),
    
            Field::create( 'tab', __('Others','mv23theme') ),
            Field::create( 'radio', 'alignment', 'Alineación')->add_options( array(
                'left' => 'Izquierda',
                'center' => 'Centro',
                'right' => 'Derecha'
            ))->set_orientation( 'horizontal' )->set_width(25),
            Field::create( 'radio', 'size', 'Tamaño')->add_options( array(
                'small' => 'Normal',
                'medium' => 'Mediano',
                'big' => 'Grande'
            ))->set_orientation( 'horizontal' )->set_width(25),
            Field::create( 'checkbox', 'fullwidth','Botón de ancho completo' )->set_text( 'Activar' )->set_width(25),
            Field::create( 'repeater', 'attributes', 'Attributos' )->set_add_text('Agregar')
                ->set_layout( 'table' )
                ->add_group('item', array(
                    'title_template' => '<%= attribute %> : <%= value %>',
                    'fields' => array(
                        Field::create( 'text', 'attribute' )->set_width( 50 ),
                        Field::create( 'text', 'value' )->set_width( 50 ),
                    )
            )),
            Field::create( 'checkbox', 'add_responsive' )->set_text( 'Cambiar alineación en móviles' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
            Field::create( 'select', 'tablet_text_align','Alineación en Tablets')->add_options( array(
                '' => 'Seleccionar',
                'left' => 'Izquierda',
                'center' => 'Centro',
                'right' => 'Derecha',
            ))->set_width(50)->add_dependency('add_responsive'),
            Field::create( 'select', 'mobile_text_align','Alineación en Móviles')->add_options( array(
                '' => 'Seleccionar',
                'left' => 'Izquierda',
                'center' => 'Centro',
                'right' => 'Derecha',
            ))->set_width(50)->add_dependency('add_responsive')            
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');
        $args['__type'] = 'button-cmp';

        $class = $args['style'];
        $fullwidth = (isset($args['fullwidth'])) ? $args['fullwidth'] : false;
        if($fullwidth) $class .= ' btn-block';
            
        $text = $args['text'] ?: 'Botón';
        $icon = (isset( $args['icon'])) ? $args['icon'] : null;
        if( $icon ) {
            $icon_position = $args['icon_position'] ?: 'left';
            $icon_prefix = (str_starts_with($icon,'fa')) ? 'fa' : 'bi';
            $icon_html = '<i class="'.$icon_prefix.' '.$icon.'"></i>';
            $class .= ' btn--icon-'.$args['icon_position'];
        
            $text = ( $icon_position === 'left' ) ? $icon_html.' '.$text : $text.' '.$icon_html;
        } 
        
        $size = (isset($args['size'])) ? $args['size'] : false;
        if($size) $class .= ' btn--'.$args['size'];

        $type = $args['type'];
        $href = '#';
        $attrs = '';
        if($type == 'link'){
            $url_type = $args['url_type'];
            switch ($url_type) {
                case 'externa':
                    $href = $args['url'];
                    break;
                
                case 'interna':
                    if($args['post']){
                        $href = get_permalink( str_replace('post_','',$args['post']) );
                    }
                    break;
            }
            $attrs = ( isset($args['new_tab']) && $args['new_tab'] == 1) ? 'target="_blank"' : ''; 
        }
        if($type == 'download'){
            if($args['file']){
                $href = wp_get_attachment_url( $args['file'] );
                $attrs = ( isset($args['new_tab']) && $args['new_tab'] == 1) ? 'target="_blank"' : 'download'; 
            }
        }

        $attributes = ( isset($args['attributes']) ) ? $args['attributes'] : array();
        $additional_attrs = '';
        if( is_array($attributes) && count($attributes) > 0 ){
            foreach ($attributes as $item) {
                if( $item['attribute'] && $item['value'] ){
                    if( $item['attribute'] == 'class' ){
                        $class .= ' '.$item['value'];
                    } else {
                        $additional_attrs .= ' '.$item['attribute'].'="'.$item['value'].'"';
                    }
                }
            }
        }

        $alignment = $args['alignment'];
        if ( $alignment != 'left' ) $args['additional_classes'][] = $alignment.'-align';

        if ( $args['tablet_text_align'] != '' ) $args['additional_classes'][] = $args['tablet_text_align'].'-on-tablet';
        if ( $args['mobile_text_align'] != '') $args['additional_classes'][] = $args['mobile_text_align'].'-on-mobile';
		
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        if($text) echo '<a href="'.$href.'" '.$attrs.' class="'.$class.'"'.$additional_attrs.'>'.$text.'</a>';
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Button();