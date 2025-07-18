<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Content_Selector;
use Ultimate_Fields\Container\Repeater_Group;
use Core\Builder\Template_Engine;

class Page_Module extends Component{

    private function __construct() {}
    
    public static function the_group() {
        $page_module_group = Repeater_Group::create( 'page_module' )
            ->set_title( __( 'Module', 'mv23theme' ) )
            ->set_icon( 'dashicons dashicons-welcome-widgets-menus' );

        parent::add_common_settings( $page_module_group );
        $page_module_group->add_fields( self::get_fields() );

        Template_Engine::getInstance()->register_component( $page_module_group->get_id(), get_called_class() );

        return $page_module_group;
    }

	public static function get_fields() {
        $fields = array( 
            Content_Selector::the_field('components', __('Components','mv23theme'), array( 'exclude' => array('card') ) )
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
	    $components = $args['components'];
        $attributes = Template_Engine::generate_attributes( $args );

		ob_start();
        echo Template_Engine::check_full_width('start', $args);
        echo '<section '.$attributes.'>';
        do_action( 'after_component_wrapper_start', $args );
        echo Template_Engine::check_video_background( $args );
        echo Template_Engine::check_slider_background( $args );
        echo Template_Engine::check_layout('start', $args);

		foreach ($components as $component) {
			echo Template_Engine::getInstance()->handle( $component['__type'], $component );
		}
		
        echo Template_Engine::check_layout('end', $args);
        do_action( 'before_component_wrapper_end', $args );
        echo '</section>';
        echo Template_Engine::check_full_width('end', $args);
		return ob_get_clean();
	}
}