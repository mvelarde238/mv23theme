<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Template_Engine\Actions;
use Ultimate_Fields\Ultimate_Builder\Handlebars;

class Button extends Component {

    public function __construct() {
		parent::__construct(
			'button',
			__( 'Button', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-button';
    }

	public static function get_fields() {

        $button_styles = apply_filters( 'filter_core_button_styles', array(
            'btn btn--main-color' => 'Botón Corporativo 1',
            'btn btn--secondary-color' => 'Botón Corporativo 2',
            'btn btn--white' => 'Botón Blanco',
            'btn' => 'Botón Simple',
            'link' => 'Link'
        ));

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme') ), 
            Field::create( 'text', 'text', __('Button Text', 'mv23theme') )
                ->add_dynamic_data_selector(),
            Field::create( 'select', 'button_style', __('Style', 'mv23theme'))
                ->add_options( $button_styles )
                ->set_default_value( 'btn btn--main-color' ),
    
            Field::create( 'radio', 'button_type',__('Type', 'mv23theme'))
                ->set_default_value( 'link' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'link' => 'Link',
                    'download' => 'Descarga',
                )),
    
            Field::create( 'file', 'file', __('File', 'mv23theme') )->add_dependency('button_type','download','='),
    
            Field::create( 'radio', 'url_type',__('Destination', 'mv23theme'))
                ->set_default_value( 'interna' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'interna' => __('Internal Page', 'mv23theme'),
                    'externa' => __('Other', 'mv23theme'),
                ))->add_dependency('button_type','link','='),
            Field::create( 'wp_object', 'post', '' )->set_button_text( __('Select Page', 'mv23theme') )->add_dependency('button_type','link','=')->add_dependency('url_type','interna','='),
            Field::create( 'text', 'url', '' )->add_dependency('button_type','link','=')->add_dependency('url_type','externa','='),
    
            Field::create( 'checkbox', 'new_tab', __('Open in a new window', 'mv23theme') )->set_text( __('Enable', 'mv23theme') ),

            Field::create( 'tab', __('Icon', 'mv23theme') ),
            Field::create( 'icon', 'icon', __('Icon', 'mv23theme') )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_width( 50 ),
            Field::create( 'radio', 'icon_position', __('Position', 'mv23theme'))->add_options( array(
                'left' => __('Left', 'mv23theme'),
                'right' => __('Right', 'mv23theme')
            ))->set_orientation( 'horizontal' )->set_width(50),
    
            Field::create( 'tab', '_other_settings', __('Other settings','mv23theme') ),
            Field::create( 'checkbox', 'fullwidth', __('Full width button', 'mv23theme') )->set_text( __('Activate', 'mv23theme') ),
            Field::create( 'repeater', 'button_attributes', __('Attributes', 'mv23theme') )->set_add_text(__('Add', 'mv23theme'))
                ->set_layout( 'grid' )
                ->add_group('item', array(
                    'title_template' => '<%= attribute %> : <%= value %>',
                    'fields' => array(
                        Field::create( 'text', 'attribute' )->set_attr('style', 'width: 50%;min-width: unset;'),
                        Field::create( 'text', 'value' )->set_attr('style', 'width: 50%;min-width: unset;'),
                    )
            ))           
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
        $args['html_tag'] = 'a';
		$args['additional_classes'][] = 'component';
        $args['__type'] = 'button-cmp';

        $args['additional_classes'][] = $args['button_style'] ?? 'btn btn--main-color';
        $fullwidth = (isset($args['fullwidth'])) ? $args['fullwidth'] : false;
        if($fullwidth) $args['additional_classes'][] = 'btn-block';
            
        $text = (isset($args['text']) && !empty($args['text'])) ? Handlebars::parse($args['text']) : '';
        $icon = (isset( $args['icon'])) ? $args['icon'] : null;
        if( $icon ) {
            $icon_position = $args['icon_position'] ?: 'left';
            $icon_prefix = (str_starts_with($icon,'fa')) ? 'fa' : 'bi';
            $icon_html = '<i class="'.$icon_prefix.' '.$icon.'"></i>';
            $args['additional_classes'][] = 'btn--icon-'.$args['icon_position'];
        
            $text = ( $icon_position === 'left' ) ? $icon_html.$text : $text.$icon_html;
        } 

        $type = $args['button_type'];
        $href = '#';
        if($type == 'link'){
            $url_type = $args['url_type'];
            switch ($url_type) {
                case 'externa':
                    $href = $args['url'];
                    break;
                
                case 'interna':
                    if($args['post']){
                        $href = get_permalink( str_replace('post_','',$args['post']) );
                    }
                    break;
            }
            if( isset($args['new_tab']) && $args['new_tab'] == 1) {
                $args['additional_attributes']['target'] = '_blank';
            }
                
        }
        if($type == 'download'){
            if($args['file']){
                $href = wp_get_attachment_url( $args['file'] );
                if( isset($args['new_tab']) && $args['new_tab'] == 1) {
                    $args['additional_attributes']['target'] = '_blank';
                } else {
                    $args['additional_attributes']['download'] = 'download';
                }
            }
        }
        $args['additional_attributes']['href'] = $href;

        // Process custom attributes
        $attributes = ( isset($args['button_attributes']) ) ? $args['button_attributes'] : array();
        $additional_attrs = ''; 
        if( is_array($attributes) && count($attributes) > 0 ){
            foreach ($attributes as $item) {
                if( $item['attribute'] && $item['value'] ){
                    if( $item['attribute'] == 'class' ){
                        $args['additional_classes'][] = $item['value'];
                    } else {
                        $args['additional_attributes'][$item['attribute']] = $item['value'];
                    }
                }
            }
        }

        // Check action settings
        $action = Actions::get_code( $args );
        if( $action && $action['attributes'] ){
            foreach ($action['attributes'] as $attr_key => $attr_value) {
                if( $attr_key == 'class' ){
                    $args['additional_classes'][] = $attr_value;
                } else {
                    $args['additional_attributes'][$attr_key] = $attr_value;
                }
            }
        }
		
        $attributes = Template_Engine::generate_attributes( $args );
		ob_start();
        echo '<'.$args['html_tag'].' '.$attributes.'>';
        if($text) echo $text;
        echo '</'.$args['html_tag'].'>';
		return ob_get_clean();
	}
}

new Button();