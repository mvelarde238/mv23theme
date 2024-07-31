<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

if( !defined('PAGE_HEADER_IN') ) define ('PAGE_HEADER_IN', array('page','archive_page'));
if( !defined('UF_TAXONOMIES') ) define ('UF_TAXONOMIES', array('category'));
if( !defined('PAGE_HEADER_CONTENT_BUILDER') ) define ('PAGE_HEADER_CONTENT_BUILDER', true);
if( !defined('PAGE_HEADER_BGC') ) define ('PAGE_HEADER_BGC', '');
if( !defined('PAGE_HEADER_LAYOUT') ) define ('PAGE_HEADER_LAYOUT', 'layout2');
if( !defined('PAGE_HEADER_TEXT_COLOR') ) define ('PAGE_HEADER_TEXT_COLOR', 'text-color-default');
if( !defined('PAGE_HEADER_BGI') ) define ('PAGE_HEADER_BGI', 0);

class Page_Header_Container{
	private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Page_Header_Container();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){
        $this->create_container()
            ->add_fields( $this->create_content_tab() )
            ->add_fields( $this->create_custom_tab() )
            ->add_fields( $this->create_background_tab() )
            ->add_fields( $this->create_settings_tab() );
    }
    
    private function create_container(){
        return Container::create('page_header')
            ->add_location('post_type', PAGE_HEADER_IN)
            ->add_location('taxonomy', UF_TAXONOMIES)
            ->set_title('Page Title')
            ->set_layout('grid')
            ->set_style('seamless')
            ->add_fields(array(
                Field::create('section', 'page_header_section', 'Page Title')->set_color('blue'), 
            ));
    }

    private function create_content_tab(){
        
        $page_header_elements = apply_filters(
            'filter_page_header_elements',
            array(
                'default' => 'Título de la página',
                'slider' => 'Slider',
                'contenido' => 'Contenido',
                'ninguno' => 'Ninguno',
            )
        );

        $content_tab = array(
            Field::create('tab', 'Contenido'),
            Field::create('radio', 'page_header_element', 'Seleccione que tipo de contenido se va mostrar:')->set_orientation('horizontal')->add_options($page_header_elements),
            Field::create('textarea', 'slider_desktop')->set_rows(1)->set_width(50)->add_dependency('page_header_element', 'slider', '='),
            Field::create('textarea', 'slider_movil')->set_rows(1)->set_width(50)->add_dependency('page_header_element', 'slider', '=')
        );

        if( PAGE_HEADER_CONTENT_BUILDER ){
            $content_tab[] = Content_Layout::the_field(array( 
                'slug' => 'page_header_content2', 
                'components' => array( 'editor-de-texto', 'imagen', 'separador' )
            ))->add_dependency( 'page_header_element', 'contenido', '=' );
        } else {
            $content_tab[] = Field::create('wysiwyg', 'page_header_content')->hide_label()->set_rows(1)->add_dependency('page_header_element', 'contenido', '=');
        }

        $content_tab = apply_filters( 'filter_page_header_content_tab', $content_tab );

        return $content_tab;
    }

    private function create_background_tab(){
        $page_header_bgcolor_active = (PAGE_HEADER_BGC) ? 1 : 0;

        $background_tab = array(
            Field::create('tab', 'Fondo'),
            Field::create('image', 'page_header_bgi', 'Imágen de Fondo')->set_width(20)->set_default_value(PAGE_HEADER_BGI),
            Field::create('complex', 'page_header_video')->add_fields(array(
                Field::create( 'video', 'files', 'Video de Fondo' ),
                Field::create( 'number', 'opacity', 'Transparencia del video' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )
            ))->set_width(20)->hide_label(),
            
            Field::create( 'complex', 'page_header_bgcolor', 'Color de fondo' )->add_fields(array(
                Field::create( 'checkbox', 'add_bgc', 'Color de fondo' )->set_text('Activar')->set_default_value($page_header_bgcolor_active),
                Field::create( 'color', 'bgc', 'Color' )->set_default_value(PAGE_HEADER_BGC)->add_dependency('add_bgc'),
                // Field::create( 'text', 'alpha', 'Transparencia' )->add_dependency('add_bgc')->set_default_value('100')->set_description('Usar un número del 1 al 100'),
            ))->set_width(20)->hide_label(),
            
            Field::create('select', 'page_header_text_color', 'Color del texto')->set_width(20)->add_options(array(
                'text-color-2' => 'Blanco',
                'text-color-default' => 'Negro',
            ))->set_default_value(PAGE_HEADER_TEXT_COLOR),
            Field::create('radio', 'page_header_bgi_parallax', 'Parallax')->set_width(20)->add_options(array(
                '0' => 'Desactivar',
                '1' => 'Activar',
            ))->set_orientation('horizontal')->set_default_value('0'),
        );

        return $background_tab;
    }

    private function create_custom_tab(){
        return apply_filters( 'filter_page_header_custom_tab', array() );
    }

    private function create_settings_tab(){
        $settings_tab = array(
            Field::create('tab', 'Settings'),
            Field::create('text', 'page_header_id', 'ID')->set_width(50)->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                ->set_description('Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )'),
            Field::create('text', 'page_header_class', 'Clases')->set_width(50)
                ->set_description('Clases de la sección, usar solo minúsculas y guiones ( -/_ )'),
            Field::create('checkbox', 'page_header_padding', 'Márgenes')->set_width(33)->set_text('Borrar Márgenes'),
            Field::create('select', 'page_header_layout')->set_width(33)->add_options(array(
                'layout2' => 'Fondo extendido / Contenido centrado',
                'layout1' => 'Estándar',
                'layout3' => 'Todo extendido',
            ))->set_default_value(PAGE_HEADER_LAYOUT),
        );

        return $settings_tab;
    }
}

Page_Header_Container::getInstance();