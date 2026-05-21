<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Frontend\Page;

class Header_Preview extends Component {

    public function __construct() {
		parent::__construct(
			'header-preview',
			__( 'Header Preview', 'mv23theme' ),
			array(
				'common_settings' => array()
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
		ob_start();
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
                    $header = null;
                    foreach ( $wrapper['components'] as $component ) {
                    	if ( $component['type'] === 'header' ) {
                    		$header = $component;
                    		break;
                    	}
                    }
                    if ( $header ) {
                        if ( !empty($compiled_css) ) echo '<style>' . $compiled_css . '</style>';
                        echo Header::display( $header );
                    };
                }
        	endif;
        endif;
		return ob_get_clean();
	}
}

new Header_Preview();