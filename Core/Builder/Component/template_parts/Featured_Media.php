<?php
use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Image;
use Core\Builder\Component\Video;
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
            'posttypes' => array('single_template','postcard'),
            'block_category' => 'template_parts',
            'custom_datastore_change_callback' => true
		);
    }

    public static function get_fields() {
        $fields = Image::get_fields();

        $control_css = 'width:50%;min-width:auto;flex-grow:initial;';
        $fields[] = Field::create( 'tab', __('Video Settings','mv23theme') );
        $fields[] = Field::create( 'complex', 'video_settings' )->hide_label()->add_fields(array(
                Field::create( 'checkbox', 'controls', __('Controls','mv23theme') )->fancy()->set_attr( 'style', $control_css )->set_default_value( 1 ),
                Field::create( 'checkbox', 'autoplay', __('AutoPlay','mv23theme') )->fancy()->set_attr( 'style', $control_css ),
                Field::create( 'checkbox', 'muted', __('Muted','mv23theme') )->fancy()->set_attr( 'style', $control_css ),
                Field::create( 'checkbox', 'loop', __('Loop','mv23theme') )->fancy()->set_attr( 'style', $control_css ),
                Field::create( 'color', 'bgc', __('Background color','mv23theme') )->set_default_value('#000000'),
                Field::create( 'number', 'opacity', __('Opacity','mv23theme') )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )
            ));

		return $fields;
	}

    public static function display( $args ){
        global $post;
        $featured_video_data = Post::getInstance()->get_featured_video_data($post);

        ob_start();
        if ( $featured_video_data['has_video'] ) {
            $args['__type'] = 'video-component';
            $video_args = array_merge($args, $featured_video_data);
            echo Video::display( $video_args );
        } else {
            $args['__type'] = 'image-component';
            echo Image::display( $args );
        }
        return ob_get_clean();
    }
}

new Featured_Media();