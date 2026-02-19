<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Header_Logo extends Component {

    public function __construct() {
		parent::__construct(
			'header-logo',
			__( 'Header Logo', 'mv23theme' ),
            array(
				'common_settings' => array(),
			)
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array(
            Field::create('image', 'static_header_logo', __('Static Header Logo','mv23theme'))->set_width(50),
            Field::create('image', 'sticky_header_logo', __('Sticky Header Logo','mv23theme'))->set_width(50),
        );

		return $fields;
	}

	public static function display( $args ){
        $static_logo_id = $args['static_header_logo'] ?? null;
        $sticky_logo_id = $args['sticky_header_logo'] ?? null;

        if ( $static_logo_id ) {
            $static_logo = wp_get_attachment_image_url( $static_logo_id, 'full' );
        }
        if ( $sticky_logo_id ) {
            $sticky_logo = wp_get_attachment_image_url( $sticky_logo_id, 'full' );
        }

        $blog_title = get_bloginfo( 'name' ); 

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo '<a class="header__logo__link" href="' . esc_url( home_url() ) . '">';
        if ( $static_logo ) {
            echo '<img src="' . esc_url($static_logo) . '" alt="Header Logo" class="static-header-logo">';
        }
        if ( $sticky_logo ) {
            echo '<img src="' . esc_url($sticky_logo) . '" alt="Header Logo" class="sticky-header-logo">';
        }
        if ( !$static_logo && !$sticky_logo ) {
            echo esc_html($blog_title);
        }
        echo '</a>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

	public static function get_view_template() {
        $blog_title = get_bloginfo( 'name' ); 
        
        $template = '<%
        static_header_logo_object = window.UF_Editor?.getPreparedFileObject(static_header_logo);
        sticky_header_logo_object = window.UF_Editor?.getPreparedFileObject(sticky_header_logo);
        static_header_logo_url = static_header_logo_object ? static_header_logo_object.get("url") : "";
        sticky_header_logo_url = sticky_header_logo_object ? sticky_header_logo_object.get("url") : "";
        %>
        <% if (static_header_logo_url) { %>
            <img src="<%= static_header_logo_url %>" alt="Header Logo" class="static-header-logo">
        <% } %>
        <% if (sticky_header_logo_url) { %>
            <img src="<%= sticky_header_logo_url %>" alt="Header Logo" class="sticky-header-logo">
        <% } %>
        <% if (!static_header_logo_url && !sticky_header_logo_url) { %>
            <span>' . esc_html($blog_title) . '</span>
        <% } %>';

		return $template;
	}
}

new Header_Logo();