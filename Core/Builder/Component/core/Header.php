<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Header extends Component {

    public function __construct() {
		parent::__construct(
			'header',
			__( 'Header', 'mv23theme' ),
			array(
				'common_settings' => array(
					'settings',
                    'scroll_animations_settings'
				),
			)
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array(
			Field::create('checkbox', 'adjust_scroll_position', __('Adjust scroll position','mv23theme'))
                ->set_text(__('If sticky header logo is smaller than static header logo this setting needs to be enabled.','mv23theme'))
                ->fancy()
		);
		return $fields;
	}

    public static function display( $args ){
		$header_content = $args['components'][0] ?? null;

		$adjust_scroll_position = $args['settings']['adjust_scroll_position'] ?? false;
		if ( $adjust_scroll_position ) {
			$args['additional_attributes']['data-adjust-scroll-position'] = 'true';
		}

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo '<div class="header-content container">';
		echo Template_Engine::check_components( $header_content );
		echo '</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Header();