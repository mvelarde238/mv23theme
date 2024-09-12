<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Page_Container {

    public static function get_fields(){
        $fields = array(
            Field::create( 'tab', __('Container','default') ),

            Field::create( 'repeater', 'containers_width' )
                ->set_add_text(__('Add rule','default'))
                ->hide_label()
                ->add_group( 'item', array(
                    'edit_mode' => 'popup',
                    'title_template' => '<% if( scope != "custom" ){ %>
                        .<%= scope %>: <%= width %>px
                    <% } else { %>
                        <%= selector %>: <%= width %>px
                    <% } %>',
                    'fields' => array(
                        Field::create( 'select', 'scope' )->add_options(array(
                            'global' => 'Global',
                            'header' => 'Header',
                            'footer' => 'Footer',
                            'single' => 'Single',
                            'page' => 'Page',
                            'archive' => 'Archive',
                            'blog' => 'Blog',
                            'custom' => 'Custom'
                        ))->set_width(30),
                        Field::create( 'text', 'selector' )->add_dependency('scope','custom')->set_width(30),
                        Field::create( 'number', 'width' )->set_suffix('px')->set_placeholder('1240')->required()->set_width(30)
                    )
            ))
        );
        return $fields;
    }
}
