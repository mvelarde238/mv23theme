<?php
namespace Ultimate_Fields\Ultimate_Builder;

use Ultimate_Fields\Field\Repeater;
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
		// wp_enqueue_script( 'grapes-react' );
		// wp_enqueue_style( 'grapes-react-styles' );

		wp_enqueue_script( 'gjs-extend-components' );
		wp_enqueue_script( 'gjs-context-menu' );
        wp_enqueue_script( 'gjs-row-and-cols' );
        wp_enqueue_script( 'gjs-togglebox' );
		wp_enqueue_script( 'gjs-section' );
        wp_enqueue_script( 'builder' );
		wp_enqueue_script( 'uf-field-ultimate-builder' );

		wp_enqueue_style( 'grapes-js-styles' );
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
        $components_data = $this->get_value( $this->name.'_components' );

        // error_log( print_r( $components_data, true ) );

		# Use the default value if needed
		if( null === $builder_data && is_array( $this->default_value ) ) {
			$builder_data = $this->default_value;
		}

		if( null === $components_data ) {
			$components_data = array();
		}

		// export link to the builder interface
		$builder_link = $this->get_builder_link();

		return array(
			$this->name => $builder_data,
			$this->name.'_components' => $components_data,
			$this->name.'_builder_link' => $builder_link
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

        // error_log( print_r( $source, true ) );

        if( isset( $source[ $this->name ] ) ){
            if( isset( $source[ $this->name ]['builder_data'] ) ){
                $builder_data = $source[ $this->name ]['builder_data'];
            }
            
            if( isset( $source[ $this->name ]['components_data'] ) ){
                $components_data = $source[ $this->name ]['components_data'];
            }
        }

		$this->datastore->set( $this->name, $builder_data );
		$this->datastore->set( $this->name.'_components', $components_data );
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
