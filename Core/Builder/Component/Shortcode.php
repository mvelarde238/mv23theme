<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Shortcode extends Component {

    public function __construct() {
		parent::__construct(
			'shortcode',
			__( 'Shortcode', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-shortcode';
    }

    public static function get_title_template() {
		$template = '<% if ( desktop || mobile ){ %>
            <%= "Desktop: "+desktop  %> | <%= "Mobile: "+mobile %>
        <% } else { %>
            There isnt any shortcode defined
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('Content','mv23theme')),
			Field::create( 'complex', '_shortcodes-wrapper' )->merge()->hide_label()->add_fields(array(
				Field::create( 'textarea', 'desktop' )->set_rows( 1 )->set_width( 50 ),
				Field::create( 'textarea', 'mobile' )->set_rows( 1 )->set_width( 50 )
			))
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');

		$desktop = $args['desktop'];
		$mobile = $args['mobile'];
		if (empty($desktop) && empty($mobile)) return;
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo ( IS_MOBILE ) ? do_shortcode($mobile) : do_shortcode($desktop);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Shortcode();