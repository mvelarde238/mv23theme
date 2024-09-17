<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Page_Template {

    public static function get_fields(){
        # post types
        $post_types = array();
        $excluded = array( 'attachment', 'page' );
		foreach( get_post_types( array('public'=>true, 'exclude_from_search'=>false), 'objects' ) as $id => $post_type ) {
			if( in_array( $id, $excluded ) ) {
				continue;
			}
			$post_types[ $id ] = __( $post_type->labels->name );
		}

        $fields = array(
            Field::create( 'tab', __('Page Template','default') ),

            Field::create( 'repeater', 'page_template_settings' )
                ->set_chooser_type( 'tags' )
                ->set_add_text(__('Add rule','default'))
                ->hide_label()
                ->add_group( 'single', array(
                    'title_template' => '<% if( hide_sidebar ){ %>
                        Hide Sidebar in pages whose type is: <%= post_types.join(", ") %>
                     <% } else { %>
                        Use <%= page_template %> template in pages whose type is: <%= post_types.join(", ") %>
                     <% } %>',
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'multiselect', 'post_types', __( 'Post Types', 'default' ) )
                            ->required()
			                ->add_options( $post_types )
                            ->set_orientation( 'horizontal' )
			                ->set_input_type( 'checkbox' )->set_width(20),
                        Field::create( 'select', 'page_template' )->add_options(array(
                            'main-content--sidebar-right' => __('Sidebar Right','deafult'),
                            'main-content--sidebar-left' => __('Sidebar Left','deafult')
                        ))->add_dependency('hide_sidebar',0)->set_width(20),
                        Field::create( 'checkbox', 'hide_sidebar' )->fancy()->set_width(20),
                    )
                ))
                ->add_group( 'archive', array(
                    'title_template' => '<% if( hide_sidebar ){ %>
                        Hide Sidebar in archive pages for: <%= post_types.concat(taxonomies).join(", ") %>
                     <% } else { %>
                        Use <%= page_template %> template in archive pages for: <%= post_types.concat(taxonomies).join(", ") %>
                     <% } %>',
                    'edit_mode' => 'popup',
                    'fields' => self::get_archive_fields( $post_types )
                ))
        );
        return $fields;
    }

    private static function get_archive_fields( $post_types ){
        $archive_fields = array();

        $archive_fields[] = Field::create( 'multiselect', 'post_types', __( 'Post Types', 'default' ) )
			->add_options( $post_types )
            // ->set_orientation( 'horizontal' )
			->set_input_type( 'checkbox' )->set_width(20);

        # Add taxonomies
        $taxonomies = array();
        $excluded_tax = array( 'link_category', 'wp_pattern_category', 'mv23_library_tax' );
        foreach( get_taxonomies( array( 'show_ui' => true ), 'objects' ) as $slug => $taxonomy ) {
            if( in_array( $slug, $excluded_tax ) ) {
				continue;
			}

            $taxonomies[$slug] = $taxonomy->labels->name;
        }

        $archive_fields[] = Field::create( 'multiselect', 'taxonomies', __( 'Taxonomies', 'default' ) )
            ->add_options( $taxonomies )
            ->set_orientation( 'horizontal' )
            ->set_input_type( 'checkbox' )->set_width(20);

        $archive_fields[] = Field::create( 'select', 'page_template' )->add_options(array(
            'main-content--sidebar-left' => __('Sidebar Left','deafult'),
            'main-content--sidebar-right' => __('Sidebar Right','deafult')
        ))->add_dependency('hide_sidebar',0)->set_width(20);

        $archive_fields[] = Field::create( 'checkbox', 'hide_sidebar' )->fancy()->set_width(20);

        return $archive_fields;
    }
}