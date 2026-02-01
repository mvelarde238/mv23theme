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
			Field::create( 'textarea', 'desktop' )					
				->hide_label()
				->set_rows( 3 ),
			Field::create( 'checkbox', 'set_mobile_shortcode' )
				->hide_label()
        		->set_attr( 'class', 'uf-separator-top' )
        		->set_text( __('Use another shortcode on mobile', 'mv23theme') ),
			Field::create( 'textarea', 'mobile' )
				->hide_label()
				->set_rows( 3 )
				->add_dependency( 'set_mobile_shortcode' )
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'][] = 'component';

		$desktop = $args['desktop'];
		$mobile = (isset($args['set_mobile_shortcode']) && $args['set_mobile_shortcode']) ? $args['mobile'] : null;
		if (empty($desktop) && empty($mobile)) return;
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo ( IS_MOBILE && $mobile ) ? do_shortcode($mobile) : do_shortcode($desktop);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

	public static function get_view_template() {
		$template = '<div class="shortcode-component">
			<% if ( desktop && set_mobile_shortcode && mobile ){ %>
				<% if ( desktop === mobile ){ %>
					<div class="shortcode">
						<div><%= desktop %></div>
					</div>
				<% } else { %>
					<div class="shortcode">
						<div><i class="bi bi-laptop"></i> <%= desktop %> | <i class="bi bi-phone"></i> <%= mobile %></div>
					</div>
				<% } %>
			<% } else if ( desktop ){ %>
				<div class="shortcode">
					<div><%= desktop %></div>
				</div>
			<% } else if ( set_mobile_shortcode && mobile ){ %>
				<div class="shortcode">
					<div><i class="bi bi-phone"></i> <%= mobile %></div>
				</div>
			<% } else { %>
				<div class="no-shortcode">There isnt any shortcode defined</div>
			<% } %>
		</div>';

		return $template;
	}
}

new Shortcode();