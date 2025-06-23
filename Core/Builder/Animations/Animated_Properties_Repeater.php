<?php

namespace Core\Builder\Animations;

use Ultimate_Fields\Field;
use Core\Builder\Animations\Properties;
use Ultimate_Fields\Container\Repeater_Group;

class Animated_Properties_Repeater
{
    public static function getRepeater($key, $label)
    {
        $repeater = Field::create('repeater', $key, $label)
            ->set_add_text(__('Add property', 'mv23theme'))
            ->set_layout('table')
            ->set_chooser_type('dropdown');

        foreach ( self::get_groups() as $group ) {
            $repeater->add_group($group);
        }

        return $repeater;
    }

    private static function get_groups()
    {
        $groups = [];
        $small_checkbox_styles = 'min-width: auto;width:auto;flex-grow:initial;padding-top:10px;';

        foreach (Properties::getProperties() as $property) {
            $key = $property['key'];
            $label = $property['label'];
            $type = $property['type'];

            if ($type === 'number') {
                $field = Field::create('number', 'value')->set_prefix(__($label, 'mv23theme'))->add_dependency('custom', '1', '!=');
                if (isset($property['enable_slider']) && $property['enable_slider']) {
                    $field->enable_slider($property['min'], $property['max'], $property['step'])->set_default_value(0);
                }

                $group = Repeater_Group::create($key)
                    ->set_title(__($label, '_mv23theme'))
                    ->add_fields(array(
                        $field
                    ));
            } elseif ($type === 'color') {
                $group = Repeater_Group::create($key)
                    ->set_title(__($label, '_mv23theme'))
                    ->add_fields(array(
                        Field::create('color', 'value')->set_prefix(__($label, '_mv23theme'))->add_dependency('custom', '1', '!='),
                    ));
            } elseif ($type === 'text') {
                $group = Repeater_Group::create($key)
                    ->set_title(__($label, '_mv23theme'))
                    ->add_fields(array(
                        Field::create('text', 'value')->set_prefix(__($label, '_mv23theme'))->add_dependency('custom', '1', '!='),
                    ));
            } elseif ($type === 'boolean') {
                $group = Repeater_Group::create($key)
                    ->set_title(__($label, '_mv23theme'))
                    ->add_fields(array(
                        Field::create('checkbox', 'value')->set_text( sprintf( __('Enable %s', '_mv23theme'), $label ) )
                    ));
            } elseif ($type === 'select') {
                $group = Repeater_Group::create($key)
                    ->set_title(__($label, '_mv23theme'))
                    ->add_fields(array(
                        Field::create('select', 'value')
                            ->set_prefix(__($label, '_mv23theme'))
                            ->add_options($property['options'])
                            ->add_dependency('custom', '1', '!=')
                    ));
            } else {
                // Handle other types if needed
                continue;
            }

            if ($type != 'boolean') {
                $custom_field = Field::create('text', 'custom_value')->set_prefix(__($label, '_mv23theme'))->add_dependency('custom');

                if( $type === 'select' && $key === 'stagger' ) $custom_field->set_default_value( 'amount:1|from:start|grid:auto' );

                $group->add_field( $custom_field );
                $group->add_field(Field::create('checkbox', 'custom')->set_attr('style', $small_checkbox_styles)->set_text('<span class="dashicons dashicons-edit"></span>'));
            }

            $group->add_field( Field::create('text', 'property')->set_default_value($key)->set_attr('style','display:none;') );

            $groups[$key] = $group;
        }

        return $groups;
    }
}
