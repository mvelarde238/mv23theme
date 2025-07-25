<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Single_Pages {

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
            Field::create( 'tab', __('Single Pages','mv23theme') ),

            Field::create( 'repeater', 'single_pages_settings' )
                ->set_chooser_type( 'tags' )
                ->set_add_text(__('Add rule','mv23theme'))
                ->hide_label()
                ->add_group( 'single', array(
                    'title_template' => '<% if( hide_sidebar ){ %>
                        Hide Sidebar in pages whose type is: <%= post_types.join(", ") %>
                     <% } else { %>
                        Use <%= page_template %> template in pages whose type is: <%= post_types.join(", ") %>
                     <% } %>',
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'multiselect', 'post_types', __( 'Post Types', 'mv23theme' ) )
                            ->required()
			                ->add_options( $post_types )
                            ->set_orientation( 'horizontal' )
			                ->set_input_type( 'checkbox' )->set_width(20),
                        Field::create( 'select', 'page_template', __( 'Page Template', 'mv23theme' ) )->add_options(array(
                            'main-content--sidebar-right' => __('Sidebar Right','mv23theme'),
                            'main-content--sidebar-left' => __('Sidebar Left','mv23theme')
                        ))->add_dependency('hide_sidebar',0)->set_width(20),
                        Field::create( 'checkbox', 'hide_sidebar', __('Hide Sidebar','mv23theme') )->fancy()->set_width(20),
                        Field::create( 'checkbox', 'hide_post_title', __('Hide Post Title','mv23theme') )->fancy()->set_width(20),
                        Field::create( 'checkbox', 'hide_social_share', __('Hide Social Share','mv23theme') )->fancy()->set_width(20)
                    )
                ))
        );
        return $fields;
    }
}
