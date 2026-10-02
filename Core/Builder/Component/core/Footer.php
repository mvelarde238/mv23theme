<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Frontend\Page;

class Footer extends Component {

    public function __construct() {
		parent::__construct(
			'footer',
			__( 'Footer', 'mv23theme' ),
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
		$fields = array();
		return $fields;
	}

	public static function display( $args ){
		$args['type'] = 'footer';
		$args['html_tag'] = 'footer';
		$args['classes'] = array('footer', 'site-footer');

		$theme_footer_post_meta = get_option('theme_footer_post');

		$go_top_icon = defined('GO_TOP_ICON') ? GO_TOP_ICON : 'fa-angle-up';
		$go_top_icon_prefix = ( substr($go_top_icon, 0, 3) === 'fa-' ) ? 'fa' : 'bi';
		$go_top_icon = $go_top_icon_prefix . ' ' . $go_top_icon;

        ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        
        if ($theme_footer_post_meta): 
            $theme_footer_post_id = str_replace('post_', '', $theme_footer_post_meta);
        	if( IS_MULTILANGUAGE && function_exists('pll_get_post') ) $theme_footer_post_id = pll_get_post($theme_footer_post_id);
            
        	$page_content = get_post_meta( $theme_footer_post_id, 'page_content', true );
            $page_content_datastore = get_post_meta( $theme_footer_post_id, 'page_content_datastore', true );
            $compiled_css = Page::compile_styles_to_css( is_array($page_content) ? ($page_content['styles'] ?? []) : [] );
            $page_content = Page::consolidate_content( $page_content, $page_content_datastore );
            $compiled_css .= Page::compile_components_custom_css( $page_content );
        
            if (is_array($page_content)) :
                $wrapper = $page_content['pages'][0]['frames'][0]['component'] ?? null;
                if ( $wrapper['type'] === 'wrapper' ){
                    $page_content_comp = null;
                    foreach ( $wrapper['components'] as $component ) {
                    	if ( $component['type'] === 'page-content' ) {
                    		$page_content_comp = $component;
                    		break;
                    	}
                    }
					$components_to_render = $page_content_comp['components'] ?? [];
					
					if (is_array($components_to_render) && !empty($components_to_render)) :
						if ( !empty($compiled_css) ) echo '<style>' . $compiled_css . '</style>';
					
						foreach ($components_to_render as $component) :
							// $component['__post_id'] = $page_ID;
							echo Template_Engine::getInstance()->handle( $component );
						endforeach;

						echo '<a class="subir-btn" href="#content" data-state="hidden"><i class="'.$go_top_icon.'"></i></a>';
					endif;
                }
        	endif;
        endif;

        echo Template_Engine::component_wrapper('end', $args);
        return ob_get_clean();
    }
}

new Footer();