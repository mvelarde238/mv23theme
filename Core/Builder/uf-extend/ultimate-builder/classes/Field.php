<?php
namespace Ultimate_Fields\Ultimate_Builder;

use Ultimate_Fields\Field\Repeater;
use Ultimate_Fields\Datastore\Group as Group_Datastore;
use Ultimate_Fields\Template;

/**
 * Handles the display of the field, including its layout and structure.
 *
 * @since 1.0
 */
class Field extends Repeater {

	private $theme_styles = array(
		'mv23theme-styles',
		'mv23theme-font-awesome',
		'mv23theme-bootstrap-icons',
		'uf-leaflet-css'
	);
	private $theme_scripts = array(
		'mv23theme-scripts',
		'uf-leaflet',
		'uf-gmaps',
	);

	/**
	 * Enqueues the scripts for the field.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'gjs-context-menu-options' );
		$this->enqueue_gjs_plugins();
        wp_enqueue_script( 'builder' );
		wp_enqueue_script( 'uf-field-ultimate-builder' );
		
		wp_enqueue_style( 'uf-field-ultimate-builder' );
		wp_enqueue_style( 'uf-field-ultimate-builder' );
		wp_enqueue_style( 'gjs-context-menu-style' );

		# Add the necessary templates
		Template::add( 'ultimate-builder', 'ultimate-builder' );
	}

	/**
	 * Enqueue GJS plugins dynamically.
	 *
	 * @since 1.0
	 */
	private function enqueue_gjs_plugins() {
		$gjs_plugins_info = $this->get_gjs_plugins();
		
		foreach ( $gjs_plugins_info as $plugin_info ) {
			wp_enqueue_script( $plugin_info['handle'] );
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
        $components_data_raw = $this->get_value( $this->name.'_components' );
		$components_data = array();
        $styles = $this->get_value( $this->name.'_styles' );

		# Use the default value if needed
		if( null === $builder_data && is_array( $this->default_value ) ) {
			$builder_data = $this->default_value;
		}

		# If there are components, go through each of them.
		// to ensure complex fields are sent correctly
		if( is_array($components_data_raw) ){
			foreach( $components_data_raw as $component){
				$processed_component = $this->export_component_recursively( $component );
				if( $processed_component !== null ){
					$components_data[] = $processed_component;
				}
			}
		}

		// export link to the builder interface
		$builder_link = $this->get_builder_link();

		return array(
			$this->name => $builder_data,
			$this->name.'_components' => $components_data,
			$this->name.'_styles' => $styles,
			$this->name.'_builder_link' => $builder_link,
			$this->name.'_theme_styles' => $this->get_styles(),
			$this->name.'_theme_scripts' => $this->get_scripts(),
			$this->name.'_gjs_plugins' => $this->get_gjs_plugins(),
		);
	}

	private function export_component_recursively( $component ) {
		if( !isset( $component['__type'] ) || empty( $component['__type'] ) ){
			return null;
		}

		if( isset( $this->groups[ $component['__type'] ] ) ){
			$datastore = new Group_Datastore( $component );
			# Get the datastore and export data
			$group = $this->groups[ $component[ '__type' ] ];
			$group->set_datastore( $datastore );
			$group_processed_values = $group->export_data();
			$group_processed_values['__id'] = $component['__id'];
			// $group_processed_values['__gjsAttributes'] = $component['__gjsAttributes'] ?? array();
		} else {
			// component type not registered is a grapesjs built-in component
			$group_processed_values = $component;
		}

		// if component has sub-components, process them recursively
		if( isset( $component['components'] ) && is_array( $component['components'] ) ){
			$group_processed_values['components'] = array();
			foreach( $component['components'] as $sub_component ){
				$processed_sub_component = $this->export_component_recursively( $sub_component );
				if( $processed_sub_component !== null ){
					$group_processed_values['components'][] = $processed_sub_component;
				}
			}
		}

		return $group_processed_values;
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
		$builder_styles = '';

        // error_log( print_r( $source, true ) );

        if( isset( $source[ $this->name ] ) ){
            if( isset( $source[ $this->name ]['builder_data'] ) ){
                $builder_data = $source[ $this->name ]['builder_data'];
            }
            
            if( isset( $source[ $this->name ]['components_data'] ) ){
                $components_data_raw = $source[ $this->name ]['components_data'];

				// process components recursively to save their data with correct "merged fields" values
				foreach( $components_data_raw as $component){
					$processed_component = $this->save_component_recursively( $component );
					if( $processed_component !== null ){
						$components_data[] = $processed_component;
					}
				}
            }

			if( isset( $source[ $this->name ]['css'] ) ){
				$builder_styles = $source[ $this->name ]['css'];
			}
		}

		$this->datastore->set( $this->name, $builder_data );
		$this->datastore->set( $this->name.'_components', $components_data );
		$this->datastore->set( $this->name.'_styles', $builder_styles );
	}

	/**
	 * Process a component recursively, handling nested components
	 *
	 * @since 1.0
	 *
	 * @param array $component The component data to process
	 * @return array|null The processed component data or null if invalid
	 */
	private function save_component_recursively( $component ) {
		if( !isset( $component['__type'] ) || empty( $component['__type'] ) ){
			return null;
		}

		if( isset( $this->groups[ $component['__type'] ] ) ){	
			$group = $this->groups[ $component[ '__type' ] ];
			$group->save( $component );
			$group_processed_values = $group->get_datastore()->get_values();
			$group_processed_values['__id'] = $component['__id'];

			// if is set an attribute starting with "__gjs", save it too
			// e.g. __gjsAttributes, __gjs_data_breakpoints, etc.
			foreach( $component as $key => $value ){
				if( strpos( $key, '__gjs') === 0 ){
					$group_processed_values[ $key ] = $value;
				}
			}

		} else {
			// component type not registered is a grapesjs built-in component
			$group_processed_values = $component;
		}

		// if component has sub-components, process them recursively
		if( isset( $component['components'] ) && is_array( $component['components'] ) ){
			$group_processed_values['components'] = array();
			foreach( $component['components'] as $sub_component ){
				$processed_sub_component = $this->save_component_recursively( $sub_component );
				if( $processed_sub_component !== null ){
					$group_processed_values['components'][] = $processed_sub_component;
				}
			}
		}

		return $group_processed_values;
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

		$allowed_post_types = ['post','page'];

		if ( !in_array($post->post_type, $allowed_post_types) ) {
			return $builder_link;
		}
		
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

		global $wp_styles;
		foreach ( $this->theme_styles as $handle ) {
			if ( isset( $wp_styles->registered[$handle] ) ) {
				$style_info = $wp_styles->registered[$handle];
				if ( isset( $style_info->src ) ) {
					$styles[] = $style_info->src;
				}
			}
		}

		return $styles;
	}

	private function get_scripts() {
		$scripts = array();

		global $wp_scripts;
		foreach ( $this->theme_scripts as $handle ) {
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
				);
			}
		}

		return $plugins;
	}
}
