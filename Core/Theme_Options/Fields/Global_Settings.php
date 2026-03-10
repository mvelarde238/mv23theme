<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;
use Core\Theme_Options\Theme_Options;

class Global_Settings {
    public static function get_fields(){
        $fields = array(
            Field::create( 'tab', 'global_settings' )
        );

        // Logos
        $logos_fields = [];
        foreach ( Theme_Options::getInstance()->get_logos_field_names() as $key => $value) {
            if( $key != 'custom' ) {
                $default_value = get_option( $key, '' );
                $logos_fields[] = Field::create( 'image', $key, $value )->set_width(25)->set_default_value($default_value);
            }
        }
        $fields[] = Field::create( 'complex', 'logos_wrapper', __('Logos','mv23theme') )
            ->add_fields( $logos_fields )
            ->merge();

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

        // Social Networks
        $social_networks_default_value = get_option( 'social_networks', array() );
        $fields[] = Field::create( 'repeater', 'social_networks', __('Social Networks','mv23theme') )
            ->set_add_text(__('Add Social Network','mv23theme'))
            ->set_description(__('Add the URLs of your social networks. For WhatsApp, add the phone number with country code and without symbols.<br><br>To show them on your website, you can use these shortcodes: [social_networks] [redes_sociales].','mv23theme'))
            ->set_default_value($social_networks_default_value)
            ->set_chooser_type('dropdown')
            ->add_group('social-network', array(
                'title_template' => '<%= icon %> : <%= url %>',
                'fields' => array(
                    Field::create( 'select', 'icon', __('Social Network','mv23theme'))->add_options( array(
                        '' => __('Select','mv23theme'),
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter',
                        'instagram' => 'Instagram',
                        'youtube' => 'Youtube',
                        'whatsapp' => 'WhatsApp',
                        'telegram' => 'Telegram',
                        'vimeo' => 'Vimeo',
                        'behance' => 'Behance',
                        'github' => 'Github',
                        'tiktok' => 'Tiktok',
                        'flickr' => 'Flickr',
                        'soundcloud' => 'SoundCloud',
                        'skype' => 'Skype',
                        'linkedin' => 'Linkedin',
                        'pinterest' => 'Pinterest',
                        'envelope' => 'Mail'
                    ))->set_width( 25 ),
                    Field::create( 'text', 'url' )->set_width( 75 )->add_dependency('icon','whatsapp','!='),
                    Field::create( 'text', 'number', __('Phone number','mv23theme') )->set_width( 25 )->add_dependency('icon','whatsapp','='),
                    Field::create( 'text', 'msg', __('Message','mv23theme') )->set_width( 50 )->add_dependency('icon','whatsapp','=')->set_default_value(__('Hello, I need more information about...','mv23theme')),
                )
            ));

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

        return $fields;
    }
}