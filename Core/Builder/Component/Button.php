<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

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
            '' => 'Link'
        ));

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme') ), 
            Field::create( 'text', 'text', __('Button Text', 'mv23theme') )
                ->required()
                ->set_default_value( 'I am a button' ),
            Field::create( 'select', 'style', __('Style', 'mv23theme'))
                ->add_options( $button_styles )
                ->set_default_value( 'btn btn--main-color' ),
    
            Field::create( 'radio', 'type',__('Type', 'mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                'link' => 'Link',
                'download' => 'Descarga',
            )),
    
            Field::create( 'file', 'file', __('File', 'mv23theme') )->add_dependency('type','download','='),
    
            Field::create( 'radio', 'url_type',__('Destination', 'mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                'interna' => __('Internal Page', 'mv23theme'),
                'externa' => __('Other', 'mv23theme'),
            ))->add_dependency('type','link','='),
            Field::create( 'wp_object', 'post', '' )->set_button_text( __('Select Page', 'mv23theme') )->add_dependency('type','link','=')->add_dependency('url_type','interna','='),
            Field::create( 'text', 'url', '' )->add_dependency('type','link','=')->add_dependency('url_type','externa','='),
    
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
            Field::create( 'radio', 'size', __('Size', 'mv23theme'))->add_options( array(
                'small' => __('Normal', 'mv23theme'),
                'medium' => __('Mediano', 'mv23theme'),
                'big' => __('Grande', 'mv23theme')
            ))->set_orientation( 'horizontal' ),
            Field::create( 'checkbox', 'fullwidth', __('Botón de ancho completo', 'mv23theme') )->set_text( __('Activar', 'mv23theme') ),
            Field::create( 'repeater', 'attributes', __('Attributos', 'mv23theme') )->set_add_text(__('Agregar', 'mv23theme'))
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
        
		$args['additional_classes'] = array('component');
        $args['__type'] = 'button-cmp';

        $class = $args['style'];
        $fullwidth = (isset($args['fullwidth'])) ? $args['fullwidth'] : false;
        if($fullwidth) $class .= ' btn-block';
            
        $text = $args['text'] ?: 'Botón';
        $icon = (isset( $args['icon'])) ? $args['icon'] : null;
        if( $icon ) {
            $icon_position = $args['icon_position'] ?: 'left';
            $icon_prefix = (str_starts_with($icon,'fa')) ? 'fa' : 'bi';
            $icon_html = '<i class="'.$icon_prefix.' '.$icon.'"></i>';
            $class .= ' btn--icon-'.$args['icon_position'];
        
            $text = ( $icon_position === 'left' ) ? $icon_html.' '.$text : $text.' '.$icon_html;
        } 
        
        $size = (isset($args['size'])) ? $args['size'] : false;
        if($size) $class .= ' btn--'.$args['size'];

        $type = $args['type'];
        $href = '#';
        $attrs = '';
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
            $attrs = ( isset($args['new_tab']) && $args['new_tab'] == 1) ? 'target="_blank"' : ''; 
        }
        if($type == 'download'){
            if($args['file']){
                $href = wp_get_attachment_url( $args['file'] );
                $attrs = ( isset($args['new_tab']) && $args['new_tab'] == 1) ? 'target="_blank"' : 'download'; 
            }
        }

        $attributes = ( isset($args['attributes']) ) ? $args['attributes'] : array();
        $additional_attrs = '';
        if( is_array($attributes) && count($attributes) > 0 ){
            foreach ($attributes as $item) {
                if( $item['attribute'] && $item['value'] ){
                    if( $item['attribute'] == 'class' ){
                        $class .= ' '.$item['value'];
                    } else {
                        $additional_attrs .= ' '.$item['attribute'].'="'.$item['value'].'"';
                    }
                }
            }
        }
		
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        if($text) echo '<a href="'.$href.'" '.$attrs.' class="'.$class.'"'.$additional_attrs.'>'.$text.'</a>';
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
        ob_start(); 
        printf(
            '<a href="#" class="%s%s%s%s">',
            '<%= style %>',
            '<% if (icon && icon_position) { %> btn--icon-<%= icon_position %><% } %>',
            '<% if (size) { %> btn--<%= size %><% } %>',
            '<% if (fullwidth) { %> btn-block<% } %>'
        );
        ?>
            <% if (icon && icon_position === 'left') { %>
                <i class="<% if (icon.startsWith('fa')) { %>fa <% } else { %>bi <% } %><%= icon %>"></i>
            <% } %>
            <% if (text) { %><%= text %><% } else { %>Button<% } %>
            <% if (icon && icon_position === 'right') { %>
                <i class="<% if (icon.startsWith('fa')) { %>fa <% } else { %>bi <% } %><%= icon %>"></i>
            <% } %>
        </a>
        <?php return ob_get_clean();
    }
}

new Button();