<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Builder\Component\Heading;

class Archive_Title extends Component {

    public function __construct() {
		parent::__construct(
			'archive-title',
			__( 'Archive Title', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('archive_template')
		);
    }

    public static function get_icon() {
        return 'bi-fonts';
    }

    public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display($args){
        $title = 'Archive Title';
        $add_tagline = false;
        $tagline = '';

        // Render archive title on builder based on archive page settings
        if( isset($args['archive_settings']) ){
            $archive_settings = $args['archive_settings'];
            $connected_posttype = $archive_settings['connected_posttype'] ?? null;
            $connected_taxonomy = $archive_settings['connected_'.$connected_posttype.'_taxonomy'] ?? null;

            if( $connected_taxonomy ){
                $term = get_taxonomy( $connected_taxonomy );
                $title = __('Archive:','mv23theme') . $term->labels->name;
            } else {
                $post_type_obj = get_post_type_object( $connected_posttype );
                $title = __('Archive:','mv23theme') . $post_type_obj->labels->name;
            }

            $add_tagline = true;
            $tagline = __('This is a placeholder for the Archive Title component. It will display the actual title on the front-end archive pages.', 'mv23theme');
        }

        // Render default archive titles on frontend
        if( is_archive() ){
            $title = get_the_archive_title();
        }
        if( is_search() ){
            $title = sprintf( __( 'Search Results for: %s', 'mv23theme' ), '<span>' . get_search_query() . '</span>' );
        }
        if( is_home() ){
            $title = get_the_title( get_option( 'page_for_posts', true ) );
        }

        ob_start();
        echo Heading::display( array(
            'heading' => array(
                'content' => $title,
                'html_tag' => 'h1'
            ),
            'add_tagline' => $add_tagline,
            'tagline' => array(
                'content' => $tagline,
                'html_tag' => 'p'
            ),
            'preset' => 'style1'
        ));
        return ob_get_clean();
    }
}

new Archive_Title();