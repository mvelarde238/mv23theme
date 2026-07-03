<?php
namespace Core\Theme_Options\UF_Container;

use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Core;

class Builder_Options{

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Builder_Options();
        }
        return self::$instance;
    }

    private function __construct(){}

    public static function init(){
        Container::create( 'builder_options_container' ) 
            ->set_title( __('Builder Options','mv23theme') )
            ->add_location( 'options', 'theme-options', array(
                'context' => 'side'
            ))
            ->set_fields_callback( array( self::getInstance(), 'get_fields' ) );
    }

    public static function get_fields(){
        $fields = array();

        $posttypes = Core::get_post_types();

        $fields[] = Field::create( 'tab', 'builder_options_tab' )->set_label( __('Builder Posttypes','mv23theme') );

        $fields[] = Field::create( 'message', 'builder_posttypes_description' )
            ->set_description( __('Select the post types where you want to enable the Ultimate Builder.','mv23theme') )
            ->hide_label();

        $fields[] = Field::create( 'multiselect', 'builder_posttypes', __( 'Post Types', 'mv23theme' ) )
            // ->set_options_callback( array( Core::class, 'get_post_types' ) )
            ->add_options( $posttypes )
            ->set_orientation( 'horizontal' )
            ->set_input_type( 'checkbox' )
            ->hide_label()
            // TODO: check why default value is not working
            ->set_default_value( DEFAULT_BUILDER_POSTTYPES );

        $fields[] = Field::create( 'tab', 'hide_wp_editor_tab' )->set_label( __('Hide WP Text Editor','mv23theme') );
        
        $fields[] = Field::create( 'message', 'hide_wp_editor_description' )
            ->set_description( __('Select the post types where you want to hide the default WordPress text editor.','mv23theme') )
            ->hide_label();
            
        $fields[] = Field::create( 'multiselect', 'hide_wp_editor_on', __( 'Hide WP Text Editor On', 'mv23theme' ) )
            ->add_options( $posttypes )
            ->set_orientation( 'horizontal' )
            ->set_input_type( 'checkbox' )
            ->hide_label();

        return $fields;
    }
}