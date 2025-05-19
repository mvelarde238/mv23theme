<?php
namespace Core\Builder\Component;

use stdClass;
use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Image extends Component {

    public function __construct() {
		parent::__construct(
			'image',
			__( 'Image', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-format-image';
    }

	public static function get_title_template() {
		$template = '<% if(image){ %>
            <%= image_prepared[0].filename %> 
            <% if(aspect_ratio != "default"){ %>
                | aspect ratio: <%= aspect_ratio %> 
            <% } %>
            <% if(alignment && alignment != "left"){ %>
                | Alignment: <%= alignment %>
            <% } %>
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'radio', 'image_source', __('Source','mv23theme'))
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'selfhosted' => __('Media','mv23theme'),
                    'external' => __('External','mv23theme')
                )),

            Field::create( 'image', 'image', __('Image','mv23theme') )->add_dependency('image_source','selfhosted','='),
            Field::create( 'text', 'external_image', 'URL')->add_dependency('image_source','external','='),
            Field::create( 'text', 'external_image_credits', __('Credits','mv23theme'))->add_dependency('image_source','external','='),

            Field::create( 'checkbox', 'expand_on_click', __('Expand on click','mv23theme') )->fancy()
                ->set_text( __( 'Show the image in a popup.', 'mv23theme' ) ),
            Field::create( 'checkbox', 'full_width', __( 'Full Width', 'mv23theme' )  )->fancy()
                ->set_text( __( 'Let the image fill the full width of the available space.', 'mv23theme' ) ),
            Field::create( 'select', 'alignment', __('Alignment','mv23theme'))->add_options( array(
                'left' => __('Left','mv23theme'),
                'center' => __('Center','mv23theme'),
                'right' => __('Right','mv23theme'),
            ))->add_dependency('full_width',0),
    
            Field::create( 'tab', __('Size','mv23theme') ),
            Field::create( 'image_select', 'aspect_ratio', __('Aspect Ratio') )->add_options(array(
                'default' => array(
                    'label' => 'default',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-default.png'
                ),
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
                'custom'  => array(
                    'label' => 'custom',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-custom.png'
                ),
            )),
            Field::create( 'text', 'custom_aspect_ratio' )
                ->set_validation_rule('^(\d+(\.\d+)?)(\s*\/\s*(\d+(\.\d+)?))?$')
                ->add_dependency( 'aspect_ratio', 'custom' ),
            Field::create( 'select', 'object_fit', __('Object Fit','mv23theme'))->add_options( array(
                'cover' => __('Cover','mv23theme'),
                'contain' => __('Contain','mv23theme'),
            ))->add_dependency('aspect_ratio','default','!=')
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component','media');

        $attachment = false;
        $image_source = $args['image_source'] ?? 'selfhosted';
        
        if( $image_source == 'selfhosted' ){
            if( $args['image'] ) $attachment = get_post( $args['image'] );
        }

        if( $image_source == 'external' && $args['external_image'] ){
            $attachment = new stdClass();
            $attachment->ID = 0;
            $attachment->guid = $args['external_image'];
            $attachment->post_title = '';
            $attachment->post_excerpt = $args['external_image_credits'];
        }

        if( !$attachment ){
            $attachment = new stdClass();
            $attachment->ID = 0;
            $attachment->guid = get_stylesheet_directory_uri().'/assets/images/nothumb.jpg';
            $attachment->post_title = '';
            $attachment->post_excerpt = '';
        }

        $image_attributes = array();
        $alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true);
        $title = $attachment->post_title;
        $src = $attachment->guid;
        // $href = get_permalink( $attachment->ID );

        if( !empty($src) ) $image_attributes[] = 'src="'.esc_url($src).'"';
        if( !empty($alt) ) $image_attributes[] = 'alt="'.esc_attr($alt).'"';
        if( !empty($title) ) $image_attributes[] = 'title="'.esc_attr($title).'"';

        if( isset($args['expand_on_click']) && $args['expand_on_click'] ) $image_attributes[] = 'class="zoom"';

        if( isset($args['full_width']) && $args['full_width'] ) $image_attributes[] = 'style="width:100%"';

        $caption = $attachment->post_excerpt;
        // $description = $attachment->post_content;

        $args['additional_styles'] = array();

        $aspect_ratio = ( isset($args['aspect_ratio']) && $args['aspect_ratio'] != 'mv23theme' ) ? $args['aspect_ratio'] : false;
        if( $aspect_ratio ){
            $aspect_ratio_value = ( $args['aspect_ratio'] != 'custom' ) ? $args['aspect_ratio'] : $args['custom_aspect_ratio'];
            $args['additional_styles'][] = '--aspect-ratio:'.$aspect_ratio_value;
        } 
        
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