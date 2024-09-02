<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;

class Image extends Component {

    public function __construct() {
		parent::__construct(
			'image',
			__( 'Image', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-format-image';
    }

	public static function get_title_template() {
		$template = 'Aspect ratio: <%= aspect_ratio %> | Alignment: <%= alignment %>';
		
		return $template;
	}

	public static function get_fields() {

		$fields = array(
            Field::create( 'tab', __('Contenido','default') ),
            Field::create( 'image', 'image', __('Image','default') ),
    
            Field::create( 'tab', __('Size','default') ),
            Field::create( 'image_select', 'aspect_ratio', __('Aspect Ratio') )->add_options(array(
                'default'  => array(
                    'label' => 'default',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-default-b.png'
                ),
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => THEME_CUSTOM_FIELDS_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
            )),
            Field::create( 'select', 'alignment', __('Alignment','default'))->add_options( array(
                'left' => __('Left','default'),
                'center' => __('Center','default'),
                'right' => __('Right','default'),
            )),
            Field::create( 'select', 'object_fit', __('Object Fit','default'))->add_options( array(
                'cover' => __('Cover','default'),
                'contain' => __('Contain','default'),
            ))->add_dependency('aspect_ratio','default','!=')
        );

		return $fields;
	}

    public static function display( $args ){
		$args['additional_classes'] = array('component','media');
        $image_data_id = $args['image'];
        $attachment = get_post( $image_data_id );

        if( !$attachment ) return;

        $image_attributes = array();
        $alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true);
        $title = $attachment->post_title;
        $src = $attachment->guid;
        // $href = get_permalink( $attachment->ID );

        if( !empty($src) ) $image_attributes[] = 'src="'.esc_url($src).'"';
        if( !empty($alt) ) $image_attributes[] = 'alt="'.esc_attr($alt).'"';
        if( !empty($title) ) $image_attributes[] = 'title="'.esc_attr($title).'"';

        $caption = $attachment->post_excerpt;
        // $description = $attachment->post_content;

        $args['additional_styles'] = array();

        $aspect_ratio = ( isset($args['aspect_ratio']) && $args['aspect_ratio'] != 'default' ) ? $args['aspect_ratio'] : false;
        if( $aspect_ratio ) $args['additional_styles'][] = '--aspect-ratio:'.$args['aspect_ratio'];
        
        $alignment = ( isset($args['alignment']) && $args['alignment'] != 'left' ) ? $args['alignment'] : false;
        if( $alignment ) $args['additional_styles'][] = 'text-align:'.$alignment;

        $object_fit = ( isset($args['object_fit']) && $args['object_fit'] != 'cover' ) ? $args['object_fit'] : false;
        if( $object_fit ) $args['additional_styles'][] = '--object-fit:'.$object_fit;
        
        $attributes = Template_Engine::generate_attributes( $args );
        ob_start();
        echo '<div '.$attributes.'>';
        echo Template_Engine::check_layout('start', $args);
		echo '<img '.implode(' ',$image_attributes).'>';
        if( $caption ) echo '<p class="media-caption">'.esc_html($caption).'</p>';
        echo Template_Engine::check_actions( $args );
        echo Template_Engine::check_layout('end', $args);
        echo '</div>';
        return ob_get_clean();
	}
}

new Image();