<?php
namespace Core\Admin;
use \Core\Frontend\Nav_Walker;
use WP_Nav_Menu_Widget;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use \Core\Builder\Component\Menu as MenuComponent;

class Extend_Nav_Menu_Widget extends WP_Nav_Menu_Widget {

    public function __construct() {

        $menu_styles_image_select = MenuComponent::get_menu_styles_image_select();
        
        Container::create( 'wysiwyg_widget' )
	        ->add_location( 'widget', 'Core\Admin\Extend_Nav_Menu_Widget' )
            ->add_fields(array(
                Field::create( 'image_select', 'style', __('Style','mv23theme') )->add_options( $menu_styles_image_select )
            ));

        parent::__construct();
    }

    public function widget($args, $instance) {
        $nav_menu = !empty($instance['nav_menu']) ? wp_get_nav_menu_object($instance['nav_menu']) : false;

        if (!$nav_menu) return;

        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'];
            echo apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
            echo $args['after_title'];
        }

        $nav_style = ( get_value( 'style', 'widget' ) != 'unordered-list' ) ? get_value( 'style', 'widget' ) : '';
        $orientation_nav_class = ( str_contains($nav_style,'horizontal') ) ? 'horizontal-nav' : 'vertical-nav';

        echo '<div class="' . esc_attr($nav_style) . ' ' . esc_attr($orientation_nav_class) . '">';

        wp_nav_menu(array(
            'fallback_cb' => '',
            'menu'        => $nav_menu,
            'walker'      => new Nav_Walker(),
        ));

        echo '</div>';

        echo $args['after_widget'];
    }
}