<?php
namespace Ultimate_Fields\Ultimate_Builder;

use Ultimate_Fields\Field\Repeater;
use Ultimate_Fields\Datastore\Group as Group_Datastore;
use Ultimate_Fields\Template;
use Core\Frontend\Frontend;
use Core\Theme_Options\Theme_Options;

/**
 * Handles the display of the field, including its layout and structure.
 *
 * @since 1.0
 */
class Field extends Repeater {
	/**
	 * Enqueues the scripts for the field.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'uf-field-ultimate-builder' );
		wp_enqueue_style( 'uf-field-ultimate-builder' );
		
		$this->enqueue_gjs_plugins();
        wp_enqueue_script( 'builder' );

		# Enqueue the scripts for all groups
		foreach( $this->groups as $group ) {
			$group->enqueue_scripts();
		}

		# Add the necessary templates
		Template::add( 'ultimate-builder', 'ultimate-builder' );
		// fix for repeater dropdown inside Animation Timeline > Tween Group:  
		Template::add( 'repeater-dropdown',  'field/repeater/dropdown' ); 
	}

	/**
	 * Enqueue GJS plugins dynamically.
	 *
	 * @since 1.0
	 */
	private function enqueue_gjs_plugins() {
		$gjs_plugins_info = $this->get_gjs_plugins();
		
		foreach ( $gjs_plugins_info as $plugin_info ) {
			if( isset( $plugin_info['isExternal'] ) && $plugin_info['isExternal'] === true ) {
				wp_enqueue_script( $plugin_info['handle'] );
				if( isset( $plugin_info['hasCss'] ) && $plugin_info['hasCss'] === true ) {
					wp_enqueue_style( $plugin_info['handle'] . '-style' );
				}
			}
		}
	}

	/**
	 * Exports the settings of the field.
	 *
	 * @since 1.0
	 *
	 * @return mixed[]
	 */
	public function export_field() {
		$settings = parent::export_field();

        $settings[ 'type' ] = 'ultimate_builder';

		return $settings;
	}

    /**
	 * Exports the data of the field.
	 *
	 * @since 1.0
	 *
	 * @return mixed[]
	 */
	public function export_data() {    
        $builder_data = $this->get_value( $this->name );
        $components_data_raw = $this->get_value( $this->name.'_datastore' );
		
		# Use the default value if needed
		if( null === $builder_data && is_array( $this->default_value ) ) {
			$builder_data = $this->default_value;
		}
		
		// If there are components, go through each of them.
		// to ensure complex fields are sent correctly
		// and "prepare" files previews
		$components_data = array();
		if( is_array($components_data_raw) ){
			foreach( $components_data_raw as $__id => $component_data){
				if ( isset($this->groups[ $component_data[ '__type' ] ]) ){
					$datastore = new Group_Datastore( $component_data );
					$datastore = apply_filters( 'uf.ultimate_builder.group_datastore', $datastore, $component_data, $this );

					# Get the datastore and export data
					$group = $this->groups[ $component_data[ '__type' ] ];
					$group->set_datastore( $datastore );
					$group_processed_values = $group->export_data();
					$components_data[$__id] = $group_processed_values;
				}
			}
		}

		// export link to the builder interface
		$builder_link = $this->get_builder_link();

		return array(
			$this->name => $builder_data,
			$this->name.'_datastore' => $components_data,
			$this->name.'_builder_link' => $builder_link,
			$this->name.'_theme_styles' => $this->get_styles(),
			$this->name.'_theme_scripts' => $this->get_scripts(),
			$this->name.'_gjs_plugins' => $this->get_gjs_plugins(),
			$this->name.'_theme_fonts' => $this->get_theme_fonts(),
		);
	}

    /**
	 * Retrieves the value of the field from a source and saves it in the current datastore.
	 *
	 * This method should not perform any validation - if something is wrong with
	 * the value of the field, simply don't save it. Validation will be performed
	 * later and will return an error anyway, if the internal value is empty.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $source The source which the value of the field should be available in.
	 */
	public function save( $source ) {
		$builder_data = array();
        $components_data = array();

        // error_log( print_r( $source[ $this->name ]['components_data'], true ) );

        if( isset( $source[ $this->name ] ) ){
            if( isset( $source[ $this->name ]['builder_data'] ) ){
                $builder_data = $source[ $this->name ]['builder_data'];
            }
            
            if( isset( $source[ $this->name ]['components_data'] ) ){
                $components_data_raw = $source[ $this->name ]['components_data'];

				// process components to save their data with correct "merged fields" values
				foreach( $components_data_raw as $__id => $component_data){
					if( 
						isset( $component_data['__type'] ) &&
						$component_data['__type'] != '' &&
						isset( $this->groups[ $component_data['__type'] ] )
						){	
						$group = $this->groups[ $component_data[ '__type' ] ];
						$group->save( $component_data );
						$group_processed_values = $group->get_datastore()->get_values();

						do_action_ref_array( 'uf.ultimate_builder.save_component', array( $group_processed_values, &$component_data, $group, $this ) );

						// Only save the component data in the builder datastore if it doesn't have a custom datastore 
						// (like theme-options component) to avoid saving it twice
						if( !isset( $component_data['has_custom_datastore'] ) || !$component_data['has_custom_datastore'] ){
							$components_data[$__id] = $group_processed_values;
						}
					}
				}
            }

		}

		$this->datastore->set( $this->name, $builder_data );
		$this->datastore->set( $this->name.'_datastore', $components_data );
	}

	/**
	 * Get the link to the builder interface
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	private function get_builder_link() {
		global $post;
		$builder_link = '';
		
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $builder_link;
		}
		
		$builder_link = add_query_arg(
			[
			  'post' => $post->ID,
			  'action' => 'ultimate-builder',
			  'meta' => $this->name
			],
			admin_url( 'post.php' )
		);

		return $builder_link;
	}

	private function get_styles() {
		$styles = array();

		$frontend_styles_control = array_merge( 
			Frontend::get_styles_control_handles(), 
			array('canvas-styles') 
		);

		global $wp_styles;
		foreach ( $frontend_styles_control as $handle ) {
			if ( isset( $wp_styles->registered[$handle] ) ) {
				// error_log( 'Found style handle: ' . $handle );
				$style_info = $wp_styles->registered[$handle];
				if ( isset( $style_info->src ) ) {
					// check for extra styles to add before this one
					if ( isset( $style_info->extra['before'] ) && is_array( $style_info->extra['before'] ) ) {
						foreach ( $style_info->extra['before'] as $extra_style ) {
							$styles[] = $extra_style;
						}
					}

					// add the style url
					$styles[] = $style_info->src;

					// check for extra styles to add after this one
					if ( isset( $style_info->extra['after'] ) && is_array( $style_info->extra['after'] ) ) {
						foreach ( $style_info->extra['after'] as $extra_style ) {
							$styles[] = $extra_style;
						}
					}
				}
			}
		}

		return $styles;
	}

	private function get_scripts() {
		$scripts = array();

		$frontend_scripts_control = Frontend::get_scripts_control_handles();

		global $wp_scripts;
		foreach ( $frontend_scripts_control as $handle ) {
			if ( isset( $wp_scripts->registered[$handle] ) ) {
				$script_info = $wp_scripts->registered[$handle];
				if ( isset( $script_info->src ) ) {
					$scripts[] = $script_info->src;
				}
			}
		}

		return $scripts;
	}

	/**
	 * Get the GJS plugins information
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	private function get_gjs_plugins() {
		$plugins = array();
		$builder_instance = Ultimate_Builder::instance();
		
		if ( $builder_instance ) {
			$gjs_plugins = $builder_instance->get_gjs_plugins();
		
			foreach ( $gjs_plugins as $plugin ) {
				$plugins[] = array(
					'name' => $plugin['name'],
					'handle' => $plugin['handler'],
					'isComponent' => $plugin['isComponent'] ?? false,
					'isExternal' => $plugin['isExternal'] ?? false,
					'hasCss' => $plugin['hasCss'] ?? false,
				);
			}
		}

		return $plugins;
	}

	public function add_groups( $groups, $args = array() ) {
		foreach ( $groups as $group ) {
			$__group_id = $group['__group_id'];
			unset( $group['__group_id'] );
			$this->add_group( $__group_id, $group );
		}
		return $this;
	}

	public function get_theme_fonts(){
		$theme_options = Theme_Options::getInstance();
		$theme_fonts = $theme_options->get_theme_fonts();
		return $theme_fonts['names'] ?? array();
	}
}
