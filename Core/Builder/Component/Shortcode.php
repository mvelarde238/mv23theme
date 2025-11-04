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

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('Content','mv23theme')),
			Field::create( 'complex', 'options' )->hide_label()->add_fields(array(
				Field::create( 'textarea', 'desktop' )->set_rows( 1 )->set_width( 50 ),
				Field::create( 'textarea', 'mobile' )->set_rows( 1 )->set_width( 50 )
			))
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');

		$desktop = $args['options']['desktop'];
		$mobile = $args['options']['mobile'];
		if (empty($desktop) && empty($mobile)) return;
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo ( IS_MOBILE ) ? do_shortcode($mobile) : do_shortcode($desktop);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

	public static function get_view_template() {
		$template = '<div class="shortcode-component">
			<% if ( options.desktop || options.mobile ){ %>
				<div class="shortcode-desktop">
					<strong>Desktop shortcode:</strong>
					<div><%= options.desktop ? options.desktop : "No shortcode defined" %></div>
				</div>
				<div class="shortcode-mobile">
					<strong>Mobile shortcode:</strong>
					<div><%= options.mobile ? options.mobile : "No shortcode defined" %></div>
				</div>
			<% } else { %>
				<div class="no-shortcode">There isnt any shortcode defined</div>
			<% } %>
		</div>';

		return $template;
	}
}

new Shortcode();