<?php
namespace Theme_Custom_Fields\Component;

use Theme_Custom_Fields\Component;
use Ultimate_Fields\Field;
use Content_Selector;
use Ultimate_Fields\Container\Repeater_Group;
use Theme_Custom_Fields\Template_Engine;

class Page_Module extends Component{

    private function __construct() {}
    
    public static function the_group() {
        $page_module_group = Repeater_Group::create( 'page_module' )
            ->set_title( __( 'Module', 'default' ) )
            ->set_icon( 'dashicons dashicons-welcome-widgets-menus' )
            ->add_fields( self::get_fields() );

        parent::add_common_settings( $page_module_group, self::get_common_settings() );

        Template_Engine::getInstance()->register_component( $page_module_group->get_id(), get_called_class() );

        return $page_module_group;
    }

	public static function get_fields() {
        $fields = array( 
            Field::create( 'tab', __('Content','default') ),
            Content_Selector::the_field('components', __('Components','default'), array( 'exclude' => array('inner_columns', 'card') ) )
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
	    $components = $args['components'];
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