<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Builder\Component\Listing;
use Core\Posttype\Archive_Page;

class Archive_Posts extends Component {

    public function __construct() {
		parent::__construct(
			'archive-posts',
			__( 'Archive Posts', 'mv23theme' )
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

    public static function display($args){        
        ob_start();

        // Render posts listing on builder based on archive page settings
        if( isset($args['archive_page_settings']) ){
            $archive_page_settings = $args['archive_page_settings'];
            $listing_args = $archive_page_settings;
            $listing_args['source'] = 'auto';
            $listing_args['query_params']['posts_per_page'] = get_option( 'posts_per_page', 10 );

            $posttype = $archive_page_settings['connected_posttype'] ?? null;
            $taxonomy = $archive_page_settings['connected_'.$posttype.'_taxonomy'] ?? null;
            $terms = $archive_page_settings['connected_'.$taxonomy.'_terms'] ?? null;
            
            if( $posttype ){
                $listing_args['posttype'] = $posttype;
            }
            if( $taxonomy && !empty($terms) ){
                $listing_args['tax_params'] = array(
                    $posttype.'--'.$taxonomy => $terms
                );
            }

            // handle _default postcard template placeholder
            $postcard_settings = $archive_page_settings['postcard_settings'] ?? array();
            $postcard_template = $postcard_settings['template'] ?? '_default';
            if( $postcard_template == '_default' ) $postcard_template = $posttype;
            $listing_args['postcard_settings']['template'] = $postcard_template;

            echo Listing::display($listing_args);
        }

        // Render default posts listing on frontend
        if( is_singular( 'archive_page' ) ){
            echo '--posts--';
            echo '--pagination--';
        } 
        if ( is_post_type_archive() || is_tax() || is_category() || is_tag() || is_home() ) {
            $archive_page = Archive_Page::getInstance();
            
            $listing_args = array(
                '__type' => 'listing',
                'additional_classes' => array( 'disable-numeric-ajax-pagination' ),
                'source' => 'auto',
                'posttype' => $archive_page->get_archive_post_type(),
                'tax_params' => $archive_page->get_archive_tax_params(),
                'listing_template' => $archive_page->get_listing_template(),
                'columns' => $archive_page->get_loop_columns(),
                'column_gap' => $archive_page->get_columns_gap(),
                'carousel_settings' => $archive_page->get_carousel_settings(),
                'postcard_settings' => $archive_page->get_postcard_settings(),
                'pagination_type' => $archive_page->get_pagination_type(),
                'pagination_scrolltop' => $archive_page->get_pagination_scrolltop(),
                'show_filter' => $archive_page->show_filter(),
                'filters' => $archive_page->get_filters(),
                'query_params' => array(
                    'posts_per_page' => get_option( 'posts_per_page', 12 ),
                ),
            );
            echo Listing::display($listing_args);
            // get_template_part('partials/loop');
            // get_template_part('partials/pagination');
        }
        return ob_get_clean();
    }
}

new Archive_Posts();