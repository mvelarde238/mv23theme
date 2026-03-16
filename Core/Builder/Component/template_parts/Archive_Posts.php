<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Builder\Component\Listing;
use Core\Posttype\Archive_Template;

class Archive_Posts extends Component {

    public function __construct() {
		parent::__construct(
			'archive-posts',
			__( 'Archive Posts', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('archive_template')
		);
    }

    public static function get_icon() {
        return 'bi-layout-text-window-reverse';
    }

    public static function get_fields() {
		$fields = array();

		# Add listing fields
		$listing_fields = Listing::get_fields();
		$exclude = ['content_tab','source','posttype','woocommerce_key','tax_params','query_settings_tab','query_params','status_params','pagination_scrolltop'];
		foreach ( $listing_fields as $field ) {
			if( in_array( $field->get_name(), $exclude ) ) continue;

			if( $field->get_name() === 'pagination_type' ){
				$pagination_options = LISTING_PAGINATION_TYPES;
				$field->remove_option('none');
			}

			$fields[] = $field;
		}

		return $fields;
	}

    // handle "_default" placeholder or empty postcard template to use posttype as template
    private static function handle_postcard_template( $key, $args, $posttype, $archive_template ){
        $postcard_template = '';

        $postcard_settings = $args['postcard_settings'] ?? array();
        $postcard_template = $postcard_settings['template'] ?? '_default';
        $posttype = ( $key === 'archive-page') ? $archive_template->get_archive_post_type() : $posttype;
        if( $postcard_template == '_default' || empty($postcard_template) ) $postcard_template = $posttype;

        return $postcard_template;
    }

    public static function display($args){
        $archive_template = Archive_Template::getInstance();
        $default_listing_args = $archive_template->get_default_listing_args();
        $listing_args = array_merge($default_listing_args, $args);

        ob_start();
        // Render posts listing on builder based on archive page settings
        if( isset($args['archive_settings']) ){
            $archive_settings = $args['archive_settings'];
            $posttype = $archive_settings['connected_posttype'] ?? null;
            $taxonomy = $archive_settings['connected_'.$posttype.'_taxonomy'] ?? null;
            $terms = $archive_settings['connected_'.$taxonomy.'_terms'] ?? null;
            
            if( $posttype ){
                $listing_args['posttype'] = $posttype;
            }
            if( $taxonomy && !empty($terms) ){
                $listing_args['tax_params'] = array(
                    $posttype.'--'.$taxonomy => $terms
                );
            }

            $postcard_template = self::handle_postcard_template('builder', $args, $posttype, $archive_template);
            $listing_args['postcard_settings']['template'] = $postcard_template;

            echo Listing::display($listing_args);
        }

        // Render default posts listing on frontend
        if( is_singular( 'archive_template' ) ){
            $msg = __('This is a placeholder for the posts listing. The actual posts will be displayed on the frontend archive page.', 'mv23theme');
            echo '<p class="archive-posts-placeholder">' . esc_html($msg) . '</p>';
        } 
        if ( is_post_type_archive() || is_tax() || is_category() || is_tag() || is_home() ) {
            $postcard_template = self::handle_postcard_template('archive-page', $args, null, $archive_template);
            $listing_args['postcard_settings']['template'] = $postcard_template;

            do_action('before_loop');
            echo Listing::display($listing_args);
        }
        return ob_get_clean();
    }
}

new Archive_Posts();