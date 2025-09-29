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

	/**
	 * Enqueues the scripts for the field.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'gjs-extend-components' );
		wp_enqueue_script( 'gjs-context-menu' );
        wp_enqueue_script( 'gjs-row-and-cols' );
		wp_enqueue_script( 'gjs-comp-wrapper' );
		wp_enqueue_script( 'gjs-container' );
        wp_enqueue_script( 'gjs-togglebox' );
		wp_enqueue_script( 'gjs-section' );
		wp_enqueue_script( 'gjs-flipbox' );
		wp_enqueue_script( 'gjs-carousel' );
        wp_enqueue_script( 'builder' );
		wp_enqueue_script( 'uf-field-ultimate-builder' );

		wp_enqueue_style( 'uf-field-ultimate-builder' );
		wp_enqueue_style( 'gjs-context-menu-style' );

		# Add the necessary templates
		Template::add( 'ultimate-builder', 'ultimate-builder' );
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
			$this->name.'_builder_link' => $builder_link
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
}
