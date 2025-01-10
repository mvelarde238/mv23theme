<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Slider extends Component {

    public function __construct() {
		parent::__construct(
			'slider',
			__( 'Slider', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-slides';
    }

    public static function get_title_template() {
		$template = '<% if ( slider_desktop || slider_movil ){ %>
            <%= "Desktop: "+slider_desktop  %> | <%= "Mobile: "+slider_movil %>
        <% } else { %>
            There isnt any slide
        <%  } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('Content','mv23theme')),
            Field::create( 'textarea', 'slider_desktop' )->set_rows( 1 )->set_width( 50 ),
            Field::create( 'textarea', 'slider_movil' )->set_rows( 1 )->set_width( 50 ),
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');

		$slider_desktop = $args['slider_desktop'];
		$slider_movil = $args['slider_movil'];
		if (empty($slider_desktop) && empty($slider_movil)) return;
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo ( IS_MOBILE ) ? do_shortcode($slider_movil) : do_shortcode($slider_desktop);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Slider();