<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Counter extends Component {

    public function __construct() {
		parent::__construct(
			'counter-component',
			__( 'Counter', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'bi-8-square';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'number', 'number', __( 'Counter Number', 'mv23theme' ) )
                ->set_default_value( 23 ),
            Field::create( 'number', 'start', __( 'Counter Start', 'mv23theme' ) )
                ->set_default_value( 0 )->set_placeholder(0)->set_width( 50 ),
            Field::create( 'number', 'duration', __( 'Counter Duration', 'mv23theme' ) )
                ->set_default_value( 1000 )
                ->set_suffix( 'ms' )
                ->set_width( 50 ),
            Field::create( 'text', 'prefix', __( 'Counter Prefix', 'mv23theme' ) )->set_width( 50 ),
            Field::create( 'text', 'suffix', __( 'Counter Suffix', 'mv23theme' ) )->set_width( 50 ),
        );
		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'][] = 'component';

        $shortcode_atts = [
            'number' => esc_attr( $args['number'] ),
            'start' => esc_attr( $args['start'] ),
            'duration' => esc_attr( $args['duration'] ),
            'prefix' => esc_attr( $args['prefix'] ),
            'suffix' => esc_attr( $args['suffix'] ),
        ];

        $shortcode = '[counter';
        foreach ( $shortcode_atts as $key => $value ) {
            if ( ! empty( $value ) ) {
                $shortcode .= ' ' . $key . '="' . $value . '"';
            }
        }
        $shortcode .= ']';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo do_shortcode( $shortcode );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
		$template = '<%= prefix %><span class="counter-number"><%= number %></span><%= suffix %>';

        return $template;
    }
}

new Counter();