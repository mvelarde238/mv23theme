<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;

class Text_Editor extends Component {

    public function __construct() {
		parent::__construct(
			'Editor de Texto',
			__( 'Text editor', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-editor-textcolor';
    }

	public static function get_title_template() {
		$template = '<%= content.replace(/<[^>]+>/ig, "") %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
			Field::create( 'tab', 'Contenido' ),
			Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 20 ),
			Field::create( 'tab', 'Mobile Options' ),
			Field::create( 'multiselect', 'theme_clases', 'Helpers' )->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options(array(
				'hide-br' => 'Ocultar saltos de línea en tablet y móviles',
				'hide-br-tablet' => 'Ocultar saltos de línea en tablet',
				'hide-br-mobile' => 'Ocultar saltos de línea en móviles',
			))->hide_label(),
			Field::create( 'checkbox', 'add_responsive' )->set_text( 'Cambiar alineación de textos en móviles' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
			Field::create( 'select', 'tablet_text_align','Alineación del texto en Tablets')->add_options( array(
				'' => 'Seleccionar',
				'left' => 'Izquierda',
				'center' => 'Centro',
				'right' => 'Derecha',
			))->set_width(50)->add_dependency('add_responsive'),
			Field::create( 'select', 'mobile_text_align','Alineación del texto en Móviles')->add_options( array(
				'' => 'Seleccionar',
				'left' => 'Izquierda',
				'center' => 'Centro',
				'right' => 'Derecha',
			))->set_width(50)->add_dependency('add_responsive'),
		);

		return $fields;
	}

	public static function get_common_settings() {
		return array( 'actions', 'all' );
	}

	public static function display( $args ){
		$args['additional_classes'] = array('componente');

		if ( isset($args['tablet_text_align']) && $args['tablet_text_align'] != '' ){
			$args['additional_classes'][] = $args['tablet_text_align'].'-on-tablet';
		} 
		if ( isset($args['mobile_text_align']) && $args['mobile_text_align'] != ''){
			$args['additional_classes'][] = $args['mobile_text_align'].'-on-mobile';
		} 
	
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($args['content']) echo '<div>'.do_shortcode(wpautop(oembed( $args['content'] ))).'</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Text_Editor();