<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Page_Container {

    public static function get_fields(){
        $fields = array(
            Field::create( 'tab', __('Container','mv23theme') ),

            Field::create( 'repeater', 'containers_width' )
                ->set_add_text(__('Add rule','mv23theme'))
                ->hide_label()
                ->add_group( 'item', array(
                    'edit_mode' => 'popup',
                    'title_template' => '<% if( scope != "custom" ){ %>
                        <% if( rule_name ){ %>
                            <%= rule_name %> |  
                        <% } %>
                        .<%= scope %>: <%= width %>px
                    <% } else { %>
                        <% if( rule_name ){ %>
                            <%= rule_name %> |  
                        <% } %>
                        <%= selector %>: <%= width %>px
                    <% } %>',
                    'fields' => array(
                        Field::create( 'text', 'rule_name' )->set_width(25),
                        Field::create( 'select', 'scope', __('Scope','mv23theme') )->add_options(array(
                            'global' => 'Global',
                            'header' => 'Header',
                            'footer' => 'Footer',
                            'single' => 'Single',
                            'page' => 'Page',
                            'archive' => 'Archive',
                            'blog' => 'Blog',
                            'custom' => 'Custom'
                        ))->set_width(25),
                        Field::create( 'text', 'selector' )->add_dependency('scope','custom')->set_width(25),
                        Field::create( 'number', 'width', __('Width','mv23theme') )->set_suffix('px')->set_placeholder('1240')->required()->set_width(25)
                    )
            ))
        );
        return $fields;
    }
}
