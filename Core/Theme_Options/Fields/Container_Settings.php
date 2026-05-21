<?php
namespace Core\Theme_Options\Fields;

use Ultimate_Fields\Field;

class Container_Settings {

    public static function get_fields(){
        $containers_settings_default = get_option( 'containers_settings', array() );

        $fields = array(
            Field::create( 'tab', __('Container','mv23theme') ),

            Field::create( 'repeater', 'containers_settings' )
                ->set_default_value( $containers_settings_default )
                ->set_add_text(__('Add rule','mv23theme'))
                // ->hide_label()
                ->add_group( 'item', array(
                    'edit_mode' => 'popup',
                    'title_template' => '<% if( scope != "custom" ){ %>
                        <% if( rule_name ){ %>
                            <%= rule_name %> |  
                        <% } %>
                        .<%= scope %>: <%= max_width %>px
                    <% } else { %>
                        <% if( rule_name ){ %>
                            <%= rule_name %> |  
                        <% } %>
                        <%= selector %>: <%= max_width %>px
                    <% } %>',
                    'fields' => array(
                        Field::create( 'text', 'rule_name' )
                            ->add_suggestions(array(
                                'Global Container',
                                'Header Container',
                                'Footer Container',
                            ))
                            ->set_width(20),
                        Field::create( 'select', 'scope', __('Scope','mv23theme') )->add_options(array(
                            'global' => 'Global',
                            'header' => 'Header',
                            'footer' => 'Footer',
                            'single' => 'Single',
                            'page' => 'Page',
                            'archive' => 'Archive',
                            'blog' => 'Blog',
                            'custom' => 'Custom'
                        ))->set_width(20),
                        Field::create( 'text', 'selector' )->add_dependency('scope','custom')->set_width(20),
                        Field::create( 'number', 'width', __('Width','mv23theme') )->set_placeholder('98')->set_suffix('%')->set_width(20),
                        Field::create( 'number', 'max_width', __('Max Width','mv23theme') )->set_placeholder('1240')->set_suffix('px')->required()->set_width(20)
                    )
            ))
        );
        return $fields;
    }
}
