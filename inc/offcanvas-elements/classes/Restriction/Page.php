<?php
namespace Offcanvas_Elements\Restriction;

use Offcanvas_Elements\Core;
use Offcanvas_Elements\Restriction;
use Ultimate_Fields\Field;

/**
 * Handles the page restriction
 */
class Page extends Restriction {
	/**
	 * Returns the type of the restriction (e.g. page).
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'page';
	}

	/**
	 * Returns the name of the restriction.
	 *
	 * @return string
	 */
	public static function get_name() {
		return __( 'Page', 'default' );
	}

	/**
	 * Returns the fields for the restriction.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array(
			Field::create( 'text', '_title' )
		);

		# Prepare the options for the post types multiselect
		$post_types   = array();
		$hierarchical = array();

		/**
		 * Allows post types to be excluded from the UI.
		 *
		 * @param string[] $post_types The post types to ignore.
		 * @return string[]
		 */
		$excluded = array( 'attachment', 'footer', 'v23accordion', 'seccion_reusable', 'megamenu', 'mv23_library', 'archive_page', Core::getInstance()->get_slug() );

		foreach( get_post_types( array( 'public' => true ), 'objects' ) as $id => $post_type ) {
			if( in_array( $id, $excluded ) ) {
				continue;
			}

			$post_types[ $id ] = __( $post_type->labels->name );
			if( is_post_type_hierarchical( $id ) ) {
				$hierarchical[ $id ] = __( $post_type->labels->name );
			}
		}

		# Prepare page templates
		$templates = array(
			'default' => __( 'Default' )
		);

		$raw = wp_get_theme()->get_page_templates();
		foreach( $raw as $template => $name ) {
			$templates[ $template ] = $name;
		}

		# Add the choice to show the element based on rules or actual posts
		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Browser type', 'default' ) )
			->add_options(array(
				'posttype' => __( 'Show the element based on page type', 'default' ),
			    'post'     => __( 'Show the element based on a particular post or page', 'default' )
			));

		$fields[] = Field::create( 'multiselect', 'post_types', __( 'Post Types', 'default' ) )
			->required()
			->add_options( $post_types )
			->set_input_type( 'checkbox' )
			->set_description( __( 'The element will be displayed on all of the checked post types above.', 'default' ) )
			->add_dependency( 'restriction_type', 'posttype' );

		if( count( $templates ) > 1 ) {
			$fields[] = Field::create( 'complex', 'templates', __( 'Templates', 'default' ) )
				->add_fields(array(
					Field::create( 'multiselect', 'visible', __( 'Show on', 'default' ) )
						->add_options( $templates )
						->set_input_type( 'checkbox' )
						->set_width( 50 ),
					Field::create( 'multiselect', 'hidden', __( 'Hide on', 'default' ) )
						->add_options( $templates )
						->set_input_type( 'checkbox' )
						->set_width( 50 )
				))
				->add_dependency( 'restriction_type', 'posttype' )
				->add_dependency( 'post_types', 'page', 'contains' )
				->set_description( __( 'The element will only appear on the checked templates, if any. If none are checked, the container will appear on all pages.', 'default' ) )
				;
		}

		# Add taxonomies
		foreach( get_taxonomies( array( 'show_ui' => true, 'hierarchical' => true ), 'objects' ) as $slug => $taxonomy ) {
			$description = __( 'Control the visiblity of the container based on the terms of the "%s" taxonomy.', 'default' );
			$description = sprintf( $description, $taxonomy->labels->name );
			$fields[] = Field::create( 'complex', $slug, $taxonomy->labels->name )
				->set_description( $description )
				->add_dependency( 'restriction_type', 'posttype' )
				->add_dependency( 'post_types', $taxonomy->object_type, 'contains' )
				->add_fields(array(
					Field::create( 'wp_objects', 'visible', __( 'Show on', 'default' ) )
						->add( 'terms', 'taxonomy=' . $slug )
						->set_width( 50 ),
					Field::create( 'wp_objects', 'hidden', __( 'Hide on', 'default' ) )
						->add( 'terms', 'taxonomy=' . $slug )
						->set_width( 50 )
				));
		}

		# Add formats
		$formats = get_theme_support( 'post-formats' );
		if( $formats && isset( $formats[ 0 ] ) && count( $formats[ 0 ] ) > 1 ) {
			$options = array();

			foreach( $formats[ 0 ] as $format ) {
				$options[ $format ] = get_post_format_string( $format );
			}

			$fields[] = Field::create( 'complex', 'post_formats', __( 'Formats', 'default' ) )
				->add_fields(array(
					Field::create( 'multiselect', 'visible', __( 'Show on', 'default' ) )
						->add_options( $options )
						->set_input_type( 'checkbox' )
						->set_orientation( 'horizontal' )
						->set_width( 50 ),
					Field::create( 'multiselect', 'hidden', __( 'Hide on', 'default' ) )
						->add_options( $options )
						->set_input_type( 'checkbox' )
						->set_orientation( 'horizontal' )
						->set_width( 50 )
				))
				->add_dependency( 'restriction_type', 'posttype' )
				->add_dependency( 'post_types', 'post', 'contains' )
				->set_description( __( 'The element will only appear on the checked formats, if any.', 'default' ) );
		}

		$fields[] = Field::create( 'complex' , 'levels', __( 'Levels', 'default' ) )
			->add_dependency( 'post_types', array_keys( $hierarchical ), 'contains' )
			->add_dependency( 'restriction_type', 'posttype' )
			->set_description( __( 'Enter as numbers, separated by commas.', 'default' ) )
			->add_fields(array(
				Field::create( 'text', 'visible', __( 'Show on', 'default' ) )
					->set_default_value( '0' )
					->set_width( 50 ),
				Field::create( 'text', 'hidden', __( 'Hide on', 'default' ) )
					->set_default_value( '0' )
					->set_width( 50 ),
			));

		$fields[] = Field::create( 'complex', 'item', __( 'Item', 'default' ) )
			->add_dependency( 'restriction_type', 'post' )
			->add_fields(array(
				Field::create( 'wp_object', 'post', __( 'Item', 'default' ) )
					->add( 'posts' )
					->set_width( 50 )
					->hide_label(),
				Field::create( 'select', 'operator', __( 'Operator', 'default' ) )
					->set_input_type( 'radio' )
					->add_dependency( 'post', '', 'NOT_NULL' )
					->add_options(array(
						'is'     => __( 'is', 'default' ),
						'is_not' => __( 'is not', 'default' )
					))
					->set_width( 20 ),
				Field::create( 'select', 'type', __( 'Item type', 'default' ) )
					->set_input_type( 'radio' )
					->add_dependency( 'post', '', 'NOT_NULL' )
					->add_options(array(
						'post'   => __( 'the current post/page', 'default' ),
						'parent' => __( 'the parent of the current post/page', 'default' )
					))
					->set_width( 30 )
			));

		return $fields;
	}
}
