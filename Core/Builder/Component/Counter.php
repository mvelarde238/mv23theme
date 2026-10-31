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
            Field::create( 'section', 'content_section', __( 'Content', 'mv23theme' ) ),
            Field::create( 'number', 'number', __( 'Counter Number', 'mv23theme' ) )
                ->set_step( 0.01 )
                ->set_default_value( 23 ),
            Field::create( 'text', 'prefix', __( 'Counter Prefix', 'mv23theme' ) )->set_width( 50 ),
            Field::create( 'text', 'suffix', __( 'Counter Suffix', 'mv23theme' ) )->set_width( 50 ),
            Field::create( 'checkbox', 'format_number', __( 'Format Number', 'mv23theme' ) )
                ->fancy(),
            Field::create( 'text', 'thousands_separator', __( 'Thousands Separator', 'mv23theme' ) )
                ->set_default_value( '.' )
                ->add_dependency( 'format_number' )
                ->set_width( 50 ),
            Field::create( 'text', 'decimal_separator', __( 'Decimal Separator', 'mv23theme' ) )
                ->set_default_value( ',' )
                ->add_dependency( 'format_number' )
                ->set_width( 50 ),

            Field::create( 'section', 'animation_section', __( 'Animation', 'mv23theme' ) ),
            Field::create( 'number', 'duration', __( 'Counter Duration', 'mv23theme' ) )
                ->set_default_value( 1000 )
                ->set_suffix( 'ms' ),
        );
		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;
		
		$args['additional_classes'][] = 'component';

        $shortcode_atts = [
            'number'              => esc_attr( $args['number'] ),
            'duration'            => esc_attr( $args['duration'] ),
            'prefix'              => esc_attr( $args['prefix'] ),
            'suffix'              => esc_attr( $args['suffix'] ),
            'format_number'       => ! empty( $args['format_number'] ) ? '1' : '0',
            'thousands_separator' => esc_attr( $args['thousands_separator'] ?? '.' ),
            'decimal_separator'  => esc_attr( $args['decimal_separator'] ?? ',' ),
        ];

        $shortcode = '[counter';
        foreach ( $shortcode_atts as $key => $value ) {
            if ( null !== $value && '' !== (string) $value ) {
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
		$template = '<%= prefix %><span class="counter-number"><% var _parts = String(number).split("."); var _integer = _parts[0]; var _decimal = _parts[1] || ""; if (format_number == "1") { _integer = _integer.replace(/\\B(?=(\\d{3})+(?!\\d))/g, thousands_separator || "."); } print(_integer + (_decimal ? (format_number == "1" ? (decimal_separator || ",") : ".") + _decimal : "")); %></span><%= suffix %>';

        return $template;
    }
}

new Counter();