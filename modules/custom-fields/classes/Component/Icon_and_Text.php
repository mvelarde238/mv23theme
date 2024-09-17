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
    
            Field::create( 'image_select', 'istyle', 'Estilo')->add_options(array(
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
    
    
            // Field::create( 'image_select', 'ialign', 'Alineación')->add_options(array(
            //     'center'  => array(
            //         'label' => 'Centro',
            //         'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icono-centro.png'
            //     ),
            //     'flex-start'  => array(
            //         'label' => 'Arriba',
            //         'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icono-arriba.png'
            //     ),
            //     'flex-end'  => array(
            //         'label' => 'Abajo',
            //         'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icono-abajo.png'
            //     ),
            // ))->set_width(33)->add_dependency('iposition','top','!='),
    
            // Field::create( 'image_select', 'itopalign', 'Alineación')->add_options(array(
            //     'center'  => array(
            //         'label' => 'Centro',
            //         'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-top.png'
            //     ),
            //     'left'  => array(
            //         'label' => 'Izquierda',
            //         'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-top-left.png'
            //     ),
            //     'right'  => array(
            //         'label' => 'Derecha',
            //         'image' =>  THEME_CUSTOM_FIELDS_PATH.'/assets/images/icon-top-right.png'
            //     ),
            // ))->set_width(33)->add_dependency('iposition','top','='),
    
            Field::create( 'number', 'ifontsize', 'Tamaño')->set_width(10)->set_default_value(40),
            Field::create( 'color', 'icolor', 'Color del ícono')->set_width(25),
            Field::create( 'checkbox', 'ihas_bgc','Activar fondo' )->set_text( 'Activar' )->set_width(10)->add_dependency('istyle','circle-outline','='),
            Field::create( 'color', 'ibgc', 'Color de Fondo')->set_width(25)->set_default_value( Theme_Options::getInstance()->get_property('primary_color') )
                ->add_dependency('istyle','circle','=')
                ->add_dependency_group()
                ->add_dependency('istyle','circle-outline','=')
                ->add_dependency('ihas_bgc'),
            Field::create( 'select', 'itopalign', 'Alineación')->add_options(array(
                'center'  => 'Al Centro',
                'left'  => 'Izquierda',
                'right'  => 'Derecha',
            ))->add_dependency('iposition','top','=')->set_width(20),
    
            // Field::create( 'section', 'Texto' ),
            Field::create( 'tab', 'Texto'),
            Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 10 )->set_width(100),
            Field::create( 'select', 'ialign', 'Alineación Vertical')->add_options(array(
                'center'  => 'Al Centro',
                'flex-start'  => 'Arriba',
                'flex-end'  => 'Abajo',
            ))->set_width(33)->add_dependency('iposition','top','!='),
            Field::create( 'select', 'horizontal-align', 'Alineación Horizontal')->add_options(array(
                'left'  => 'Izquierda',
                'center'  => 'Al Centro',
                'right'  => 'Derecha'
            ))->set_width(33),
    
            Field::create( 'tab', 'Responsive' ),
            Field::create( 'checkbox', 'hide-icon-on-mobile' )->set_text( 'Ocultar Icono en moviles' )->hide_label(),
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');
        $args['__type'] = 'icon-and-text';

        if (isset($args['iposition'])) $args['additional_classes'][] = 'icon--'.$args['iposition'];
        if (isset($args['center-all']) && $args['center-all'] == 1) $args['additional_classes'][] = 'center-all';
        if (isset($args['horizontal-align'])) $args['additional_classes'][] = $args['horizontal-align'].'-all';

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

        $classes = array('icon');
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
		?>
        <div <?=$icon_class?> <?=$icon_style?>>
	    	<?php if ($args['istyle']!='default') { echo '<span style="background-color:'.$backgroundColor.'">'; } else { echo '<span>'; }; ?>
	    		<?php echo $element; ?>
	    	</span>
	    </div>
	    <div><?php if($content) echo do_shortcode(wpautop($content)); ?></div>
		<?php
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Icon_and_Text();