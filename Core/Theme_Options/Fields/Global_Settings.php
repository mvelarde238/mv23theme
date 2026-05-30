<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Global_Settings {
    public static function get_fields(){
        $fields = array(
            Field::create( 'tab', 'global_settings' )
        );

        // Header and Footer
        $header_default_value = get_option( 'theme_header_post', '' );
        $footer_default_value = get_option( 'theme_footer_post', '' );
        $fields[] = Field::create( 'complex', 'header_and_footer_wrapper', __('Header and Footer','mv23theme') )->merge()->add_fields(array(
            Field::create( 'wp_object', 'theme_header_post', __('Header','mv23theme') )
                ->set_default_value($header_default_value)
                ->add( 'posts', 'post_type=header' )
                ->set_width(50),
            Field::create( 'wp_object', 'theme_footer_post', __('Footer','mv23theme') )
                ->set_default_value($footer_default_value)
                ->add( 'posts', 'post_type=footer' )
                ->set_width(50),
        ));

        // Classic Editor
        $activate_classic_editor_default           = get_option( 'activate_classic_editor', false );
        $disable_gutenberg_frontend_styles_default = get_option( 'disable_gutenberg_frontend_styles', false );
        $fields[] = Field::create( 'complex', 'classic_editor_wrapper', __( 'Classic Editor', 'mv23theme' ) )->merge()->add_fields( array(
            Field::create( 'checkbox', 'activate_classic_editor' )
                ->set_default_value( $activate_classic_editor_default )
                ->set_text( __( 'Disable Gutenberg & enable Classic Editor + Classic Widgets', 'mv23theme' ) )
                ->fancy()
                ->hide_label()
                ->set_width( 50 ),
            Field::create( 'checkbox', 'disable_gutenberg_frontend_styles' )
                ->set_default_value( $disable_gutenberg_frontend_styles_default )
                ->set_text( __( 'Remove block CSS from frontend (wp-block-library)', 'mv23theme' ) )
                ->add_dependency( 'activate_classic_editor' )
                ->fancy()
                ->hide_label()
                ->set_width( 50 ),
        ) );

        // Global Animations
        $default_value_scroll_animations = get_option( 'activate_scroll_animations', false );
        $default_value_global_animations = get_option( 'global_animations', array() );
        $fields[] = Field::create( 'complex', 'scroll_animations_wrapper', __('Scroll Animations','mv23theme') )->merge()->add_fields(array(
            Field::create( 'checkbox', 'activate_scroll_animations' )
                ->set_default_value($default_value_scroll_animations)
                ->set_text( __('Activate','mv23theme') )
                ->fancy()
                ->hide_label()
                ->set_width(50),
            Field::create( 'common_settings_control', 'global_animations' )
                ->set_default_value($default_value_global_animations)
			    ->set_container( 'scroll_animations_container' )
			    ->set_add_text( __('Global Animations', 'mv23theme') )
                ->add_dependency('activate_scroll_animations')
                ->hide_label()
                ->set_width(50),
        ));

        // Masonry Gallery
        $masonry_default_value = get_option( 'activate_masonry', false );
        $fields[] = Field::create( 'complex', 'masonry_wrapper', __('Masonry Gallery','mv23theme') )->merge()->add_fields(array(
            Field::create( 'checkbox', 'activate_masonry', __('Activate Masonry','mv23theme') )
                ->set_default_value($masonry_default_value)
                ->set_text(__('Activate','mv23theme'))
                ->hide_label()
                ->fancy(),
        ));

        // Leaflet
        $default_value_leaflet = get_option( 'activate_leaflet', false );
        $fields[] = Field::create( 'complex', 'leaflet_map_wrapper', __('Leaflet Maps','mv23theme') )->merge()->add_fields(array(
            Field::create( 'checkbox', 'activate_leaflet', __('Activate LeaftLet','mv23theme') )
                ->set_default_value($default_value_leaflet)
                ->set_text(__('Activate','mv23theme'))
                ->hide_label()
                ->fancy()
        ));
            
        // Google Maps
        $default_value_gm = get_option( 'activate_gm', false );
        $default_value_gm_api_key = get_option( 'uf_google_maps_api_key', '' );
        $default_value_gm_services = get_option( 'gm_services', array() );
        $fields[] = Field::create( 'complex', 'google_map_wrapper', __('Google Maps','mv23theme') )->merge()->add_fields(array(
            Field::create( 'checkbox', 'activate_gm', __('Activate Google Maps','mv23theme') )
                ->set_default_value($default_value_gm)
                ->set_text(__('Activate','mv23theme'))
                ->fancy()
                ->hide_label()
                ->set_width(30),
            Field::create( 'multiselect', 'gm_services', __('Google Map Services','mv23theme'))
                ->set_default_value($default_value_gm_services)
                ->set_input_type( 'checkbox' )
                ->set_orientation( 'horizontal' )
                ->add_dependency( 'activate_gm' )
                ->add_options( array(
                    'places' => 'Places'
                ))
                ->set_width(30),
            Field::create( 'text', 'uf_google_maps_api_key', __('Google Maps API Key','mv23theme'))
                ->set_default_value($default_value_gm_api_key)
                ->add_dependency('activate_gm')
                ->required()
                ->set_width(30),
        ));
        
        // Comments styles
        $comments_styles_default_value = get_option( 'disable_comments_styles', false );
        $fields[] = Field::create( 'checkbox', 'disable_comments_styles', __('Deactivate theme styles in comments','mv23theme') )
            ->set_default_value($comments_styles_default_value)
            ->set_text(__('Deactivate','mv23theme'))
            ->fancy();

        // Disable Ultimate Fields UI
        $uf_disable_ui_default_value = get_option( 'uf_disable_ui', false );
        $fields[] = Field::create( 'checkbox', 'uf_disable_ui', __('Disable Ultimate Fields UI','mv23theme') )
            ->set_default_value($uf_disable_ui_default_value)
            ->set_text(__('Disable','mv23theme'))
            ->set_description(__('This will hide the Ultimate Fields UI, which is used to create and manage custom fields','mv23theme'))
            ->fancy();

        // Disable header height calculation on anchors
        $disable_header_height_calculation_on_anchors_default_value = get_option( 'disable_header_height_calculation_on_anchors', false );
        $fields[] = Field::create( 'checkbox', 'disable_header_height_calculation_on_anchors', __('Disable header height calculation on anchors','mv23theme') )
            ->set_default_value($disable_header_height_calculation_on_anchors_default_value)
            ->set_text(__('Disable','mv23theme'))
            ->set_description(__('This will disable the calculation of header height when using anchor links. This can be useful if you have a transparent fixed header and want to avoid extra space when navigating to anchor links.','mv23theme'))
            ->fancy();

        return $fields;
    }
}