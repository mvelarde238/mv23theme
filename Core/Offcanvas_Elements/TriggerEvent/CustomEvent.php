<?php
namespace Core\Offcanvas_Elements\TriggerEvent;

use Core\Offcanvas_Elements\TriggerEvent;
use Ultimate_Fields\Field;
use Core\Offcanvas_Elements\Core;

/**
 * Handles custom events
 */
class CustomEvent extends TriggerEvent {
	/**
	 * Returns the type of the event
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'custom_event';
	}

	/**
	 * Returns the name of the event.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Custom Event', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = 'The element will show on: <%= ( event_source == "custom" ) ? event_name : event_source+"_event"  %>';
		
		return $template;
	}

	/**
	 * Returns the fields for the trigger event.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
        $slug = Core::getInstance()->get_slug();

        $event_sources = apply_filters( 
            $slug.'_event_sources_filter',
            array(
                'woocommerce' => array( 'adding_to_cart','added_to_cart','removed_from_cart','wc_cart_button_updated','cart_page_refreshed','cart_totals_refreshed','wc_fragments_loaded','wc_cart_emptied','update_checkout','updated_wc_div','updated_cart_totals','country_to_state_changed','updated_shipping_method','applied_coupon','removed_coupon','init_checkout','payment_method_selected','update_checkout','updated_checkout','checkout_error','applied_coupon_in_checkout','removed_coupon_in_checkout' ),
                'contact_form_7' => array( 'contact_form_7_send', 'otro' )
            )
        );
        
        $event_sources_options = array( 'custom' => __( 'Use a custom event', 'mv23theme' ) );
        foreach ($event_sources as $source => $events) {
            $event_sources_options[$source] = __( 'Use a '.$source.' event', 'mv23theme' );
        }

		$fields = array(
            Field::create( 'radio', 'event_source', __( 'Event Source', 'mv23theme' ) )->add_options( $event_sources_options ),
            Field::create( 'text', 'event_name', __( 'Event name', 'mv23theme' ) )
            ->set_description( __( 'Please enter the event name that will trigger the display of the element. For example, "theme_document_ready" will show the element once the page is fully loaded.', 'mv23theme' ) )->add_dependency('event_source','custom','=')->required()
        );

        foreach ($event_sources as $source => $events) {
            $events_associative = array_combine($events, $events);
            $fields[] = Field::create( 'select', $source.'_event' )->add_dependency('event_source',$source,'=')->required()->add_options( $events_associative );
        }

		return $fields;
	}
}