<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Frontend\Nav_Walker;

class Menu extends Component {

    public function __construct() {
		parent::__construct(
			'menu',
			__( 'Menu', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-menu-alt2';
    }

    public static function get_title_template() {
		$template = '<% if ( type == "menu" && menu != 0 || type == "location" && location ){ %>
            <%= ( type == "menu" ) ? "Menu ID: "+menu+ " | Style: "+style : "There isnt any menu selected" %>
            <%= ( type == "location" ) ? "Menu Location: "+location+ " | Style: "+style : "There isnt any menu location selected" %>
		<% } else { %>
            This component is empty
        <% } %>';

		return $template;
	}

    public static function get_menu_styles() {
        $menu_styles = apply_filters( 
            'theme_nav_styles', 
            array( 
                array( 'slug' => 'horizontal-nav-1', 'name' => __('Horizontal Nav 1','mv23theme'), 'image' => '' ),
                array( 'slug' => 'horizontal-nav-2', 'name' => __('Horizontal Nav 2','mv23theme'), 'image' => '' ),
                array( 'slug' => 'horizontal-nav-3', 'name' => __('Horizontal Nav 3','mv23theme'), 'image' => '' ),
                array( 'slug' => 'horizontal-nav-4', 'name' => __('Horizontal Nav 4','mv23theme'), 'image' => '' ),
                array( 'slug' => 'vertical-nav-1', 'name' => __('Vertical Nav 1','mv23theme'), 'image' => '' ),
                array( 'slug' => 'unordered-list', 'name' => __('None','mv23theme'), 'image' => '' )
            ) 
        );
        return $menu_styles;
    }

    public static function get_menu_styles_image_select() {
        $images_path = BUILDER_PATH.'/assets/images/';
        $menu_styles = self::get_menu_styles();
        $menu_styles_image_select = array();
        foreach ($menu_styles as $style) {
            $image_path = ( $style['image'] != '' ) ? $style['image'] : $images_path.$style['slug'].'.png';
            $menu_styles_image_select[ $style['slug'] ] = array( 'label' => $style['name'], 'image' => $image_path );
        }
        return $menu_styles_image_select;
    }

	public static function get_fields() {
        $menu_styles_image_select = self::get_menu_styles_image_select();

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme')),
            Field::create( 'radio', 'type', __('Select','mv23theme') )->add_options(array(
                'menu'     => __( 'Show a particular menu', 'mv23theme' ),
                'location' => __( 'Select a location. If a menu is assigned to that location, it will be displayed', 'mv23theme' ),
            ))->set_width(50),
            Field::create( 'select', 'menu' )
                ->add_terms( 'nav_menu' )
                ->add_dependency('type','menu','=')
                ->set_width(50),
            Field::create( 'select', 'location' )
                ->add_options( get_registered_nav_menus() )
                ->add_dependency('type','location','=')
                ->set_width(50),
            Field::create( 'image_select', 'style', __('Style','mv23theme') )
                ->add_options( $menu_styles_image_select )
                ->add_dependency( 'type', 'menu' )
                ->add_dependency( 'menu', '0', '!=' )
                ->add_dependency_group()
                ->add_dependency( 'type', 'location' ),
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');
		$args['__type'] = 'menu-comp';

        $type = $args['type'];
        $menu = $args['menu'];
        $location = $args['location'];
        $style = ( $args['style'] != 'unordered-list' ) ? $args['style'] : '';
        $orientation_nav_class = ( str_contains($style,'horizontal') ) ? 'horizontal-nav' : 'vertical-nav';

        $args['additional_classes'][] = $orientation_nav_class;
        if( $style ) $args['additional_classes'][] = $style;
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        if( $type === 'menu' ){
            wp_nav_menu(array(
                'menu' => $menu,
                'container' => false,                           
                'container_class' => '',
                'walker' => new Nav_Walker(),
            ));
        }
        if( $type === 'location' ){
            wp_nav_menu( array('theme_location' => $location, 'walker' => new Nav_Walker()) );
        }

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Menu();