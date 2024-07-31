<?php
namespace Theme_Custom_Fields\Component;

use Theme_Custom_Fields\Component;
use Ultimate_Fields\Field;
use Content_Selector;
use Ultimate_Fields\Container\Repeater_Group;
use Theme_Custom_Fields\Template_Engine;
use Theme_Custom_Fields\Common_Settings;

class Page_Module extends Component{

    private function __construct() {}
    
    public static function the_group() {
        $page_module_group = Repeater_Group::create( 'Módulos' )
            ->set_title( __( 'Module', 'default' ) )
            ->set_icon( 'dashicons dashicons-welcome-widgets-menus' )
            ->add_fields(array( 
                Field::create( 'tab', __('Content','default') ),
                Content_Selector::the_field('componentes', __('Components','default'), array( 'exclude' => array('columnas-internas', 'card') ) ),
                Field::create( 'tab', __('Settings','default') )
            ))
            ->add_fields( Common_Settings::get_fields('id-and-class') )
            ->add_fields( self::get_fields() );

        parent::add_common_settings( $page_module_group, self::get_common_settings() );

        Template_Engine::getInstance()->register_component( $page_module_group->get_id(), get_called_class() );

        return $page_module_group;
    }

	public static function get_fields() {
        $fields = array( 
            Field::create('select', 'visibility', 'Visibilidad')->add_options(array(
                '' => 'Visible para todos los usuarios',
                'user_is_logged_in' => 'Visible para usuarios registrados',
                'user_is_not_logged_in' => 'Visible para usuarios no registrados',
                'is_private' => 'Solo visible para usuarios admin.',
            ))->set_width(33),
            // is diferent to common settings, layout2 is the default:
            Field::create('select', 'layout')->add_options(array(
                'layout2' => 'Fondo extendido / Contenido centrado',
                'layout1' => 'Estándar',
                'layout3' => 'Todo extendido',
            ))->set_width(33),
            // is diferent to common settings, property margin <-> padding:
            Field::create('checkbox', 'delete_margins')->set_text('Quitar Márgenes')->hide_label()->set_attr('style', 'background: #eeeeee; width: 100%'),
            Field::create('complex', 'padding', 'Borrar Márgenes')->hide_label()->add_fields(array(
                Field::create('checkbox', 'top')->set_width(25)->set_text('Superior')->hide_label(),
                Field::create('checkbox', 'bottom')->set_width(25)->set_text('Inferior')->hide_label(),
            ))->add_dependency('delete_margins'),
            // is diferent to common settings, background is inside a checkbox:
            Field::create('checkbox', 'edit_background')->set_text('Editar Fondo')->hide_label()->set_attr('style', 'background: #eeeeee; width: 100%'),
            Field::create('image', 'bgi', 'Imágen de Fondo')->set_width(20)->add_dependency('edit_background'),
            Field::create('complex', 'bgi_options', '')->set_width(20)->add_fields(array(
                Field::create('select', 'size', 'Tamaño')->add_options(array(
                    'cover' => 'Cubrir Todo',
                    'auto' => 'Automático',
                )),
                Field::create('select', 'repeat', 'Repetir')->add_options(array(
                    'no-repeat' => 'No Repetir',
                    'repeat' => 'Ambas direcciones',
                    'repeat-x' => 'Solo horizontal',
                    'repeat-y' => 'Solo en vertical',
                )),
                Field::create('select', 'position_x', 'Posición Eje Horizontal')->add_options(array(
                    'center' => 'Centro',
                    'left' => 'Izquierda',
                    'right' => 'Derecha',
                )),
                Field::create('select', 'position_y', 'Posición Eje Vertical')->add_options(array(
                    'center' => 'Centro',
                    'top' => 'Arriba',
                    'bottom' => 'Abajo',
                )),
            ))->add_dependency('bgi', '0', '>')->add_dependency('edit_background'),
            // is diferent to common settings, field slug name and add parallax
            Field::create('checkbox', 'add_bgc', 'Usar color de fondo')->set_width(20)->set_text('Activar')->add_dependency('edit_background'),
            Field::create('color', 'bgc', 'Color de Fondo')->set_width(20)->set_default_value('#ffffff')->add_dependency('add_bgc')->add_dependency('edit_background'),
            Field::create('select', 'text_color', 'Color del texto')->add_options(array(
                'text-color-default' => 'Negro',
                'text-color-2' => 'Blanco',
            ))->set_default_value(DEFAULT_TEXT_COLOR)->set_width(20)->add_dependency('edit_background'),
            Field::create('checkbox', 'parallax', 'Parallax')->set_width(20)->add_dependency('edit_background')
        );

		return $fields;
	}

    public static function get_common_settings() {
		return array( 'borders', 'video-background', 'scroll-animations' );
	}

    public static function display( $args ){
        $visibility = (isset($args['visibility'])) ? $args['visibility'] : '';
	    if ($visibility == 'is_private' && !current_user_can('administrator')) return;
	    if ($visibility == 'user_is_logged_in' && !is_user_logged_in()) return;
	    if ($visibility == 'user_is_not_logged_in' && is_user_logged_in()) return;
        
		$args['additional_classes'] = array('page-module','cf','editor-de-texto');
		// $args['__type'] = ''; cant delete, $no_padding_components need the type
	    if (isset($args['parallax']) && $args['parallax'] == 1) $args['additional_classes'][] = 'parallax';
	    $components = $args['componentes'];

        $attributes = Template_Engine::generate_attributes( $args );

		ob_start();
        echo '<section '.$attributes.'>';
        echo Template_Engine::check_video_background( $args );
        echo Template_Engine::check_layout('start', $args);

		foreach ($components as $component) {
			echo Template_Engine::getInstance()->handle( $component['__type'], $component );
		}
		
        echo Template_Engine::check_layout('end', $args);
        echo '</section>';
		return ob_get_clean();
	}
}