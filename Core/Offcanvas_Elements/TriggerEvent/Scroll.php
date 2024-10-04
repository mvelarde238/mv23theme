<?php
namespace Core\Offcanvas_Elements\TriggerEvent;

use Core\Offcanvas_Elements\TriggerEvent;
use Ultimate_Fields\Field;

/**
 * Handles the scroll event
 */
class Scroll extends TriggerEvent {
	/**
	 * Returns the type of the event
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'scroll';
	}

	/**
	 * Returns the name of the event.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Scroll', 'default' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = 'The element will show if scroll top reaches: 
        <% if(settings_type == "basic"){ %>
            <%= scroll_top %>px 
        <% } else { %>
            <%= scrollmagic_settings.trigger_element %>
        <% } %>
        <% if(_custom_cookie_wrapper.custom_cookie){ %>
            limited by <%= _custom_cookie_wrapper.storage_type %> storage
        <% } else { %>
            <% if(settings_type == "basic"){ %>
                on every page reload
            <% } %> 
        <% } %>';
		
		return $template;
	}

	/**
	 * Returns the fields for the trigger event.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {

        $scrollMagic_fields = array();
        if( !SCROLL_ANIMATIONS ){
            $scrollMagic_fields[] = Field::create( 'message', 'Hint_1' )->set_description( __('Activate advanced animations in Theme Options -> Global Options','default') )->hide_label();
        }
        $scrollMagic_fields[] = Field::create( 'text', 'trigger_element', __('Trigger Element','default') )->add_dependency( '../settings_type','scrollmagic','=' )->required()->set_width( 20 );
        $scrollMagic_fields[] = Field::create( 'select', 'trigger_hook', __('Trigger Hook','default') )->add_options( array(
            'onEnter' => __('onEnter','default'),
            'onCenter' => __('onCenter','default'),
            'onLeave' => __('onLeave','default')
        ))->set_width( 20 );
        $scrollMagic_fields[] = Field::create( 'number', 'offset' )->set_default_value(0)->set_suffix('pixels')->set_placeholder('0')->set_width( 20 );
        if( SCROLL_INDICATORS ) $scrollMagic_fields[] = Field::create( 'checkbox', 'add_indicators', __('Add indicators','') )->fancy()->set_width( 20 );

		$fields = array(
            Field::create( 'radio', 'settings_type', __( 'Settings type', 'default' ) )
                ->add_options(array(
                    'basic'     => __( 'Use basic settings', 'default' ),
                    'scrollmagic' => __( 'Use ScrollMagic library settings', 'default' )
            )),
            Field::create( 'number', 'scroll_top' )->set_suffix('pixels')->set_description(__('Please enter a number that represents the vertical scroll position required to show the element','default'))->add_dependency( 'settings_type','basic','=' )->required(),

            Field::create( 'complex', 'scrollmagic_settings' )->add_dependency( 'settings_type','scrollmagic','=' )->add_fields($scrollMagic_fields),

            Field::create( 'complex', '_custom_cookie_wrapper', __('(optional) Create a custom visualization cookie','default') )->merge()->add_fields(array(
                Field::create( 'checkbox', 'custom_cookie', __('Activate','default') )->fancy()->set_width(50),
                Field::create( 'text', 'cookie_name', __( 'Name', 'default' ) )->required()->add_dependency('custom_cookie')->set_width(50),
                Field::create( 'radio', 'storage_type', __( 'Storage type', 'default' ) )->add_dependency('custom_cookie')
                    ->add_options(array(
                        'session'     => __( 'Session: The data is stored only for the duration of a session, i.e., until the browser (or tab) is closed', 'default' ),
                        'local' => __( 'Local: The stored data will persist even after closing and reopening the browser', 'default' )
                        // 'cookie' => __( 'Cookie: The data can be set to expire at a certain time.', 'default' )
                ))
            )),

            Field::create( 'complex', '_cookie_expiration_wrapper', __('(optional) Set the cookie expiration time','default') )->merge()->set_description(__('The cookie will be deleted after X time from creation.','default'))->add_fields(array(
                Field::create( 'checkbox', 'cookie_expiration', __('Activate','default') )->fancy()->set_width(50),
                Field::create( 'number', 'expiration_time', __( 'Expiration time', 'default' ) )->required()->add_dependency('cookie_expiration')->set_suffix('miliseconds')->set_width(50)
            ))
        );

		return $fields;
	}
}
