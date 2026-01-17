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
            ->add_fields(array(
                Field::create( 'tab', 'builder_options_tab' )->set_label( __('Builder Posttypes','mv23theme') ),
                Field::create( 'message', 'builder_posttypes_description' )
                    ->set_description( __('Select the post types where you want to enable the Ultimate Builder.','mv23theme') )
                    ->hide_label(),
                Field::create( 'multiselect', 'builder_posttypes', __( 'Post Types', 'mv23theme' ) )
                    ->set_options_callback( function() {
                        return Core::get_post_types(array(
                            'exclude_post_types' => array('offcanvas_element','attachment','mv23_library')
                        ));
                    } )
                    ->set_orientation( 'horizontal' )
                    ->set_input_type( 'checkbox' )
                    ->hide_label()
                    // TODO: check why default value is not working
                    ->set_default_value( array('page','megamenu','archive_page','footer','reusable_section') ),

                Field::create( 'tab', 'hide_wp_editor_tab' )->set_label( __('Hide WP Text Editor','mv23theme') ),
                Field::create( 'message', 'hide_wp_editor_description' )
                    ->set_description( __('Select the post types where you want to hide the default WordPress text editor.','mv23theme') )
                    ->hide_label(),
                Field::create( 'multiselect', 'hide_wp_editor_on', __( 'Hide WP Text Editor On', 'mv23theme' ) )
                    ->set_options_callback( array( Core::class, 'get_post_types' ) )
                    ->set_orientation( 'horizontal' )
                    ->set_input_type( 'checkbox' )
                    ->hide_label(),

                Field::create( 'tab', 'insert_single_structure_tab' )->set_label( __('Insert Single Structure','mv23theme') ),
                Field::create( 'message', 'insert_single_structure_description' )
                    ->set_description( __('Select the post types where you want to insert the single structure when creating a new post.','mv23theme') )
                    ->hide_label(),
                Field::create( 'multiselect', 'insert_single_structure_on', __( 'Insert Single Structure On', 'mv23theme' ) )
                    ->set_options_callback( function() {
                        $options = array();
                        $builder_posttypes = get_option( 'builder_posttypes', array());
                        foreach ( $builder_posttypes as $post_type ) {
                            $options[ $post_type ] = get_post_type_object( $post_type )->labels->singular_name;
                        }
                        return $options;
                    } )
                    ->set_orientation( 'horizontal' )
                    ->set_input_type( 'checkbox' )
                    ->hide_label(),
            ));
    }
}