<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Layout_Group;
use Core\Builder\Blocks_Layout;

if( !defined('PAGE_HEADER_IN') ) define ('PAGE_HEADER_IN', array('page','archive_page'));
if( !defined('UF_TAXONOMIES') ) define ('UF_TAXONOMIES', array('category'));
if( !defined('PAGE_HEADER_COMPONENTS') ) define ('PAGE_HEADER_COMPONENTS', array( 'text_editor', 'image', 'slider', 'spacer', 'button', 'components_wrapper' ));

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
        $this->create_container()->add_fields( $this->create_fields() );
    }
    
    private function create_container(){
        return Container::create('page_header')
            ->add_location('post_type', PAGE_HEADER_IN)
            ->add_location('taxonomy', UF_TAXONOMIES)
            ->set_title(__('Page Header','mv23theme'))
            ->set_layout('grid');
    }

    private function create_fields(){
        
        $page_header_content_types = apply_filters(
            'filter_page_header_content_types',
            array(
                'default' => __('Page Title','mv23theme'),
                'slider' => 'Slider',
                'content' => __('Content','mv23theme'),
                'none' => __('None','mv23theme')
            )
        );

        $content_fields = array(
            Field::create( 'radio', 'page_header_content_type', __('Content type','mv23theme'))->set_orientation('horizontal')->add_options($page_header_content_types)->set_width(50),
            Field::create( 'common_settings_control', 'page_header_settings', __('Settings','mv23theme') )->set_container( 'common_settings_container' )->set_width(50),
            Field::create( 'complex', 'page_header_slider' )->add_dependency('page_header_content_type', 'slider', '=')->hide_label()->add_fields(array(
                Field::create('textarea', 'desktop')->set_rows(1)->set_width(50),
                Field::create('textarea', 'mobile')->set_rows(1)->set_width(50),
            ))->set_width(50),
            Blocks_Layout::the_field(array( 
                'slug' => 'page_header_content', 
                'components' => PAGE_HEADER_COMPONENTS
            ))->add_dependency( 'page_header_content_type', 'content', '=' ),
            // FAKE OCE SELECTOR FOR AJAX CALLS INSIDE POP UP
            Field::create( 'wp_object', 'id' )->add( 'posts','post_type=offcanvas_element' )->add_dependency( 'page_header_content_type', '_ALWAYS_HIDDEN', '=' )
        );

        $content_fields = apply_filters( 'filter_page_header_content_fields', $content_fields );

        return $content_fields;
    }
}

Page_Header_Container::getInstance();