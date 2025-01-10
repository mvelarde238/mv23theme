<?php
namespace Core\Offcanvas_Elements\Restriction;

use Core\Offcanvas_Elements\Core;
use Core\Offcanvas_Elements\Restriction;
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
		return __( 'Page', 'mv23theme' );
	}

	/**
	 * Returns the title template for the group
	 *
	 * @return string backbone template
	 */
	public static function get_title_template() {
		$template = '<% if (restriction_type == "post") { %>
		  	Visible if<%= (item.type == "parent") ? " "+item.type+" " : " " %>page <%= item.operator %> <%= item.post %>
		<% } else { %>
		  <% if ( post_types.length ){ %>
		  	Restricted to pages whose type is<%= (post_types.length > 1) ? " either" : "" %>: <%= post_types.join(" or ") %>
			<% if ( category.visible.length ){ %>
				AND <%= ( category.visible.length > 1 ) ? "categories are" : "category is" %>: <%= category.visible.join(", ") %>
			<% } %>
			<% if ( category.hidden.length ){ %>
				AND <%= ( category.hidden.length > 1 ) ? "categories arent" : "category isnt" %>: <%= category.hidden.join(", ") %>
			<% } %>
			<% if ( templates.visible.length ){ %>
				AND <%= ( templates.visible.length > 1 ) ? "templates are" : "template is" %>: <%= templates.visible.join(", ") %>
			<% } %>
			<% if ( templates.hidden.length ){ %>
				AND <%= ( templates.hidden.length > 1 ) ? "templates arent" : "template isnt" %>: <%= templates.hidden.join(", ") %>
			<% } %>
		  <% } %>
		<% } %>';

		return $template;
	}

	/**
	 * Returns the fields for the restriction.
	 *
	 * @return Ultimate_Fields\Field[]
	 */
	public static function get_fields() {
		$fields = array();

		# Prepare the options for the post types multiselect
		$post_types   = array();
		$hierarchical = array();

		/**
		 * Allows post types to be excluded from the UI.
		 *
		 * @param string[] $post_types The post types to ignore.
		 * @return string[]
		 */
		$excluded = array( 'attachment', 'footer', 'reusable_section', 'megamenu', 'mv23_library', 'archive_page', Core::getInstance()->get_slug() );

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
			'mv23theme' => __( 'Default' )
		);

		$raw = wp_get_theme()->get_page_templates();
		foreach( $raw as $template => $name ) {
			$templates[ $template ] = $name;
		}

		# Add the choice to show the element based on rules or actual posts
		$fields[] = Field::create( 'radio', 'restriction_type', __( 'Restriction type', 'mv23theme' ) )
			->add_options(array(
			    'post'     => __( 'Show the element based on a particular post or page', 'mv23theme' ),
				'posttype' => __( 'Show the element based on page type', 'mv23theme' )
			));

		$fields[] = Field::create( 'multiselect', 'post_types', __( 'Post Types', 'mv23theme' ) )
			->required()
			->add_options( $post_types )
			->set_input_type( 'checkbox' )
			->set_description( __( 'The element will be displayed on all of the checked post types above.', 'mv23theme' ) )
			->add_dependency( 'restriction_type', 'posttype' );

		if( count( $templates ) > 1 ) {
			$fields[] = Field::create( 'complex', 'templates', __( 'Templates', 'mv23theme' ) )
				->add_fields(array(
					Field::create( 'multiselect', 'visible', __( 'Show on', 'mv23theme' ) )
						->add_options( $templates )
						->set_input_type( 'checkbox' )
						->set_width( 50 ),
					Field::create( 'multiselect', 'hidden', __( 'Hide on', 'mv23theme' ) )
						->add_options( $templates )
						->set_input_type( 'checkbox' )
						->set_width( 50 )
				))
				->add_dependency( 'restriction_type', 'posttype' )
				->add_dependency( 'post_types', 'page', 'contains' )
				->set_description( __( 'The element will only appear on the checked templates, if any. If none are checked, the container will appear on all pages.', 'mv23theme' ) )
				;
		}

		# Add taxonomies
		foreach( get_taxonomies( array( 'show_ui' => true, 'hierarchical' => true ), 'objects' ) as $slug => $taxonomy ) {
			$description = __( 'Control the visiblity of the element based on the terms of a taxonomy.', 'mv23theme' );
			$description = sprintf( $description, $taxonomy->labels->name );
			$fields[] = Field::create( 'complex', $slug, $taxonomy->labels->name )
				->set_description( $description )
				->add_dependency( 'restriction_type', 'posttype' )
				->add_dependency( 'post_types', $taxonomy->object_type, 'contains' )
				->add_fields(array(
					Field::create( 'wp_objects', 'visible', __( 'Show on', 'mv23theme' ) )
						->add( 'terms', 'taxonomy=' . $slug )
						->set_width( 50 ),
					Field::create( 'wp_objects', 'hidden', __( 'Hide on', 'mv23theme' ) )
						->add( 'terms', 'taxonomy=' . $slug )
						->set_width( 50 )
				));
		}

		# Add formats
		// $formats = get_theme_support( 'post-formats' );
		// if( $formats && isset( $formats[ 0 ] ) && count( $formats[ 0 ] ) > 1 ) {
		// 	$options = array();

		// 	foreach( $formats[ 0 ] as $format ) {
		// 		$options[ $format ] = get_post_format_string( $format );
		// 	}

		// 	$fields[] = Field::create( 'complex', 'post_formats', __( 'Formats', 'mv23theme' ) )
		// 		->add_fields(array(
		// 			Field::create( 'multiselect', 'visible', __( 'Show on', 'mv23theme' ) )
		// 				->add_options( $options )
		// 				->set_input_type( 'checkbox' )
		// 				->set_orientation( 'horizontal' )
		// 				->set_width( 50 ),
		// 			Field::create( 'multiselect', 'hidden', __( 'Hide on', 'mv23theme' ) )
		// 				->add_options( $options )
		// 				->set_input_type( 'checkbox' )
		// 				->set_orientation( 'horizontal' )
		// 				->set_width( 50 )
		// 		))
		// 		->add_dependency( 'restriction_type', 'posttype' )
		// 		->add_dependency( 'post_types', 'post', 'contains' )
		// 		->set_description( __( 'The element will only appear on the checked formats, if any.', 'mv23theme' ) );
		// }

		// $fields[] = Field::create( 'complex' , 'levels', __( 'Levels', 'mv23theme' ) )
		// 	->add_dependency( 'post_types', array_keys( $hierarchical ), 'contains' )
		// 	->add_dependency( 'restriction_type', 'posttype' )
		// 	->set_description( __( 'Enter as numbers, separated by commas.', 'mv23theme' ) )
		// 	->add_fields(array(
		// 		Field::create( 'text', 'visible', __( 'Show on', 'mv23theme' ) )
		// 			->set_default_value( '0' )
		// 			->set_width( 50 ),
		// 		Field::create( 'text', 'hidden', __( 'Hide on', 'mv23theme' ) )
		// 			->set_default_value( '0' )
		// 			->set_width( 50 ),
		// 	));

		$fields[] = Field::create( 'complex', 'item', __( 'Item', 'mv23theme' ) )
			->add_dependency( 'restriction_type', 'post' )
			->add_fields(array(
				Field::create( 'wp_object', 'post', __( 'Item', 'mv23theme' ) )
					->add( 'posts' )
					->set_width( 50 )
					->hide_label(),
				Field::create( 'select', 'operator', __( 'Operator', 'mv23theme' ) )
					->set_input_type( 'radio' )
					->add_dependency( 'post', '', 'NOT_NULL' )
					->add_options(array(
						'is'     => __( 'is', 'mv23theme' ),
						'is_not' => __( 'is not', 'mv23theme' )
					))
					->set_width( 20 ),
				Field::create( 'select', 'type', __( 'Item type', 'mv23theme' ) )
					->set_input_type( 'radio' )
					->add_dependency( 'post', '', 'NOT_NULL' )
					->add_options(array(
						'post'   => __( 'the current post/page', 'mv23theme' ),
						'parent' => __( 'the parent of the current post/page', 'mv23theme' )
					))
					->set_width( 30 )
			));

		return $fields;
	}

	/**
	 * Returns the result of restrictions checking.
	 *
	 * @return bool
	 */
	public static function check_restrictions( $restriction_data ) {
		$current_post_id = get_the_ID();
		
		if( $restriction_data['restriction_type'] === 'post' ){
			$item = $restriction_data['item'];
			$item_post = $item['post'];
			if($item_post){
				$evaluate_operator = [
					'is' => function($a, $b) { return $a != $b; },
					'is_not' => function($a, $b) { return $a == $b; }
				];

				$item_post_id = str_replace('post_','',$item_post);
				$current_post_id = ( $item['type'] == 'parent' ) ? wp_get_post_parent_id($current_post_id) : $current_post_id;

				$item_operator = $item['operator'];
				if ( isset($evaluate_operator[$item_operator]) ) {
					$restrictions_check_in[] = $evaluate_operator[$item_operator]( $current_post_id, $item_post_id );
				}
			}
		}

		if( $restriction_data['restriction_type'] === 'posttype' ){
			$is_restricted_in_posttype = array();
			$is_restricted_in_term = array();
			$is_restricted_in_template = array();

			// check posttype
			if( !empty($restriction_data['post_types']) ){
				$current_post_type = get_post_type($current_post_id);
				foreach ($restriction_data['post_types'] as $posttype) {
					$is_restricted_in_posttype[] = ( $current_post_type != $posttype );
				}
			}

			// check taxonomies
			if( in_array( 'post', $restriction_data['post_types'] ) ){
				foreach( get_taxonomies( array( 'show_ui' => true, 'hierarchical' => true ), 'objects' ) as $tax_slug => $taxonomy ) {
					if( ! isset( $restriction_data[ $tax_slug ] ) ) {
						continue;
					}
	
					if( ! empty( $restriction_data[ $tax_slug ][ 'visible' ] ) ){
						$current_terms_obj_list = get_the_terms($current_post_id, $tax_slug);
						$current_terms_ids = wp_list_pluck($current_terms_obj_list, 'term_id');
						
						foreach( $restriction_data[ $tax_slug ][ 'visible' ] as $term ){
							$show_in_this_term = intval( str_replace( 'term_', '', $term ) );
							$is_restricted_in_term[] = !in_array( $show_in_this_term, $current_terms_ids );
						}
					} 
	
					if( ! empty( $restriction_data[ $tax_slug ][ 'hidden' ] ) ){
						$current_terms_obj_list = get_the_terms($current_post_id, $tax_slug);
						$current_terms_ids = wp_list_pluck($current_terms_obj_list, 'term_id');
						
						foreach( $restriction_data[ $tax_slug ][ 'hidden' ] as $term ){
							$hide_in_this_term = intval( str_replace( 'term_', '', $term ) );
							$is_restricted_in_term[] = in_array( $hide_in_this_term, $current_terms_ids );
						}
					} 
				}
			}

			// check templates
			if( 
				in_array( 'page', $restriction_data['post_types'] ) &&
				( !empty($restriction_data['templates']['visible']) || !empty($restriction_data['templates']['hidden']) )
			){
				$current_template = basename(get_page_template());

				if( ! empty( $restriction_data[ 'templates' ][ 'visible' ] ) ){
					foreach( $restriction_data[ 'templates' ][ 'visible' ] as $template ){
						$show_in_this_template = str_replace( 'templates/', '', $template );
						$is_restricted_in_template[] = ( $show_in_this_template != $current_template );
					}
				}
				
				if( ! empty( $restriction_data[ 'templates' ][ 'hidden' ] ) ){
					foreach( $restriction_data[ 'templates' ][ 'hidden' ] as $template ){
						$hide_in_this_template = str_replace( 'templates/', '', $template );
						$is_restricted_in_template[] = ( $hide_in_this_template == $current_template );
					}
				}
			}

			if( !empty($is_restricted_in_term) ){
				$restrictions_check_in[] = !in_array(false, $is_restricted_in_term, true);	
			} else if( !empty($is_restricted_in_template) ){
				$restrictions_check_in[] = !in_array(false, $is_restricted_in_template, true);
			} else {
				$restrictions_check_in[] = !in_array(false, $is_restricted_in_posttype, true);
			}
		}

		// if all items in $restrictions_check_in are true [true, true, ...] is restricted
        $is_restricted = ( !empty($restrictions_check_in) ) ? !in_array(false, $restrictions_check_in, true) : false;
		return $is_restricted;
	}
}
