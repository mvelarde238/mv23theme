<?php
namespace Core\Offcanvas_Elements\Restriction;

use Ultimate_Fields\Field;
use Core\Offcanvas_Elements\Restriction;
use Core\Offcanvas_Elements\Core;

/**
 * Handles the plugin restriction
 */
class Plugin extends Restriction {
    private static $plugins = [];

	/**
	 * Returns the type of the restriction
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'plugin';
	}

	/**
	 * Returns the name of the restriction.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Plugin', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
        $template = '<% if (restriction_type == "plugin") { %>
                Visible if plugin <%= item.plugin %> <%= item.operator %>
          <% } %>';

		return $template;
	}

	/**
	 * Returns the fields for the restriction.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
        $slug = Core::getInstance()->get_slug();
		$fields = array();
        $plugins = apply_filters( 
            $slug.'_plugins_restriction_filter', 
            array(
                array( 'slug'=>'woocommerce', 'name'=>'WooCommerce', 'path'=>'' ),
                array( 'slug'=>'contact_form_7', 'name'=>'Contact Form 7', 'path'=>'contact-form-7/wp-contact-form-7.php' ),
                array( 'slug'=>'polylang', 'name'=>'Polylang', 'path'=>'' )
        ));
        self::$plugins = $plugins;

        $plugin_ids = array( '' => __('Select','mv23theme') );
        foreach ($plugins as $plugin) {
            $plugin_ids[ $plugin['slug'] ] = $plugin['name'];
        }

		# Add the choice to show the element based on actual plugin or rules(?)
		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Restriction type', 'mv23theme' ) )
			->add_options(array(
			    'plugin'     => __( 'Show the element based on a particular plugin', 'mv23theme' ),
				// 'posttype' => __( 'Show the element based on page type', 'mv23theme' )
			));

        $fields[] = Field::create( 'complex', 'item', __( 'Item', 'mv23theme' ) )
			->add_dependency( 'restriction_type', 'plugin' )
			->add_fields(array(
				Field::create( 'select', 'plugin', __( 'Plugin', 'mv23theme' ) )
                    ->add_options( $plugin_ids )
					->set_width( 50 )
					->hide_label(),
				Field::create( 'select', 'operator', __( 'Operator', 'mv23theme' ) )
                    ->add_dependency( 'plugin', '', '!=' )
					->set_input_type( 'radio' )
					->add_options(array(
						'is_active'     => __( 'is active', 'mv23theme' ),
						'is_not_active' => __( 'is not active', 'mv23theme' )
					))
					->set_width( 50 )
			));

		return $fields;
	}

	/**
	 * Returns the result of restrictions checking.
	 *
	 * @return bool
	 */
	public static function check_restrictions( $restriction_data ) {
		if( $restriction_data['restriction_type'] === 'plugin' ){
			$item = $restriction_data['item'];
			$item_plugin = $item['plugin'];
			if($item_plugin){
				$evaluate_operator = [
					'is_active' => function( $plugin_slug ) { return !self::plugin_is_active( $plugin_slug ); },
					'is_not_active' => function( $plugin_slug ) { return self::plugin_is_active( $plugin_slug ); }
				];

				$item_operator = $item['operator'];
				if ( isset($evaluate_operator[$item_operator]) ) {
					$restrictions_check_in[] = $evaluate_operator[$item_operator]( $item_plugin );
				}
			}
		}

        // if all items in $restrictions_check_in are true [true, true, ...] is restricted
        $is_restricted = ( !empty($restrictions_check_in) ) ? !in_array(false, $restrictions_check_in, true) : false;
        return $is_restricted;
	}

    /**
	 * Checks if plugin is active.
	 *
	 * @return bool
	 */
    private static function plugin_is_active( $plugin_slug ){
        $plugin_path = trailingslashit( WP_PLUGIN_DIR );

        foreach (self::$plugins as $plugin) {
            if( $plugin['slug'] === $plugin_slug ){
                $plugin_path .= ( $plugin['path'] != '' ) ? $plugin['path'] : $plugin_slug.'/'.$plugin_slug.'.php';
            }
        }

        return in_array( $plugin_path, wp_get_active_and_valid_plugins() );
    }
}
