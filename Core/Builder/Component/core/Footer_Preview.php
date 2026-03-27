<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Frontend\Page;

class Footer_Preview extends Component {

    public function __construct() {
		parent::__construct(
			'footer-preview',
			__( 'Footer Preview', 'mv23theme' ),
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
		$theme_footer_post_meta = get_option('theme_footer_post');

        if ($theme_footer_post_meta): 
        	if( IS_MULTILANGUAGE && function_exists('pll_get_post') ) $theme_footer_post_id = pll_get_post($theme_footer_post_id);
            
            $theme_footer_post_id = str_replace('post_', '', $theme_footer_post_meta);
        	$page_content = get_post_meta( $theme_footer_post_id, 'page_content', true );
            $page_content_datastore = get_post_meta( $theme_footer_post_id, 'page_content_datastore', true );
            $compiled_css = Page::compile_styles_to_css( is_array($page_content) ? ($page_content['styles'] ?? []) : [] );
            $page_content = Page::consolidate_content( $page_content, $page_content_datastore );
        
            if (is_array($page_content)) :
                $wrapper = $page_content['pages'][0]['frames'][0]['component'] ?? null;
                if ( $wrapper['type'] === 'wrapper' ){
                    $container = null;
                    foreach ( $wrapper['components'] as $component ) {
                    	if ( $component['type'] === 'container' ) {
                    		$container = $component;
                    		break;
                    	}
                    }
                    if ( $container ) {
                        if ( !empty($compiled_css) ) echo '<style>' . $compiled_css . '</style>';
                        echo Footer::display( $container );
                    };
                }
        	endif;
        endif;
		return ob_get_clean();
	}
}

new Footer_Preview();