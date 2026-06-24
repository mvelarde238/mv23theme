<?php
use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Image;
use Core\Posttype\Post;

class Featured_Media extends Component {

    public function __construct() {
		parent::__construct(
			'featured-media',
			__( 'Featured Media', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-format-image';
    }

    public static function get_builder_data() {
        return array(
            'posttypes' => array('postcard'),
            'custom_datastore_change_callback' => true
		);
    }

    public static function get_fields() {
        $fields = Image::get_fields();
		return $fields;
	}

    public static function display( $args ){
        global $post;
        $featured_video = Post::getInstance()->get_featured_video($post);
        if ( $featured_video ) {
            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            echo $featured_video;
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        } else {
            return Image::display( $args );
        }
    }
}

new Featured_Media();