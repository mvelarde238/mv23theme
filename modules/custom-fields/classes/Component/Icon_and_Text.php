<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use Core\Theme_Options\Theme_Options;

class Icon_and_Text extends Component {

    public function __construct() {
		parent::__construct(
			'icon_and_text',
			__( 'Icon and Text', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-align-pull-left';
    }

    public static function get_title_template() {
		$template = '<%= content.replace(/<[^>]+>/ig, "") %>';
		
		return $template;
	}

    public static function get_layout(){
        return 'table';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', 'Icono'),
        
            Field::create( 'radio', 'ielement','Seleccione que mostrar:')->set_orientation( 'horizontal' )->add_options( array(
                'icono' => 'Icono',
                'imagen' => 'Imagen',
            ))->set_width(20),
            Field::create( 'icon', 'iname', 'Icono' )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->add_dependency('ielement','icono','=')->set_width(20),
            Field::create( 'image', 'iimage', 'Imágen' )->add_dependency('ielement','imagen','=')->set_width(20),

            Field::create( 'image_select', 'iposition', 'Posición')->add_options(array(
                'left'  => array(
                    'label' => 'Izquierda',
                    'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-left.png'
                ),
                'top'  => array(
                    'label' => 'Arriba',
                    'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-top.png'
                ),
                'right'  => array(
                    'label' => 'Derecha',
                    'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-right.png'
                ),
            ))->set_width(30),

            // ALIGMENT
            Field::create( 'select', 'itopalign', __('Icon Aligment', 'default'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->add_options(array(
                    'center'  => 'Al Centro',
                    'left'  => 'Izquierda',
                    'right'  => 'Derecha',
                ))->add_dependency('iposition','top','='),
            Field::create( 'select', 'ialign', __('Icon Alignment', 'default'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->add_options(array(
                    'center'  => 'Al Centro',
                    'flex-start'  => 'Arriba',
                    'flex-end'  => 'Abajo',
                ))->set_width(33)->add_dependency('iposition','top','!='),
            Field::create( 'checkbox', 'hide-icon-on-mobile', 'Ocultar Icono en móviles' )->fancy(),

            // STYLE
            Field::create( 'section', 'icon_style' ),
            Field::create( 'image_select', 'istyle', __('Style','default'))->add_options(array(
                'default'  => array(
                    'label' => 'Normal',
                    'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-default.png'
                ),
                'circle'  => array(
                    'label' => 'Circular',
                    'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-circle.png'
                ),
                'circle-outline'  => array(
                    'label' => 'Circular y Lineal',
                    'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-circle-outline.png'
                ),
            ))->set_width(30),
            Field::create( 'complex', '_icon_styles_wrapper', __('Settings','default') )->merge()->add_fields(array(
                Field::create( 'number', 'ifontsize', 'Tamaño')->set_default_value(40)->set_suffix('px')->set_width(25),
                Field::create( 'color', 'icolor', 'Color del ícono')->set_width(25),
                Field::create( 'checkbox', 'ihas_bgc','Activar fondo' )->fancy()->add_dependency('../istyle','circle-outline','=')->set_width(25),
                Field::create( 'color', 'ibgc', 'Color de Fondo')
                    ->set_default_value( Theme_Options::getInstance()->get_property('primary_color') )
                    ->add_dependency('../istyle','circle','=')
                    ->add_dependency_group()
                    ->add_dependency('../istyle','circle-outline','=')
                    ->add_dependency('ihas_bgc')
                    ->set_width(25),
            )),
    
            // CONTENT
            Field::create( 'tab', 'Texto'),
            Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 10 )->set_width(100),

            // GLOBAL
            Field::create( 'tab', 'Global'),
            Field::create( 'select', 'horizontal_alignment', 'Alineación Horizontal')
                ->set_description( "This setting allows you to align the entire component." )
                ->add_options(array(
                    ''  => __('Default','default'),
                    'left'  => 'Izquierda',
                    'center'  => 'Al Centro',
                    'right'  => 'Derecha'
                ))->set_width(33),
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');
        $args['__type'] = 'icon-and-text';

        if (isset($args['iposition'])) $args['additional_classes'][] = 'icon--'.$args['iposition'];
        if (isset($args['center-all']) && $args['center-all'] == 1) $args['additional_classes'][] = 'center-all';

        $has_horizontal_alignment = (isset($args['horizontal_alignment']) && $args['horizontal_alignment'] != '');
        if ($has_horizontal_alignment) $args['additional_classes'][] = $args['horizontal_alignment'].'-all';

        // **************************************************************************************************
        
        if (isset($args['iposition']) && $args['iposition'] != 'top' && $args['ialign']){
            $args['additional_styles'] = array( "align-items:".$args['ialign'] );
        } 
        
        // **************************************************************************************************
        
        $content = $args['content'];
        $icon_element = $args['ielement'];

        if ($icon_element == 'icono') {
            $icon_prefix = (str_starts_with($args['iname'],'fa')) ? 'fa' : 'bi';
        	$element = '<i class="'.$icon_prefix.' '.$args['iname'].'"></i>';
        } else {
        	$imagen_url = wp_get_attachment_url($args['iimage']);
        	$element = '<img style="height:'.$args['ifontsize'].'px;" src="'.$imagen_url .'" />';
        }

        $icon_style = '';
        $icon_style .= 'font-size:'.$args['ifontsize'].'px;';
        if($args['icolor']) $icon_style .= 'color:'.$args['icolor'].';';
        $icon_style .= (isset($args['iposition']) && $args['iposition'] == 'top' && isset($args['itopalign']) && $args['itopalign']) ? "text-align:".$args['itopalign'].";" : "text-align:center;";
        $icon_style = ($icon_style) ? 'style="'.$icon_style.'"' : '';

        $classes = array('icon-wrapper');
        if($args['istyle']!='default') array_push($classes, 'icon--'.$args['istyle']);
        if(isset($args['hide-icon-on-mobile']) && $args['hide-icon-on-mobile']) array_push($classes, 'hide-on-small-only');
        $icon_class = (!empty($classes)) ? 'class="'.implode(' ',$classes).'"' : '';

        $hasBackground = false;
        if ($args['istyle'] == 'circle' ) $hasBackground = true;
        if ($args['istyle'] == 'circle-outline' && $args['ihas_bgc'] == 1 ) $hasBackground = true;
        $ibgc = ($args['ibgc'] == '') ? Theme_Options::getInstance()->get_property('primary_color') : $args['ibgc'];
        $backgroundColor = ( $hasBackground ) ? $ibgc : '';
		
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);

        echo '<div '.$icon_class.' '.$icon_style.'>';
	    if ($args['istyle']!='default') { echo '<span style="background-color:'.$backgroundColor.'">'; } else { echo '<span>'; };
	    echo $element;
	    echo '</span>';
	    echo '</div>';

        if($content) echo '<div class="content-wrapper">'.do_shortcode(wpautop($content)).'</div>';

        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Icon_and_Text();