<?php
namespace Core\Builder\Component\Settings;

use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

abstract class Repeater_Nested {

    /**
     * Builds a repeater field used to handle grouped settings. 
     *
     * @param string $key   Datastore key where the settings are stored.
     * @param string $label Field label shown in the UI.
     * @return \Ultimate_Fields\Field
     */
    /* abstract */ public static function getRepeater($key, $label)
    {
        $repeater = Field::create('repeater', $key, $label)
            ->set_chooser_type('tags')
            ->set_add_text( __('Add','mv23theme') )
            ->add_group( static::get_relation_group() );

        $grouped_queries_repeater = static::get_grouped_queries_repeater();
        
        foreach ( static::get_groups() as $group ) {
            $repeater->add_group($group);
            $grouped_queries_repeater->add_group($group);
        }
            
        $grouped_queries_group = static::get_grouped_queries_group();
        $grouped_queries_group->add_field( $grouped_queries_repeater );

        $repeater->add_group( $grouped_queries_group );

        return $repeater;
    }

    abstract protected static function get_groups();

    protected static function get_relation_group(){
        $relation_group = new Repeater_Group( 'relation' );
        $relation_group->set_title(__('Relation','mv23theme'));
        $relation_group->set_maximum( 1 );
        $relation_group->add_fields(array(
            Field::create( 'radio', 'relation' )->set_orientation( 'horizontal' )->add_options(array(
                'AND' => __('AND','mv23theme'),
                'OR' => __('OR','mv23theme')
            ))->hide_label()
        ));

        return $relation_group;
    }

    protected static function get_grouped_queries_group(){
        $grouped_queries_group = new Repeater_Group('grouped_queries' );
        $grouped_queries_group->set_title(__('Grouped Queries','mv23theme'));

        return $grouped_queries_group;
    }

    protected static function get_grouped_queries_repeater(){
        $repeater = Field::create( 'repeater', 'grouped_queries', __('Grouped Queries','mv23theme') )
            ->set_chooser_type('tags')
            ->set_add_text( __('Add grouped query','mv23theme') )
            ->hide_label()
            ->add_group( static::get_relation_group() );

        return $repeater;
    }
}