<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Frontend\Page;

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
		$theme_header_post_meta = get_option('theme_header_post');
       
        if ($theme_header_post_meta): 
            $theme_header_post_id = str_replace('post_', '', $theme_header_post_meta);
        	if( IS_MULTILANGUAGE && function_exists('pll_get_post') ) $theme_header_post_id = pll_get_post($theme_header_post_id);
            
        	$page_content = get_post_meta( $theme_header_post_id, 'page_content', true );
            $page_content_datastore = get_post_meta( $theme_header_post_id, 'page_content_datastore', true );
            $compiled_css = Page::compile_styles_to_css( is_array($page_content) ? ($page_content['styles'] ?? []) : [] );
            $page_content = Page::consolidate_content( $page_content, $page_content_datastore );
            $compiled_css .= Page::compile_components_custom_css( $page_content );
        
            if (is_array($page_content)) :
                $wrapper = $page_content['pages'][0]['frames'][0]['component'] ?? null;
                if ( $wrapper['type'] === 'wrapper' ){
                    $header_comp = null;
                    foreach ( $wrapper['components'] as $component ) {
                    	if ( $component['type'] === 'header' ) {
                    		$header_comp = $component;
                    		break;
                    	}
                    }

					// $header_comp['components'][0] is a header-content wrapper
					$components_to_render = $header_comp['components'][0]['components'] ?? [];
					
					if (is_array($components_to_render) && !empty($components_to_render)) :

						$header_args = $header_comp;
						$adjust_scroll_position = $header_args['settings']['adjust_scroll_position'] ?? false;
						if ( $adjust_scroll_position ) {
							$header_args['additional_attributes']['data-adjust-scroll-position'] = 'true';
						} 

						ob_start();
        				echo Template_Engine::component_wrapper('start', $header_args);	
						if ( !empty($compiled_css) ) echo '<style>' . $compiled_css . '</style>';
						echo '<div class="header-content container">';
						foreach ($components_to_render as $component) :
							// $component['__post_id'] = $page_ID;
							echo Template_Engine::getInstance()->handle( $component );
						endforeach;
						echo '</div>';
						echo Template_Engine::component_wrapper('end', $header_args);
						return ob_get_clean();
					endif;
                }
        	endif;
        endif;
    }
}

new Header();