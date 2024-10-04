<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

class Accordion {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Accordion();
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $accordions = new CPT(
            array(
                'post_type_name' => 'v23accordion',
                'singular' => 'Accordion',
                'plural' => 'Accordion',
            ), 
            array(
                'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'exclude_from_search' => true,
                'supports' => array('title')
                )
            );

        $accordions->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'shortcode' => __('Shortcode'),
            'date' => __('Date')
        ));
            
        $accordions->populate_column('shortcode', function($column_name, $post) {
            echo '<code>[accordion id="'.$post->ID.'"]</code>';
        });
    }

    public function add_meta_boxes(){
        Container::create( 'accordion_settings' )->add_location( 'post_type', array('v23accordion') )->set_layout( 'grid' )->add_fields( array(
            Field::create( 'tab', 'Contenido' ),
            Field::create( 'repeater', 'accordion', '' )
                ->set_add_text('Agregar Fila')
                ->add_group('Item', array(
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'tab', 'Título' ),
                        Field::create( 'text', 'titulo', 'Título' )->set_width( 33 ),
                        Field::create( 'radio', 'identificador','Seleccione que mostrar antes del título:')->set_orientation( 'horizontal' )->add_options( array(
                                'icono' => 'Icono',
                                'imagen' => 'Imagen',
                            ) )->set_width( 33 ),
                        Field::create( 'icon', 'icon', 'Icono' )->add_set( 'font-awesome' )->set_width( 33 )->add_dependency('identificador','icono','='),
                        Field::create( 'image', 'imagen', 'Imágen' )->set_width( 33 )->add_dependency('identificador','imagen','='),
                        Field::create( 'tab', 'Contenido' ),
                        Field::create( 'radio', 'content_element','Seleccione que mostrar como Contenido:')->set_orientation( 'horizontal' )->add_options( array(
                                'texto' => 'Texto',
                                // 'pagina' => 'Página',
                            ) ),
                        Field::create( 'wysiwyg', 'content', 'Contenido' )->add_dependency('content_element','texto','=')->hide_label()->set_rows( 30 ),
                        // Field::create( 'wp_objects', 'page', 'Página' )->add( 'posts', 'page' )->set_button_text( 'Selecciona la página' )->add_dependency('content_element','pagina','=')->hide_label(),
                    )
                )
            ),
            Field::create( 'select', 'desktop_template', 'Apariencia en Desktop' )->add_options( array(    
                'accordion' => 'Accordion',
                'tab' => 'Tab',
            )),
        ));
    }
}

