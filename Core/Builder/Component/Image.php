<?php
namespace Core\Builder\Component;

use stdClass;
use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Ultimate_Builder\Handlebars;

class Image extends Component {

    public function __construct() {
		parent::__construct(
			'image-component',
			__( 'Image', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-format-image';
    }

    public static function get_builder_data() {
        return array(
            // 'block_render_type' => 'figure',
            'custom_datastore_change_callback' => true
		);
    }

	public static function get_fields() {

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'radio', 'image_source', __('Source','mv23theme'))
                ->set_default_value( 'selfhosted' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'selfhosted' => __('Media','mv23theme'),
                    'external' => __('External','mv23theme')
                )),
            Field::create( 'image', 'image', __('Image','mv23theme') )->add_dependency('image_source','selfhosted','='),
            Field::create( 'text', 'external_image', 'URL')
                ->add_dynamic_data_selector()
                ->hide_label()->set_prefix('URL')->add_dependency('image_source','external','=')
                ->add_suggestions( array(
                    'https://picsum.photos/600/500?random=238'
                )),
            Field::create( 'checkbox', 'expand_on_click', __('Expand on click','mv23theme') )->fancy()
                ->set_text( __( 'Show the image in a popup.', 'mv23theme' ) ),
    
            Field::create( 'tab', __('Caption','mv23theme') ),
            Field::create( 'radio', 'caption_source', __('Source','mv23theme'))
                ->set_default_value( 'global' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'global' => __('Global','mv23theme'),
                    'custom' => __('Custom','mv23theme')
                )),
            Field::create( 'message', 'caption_global_info', __('The legend set in the Media Library will be used.','mv23theme') )
                ->add_dependency( 'caption_source', 'global', '=' ),
            Field::create( 'text', 'custom_caption' )
                ->hide_label()->add_dependency( 'caption_source', 'custom', '=' ),
            
            Field::create( 'tab', __('Aspect Ratio','mv23theme') ),
            Field::create( 'image_select', 'aspect_ratio', __('Aspect Ratio') )
                ->set_description( __('Select a predefined aspect ratio (width / height) or set a custom one.', 'mv23theme') )
                ->hide_label()->set_attr( 'class', 'image-select-3-cols' )->add_options(array(
                'default' => array(
                    'label' => 'default',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-default.png'
                ),
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-2_5.png'
                ),
                'custom'  => array(
                    'label' => 'custom',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-custom.png'
                ),
            )),
            Field::create( 'text', 'custom_aspect_ratio' )
                ->hide_label()
                ->set_prefix( 'Custom Width / Height' )
                ->add_suggestions(array( '1/1', '4/3', '16/9', '2/1', '2.5/1', '4/1', '3/4', '9/16', '1/2', '1/2.5' ))
                ->set_validation_rule('^(\d+(\.\d+)?)(\s*\/\s*(\d+(\.\d+)?))?$')
                ->add_dependency( 'aspect_ratio', 'custom' )
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'media';

        $attachment = false;
        $image_source = $args['image_source'] ?? 'selfhosted';
        
        if( $image_source == 'selfhosted' ){
            if( $args['image'] ) {
                $attachment = get_post( $args['image'] );
                // do this to get the correct URL when theme has support for webp:
                $attachment->guid = wp_get_attachment_image_url($args['image'], 'full');
            }
        }

        if( $image_source == 'external' && $args['external_image'] ){
            $attachment = new stdClass();
            $attachment->ID = 0;
            $attachment->guid = Handlebars::parse($args['external_image']);
            $attachment->post_title = '';
            $attachment->post_excerpt = '';
        }

        if( !$attachment ){
            $attachment = new stdClass();
            $attachment->ID = 0;
            $attachment->guid = get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
            $attachment->post_title = '';
            $attachment->post_excerpt = '';
            $args['additional_classes'][] = 'no-image';
        }

        $alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true);
        $title = $attachment->post_title;
        $src = $attachment->guid;
        // $href = get_permalink( $attachment->ID );

        // set caption
        $caption_source = $args['caption_source'] ?? 'global';
        $custom_caption = $args['custom_caption'] ?? '';
        if( $caption_source == 'custom' ){
            $attachment->post_excerpt = $custom_caption;
        }
        $caption = $attachment->post_excerpt ?? '';
        
        if( !empty($src) ) $args['additional_attributes']['src'] = esc_url($src);
        if( !empty($alt) ) $args['additional_attributes']['alt'] = esc_attr($alt);
        if( !empty($title) ) $args['additional_attributes']['title'] = esc_attr($title);

        if( isset($args['expand_on_click']) && $args['expand_on_click'] ) $args['additional_classes'][] = 'zoom';
        
        $image_attributes = Template_Engine::generate_attributes( $args );
        ob_start();
        do_action( 'after_component_wrapper_start', $args );
        echo Template_Engine::check_layout('start', $args);
        echo Template_Engine::check_actions( 'start', $args );
		echo '<img '.$image_attributes.'>';
        echo Template_Engine::check_actions( 'end', $args );
        if( $caption ) echo '<p class="media-caption">'.esc_html($caption).'</p>';
        echo Template_Engine::check_layout('end', $args);
        do_action( 'before_component_wrapper_end', $args );
        return ob_get_clean();
	}
}

new Image();