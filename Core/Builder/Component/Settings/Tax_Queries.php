<?php
namespace Core\Builder\Component\Settings;

use Core\Builder\Component\Settings\Repeater_Nested;
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;
use Core\Builder\Core;

class Tax_Queries extends Repeater_Nested {

    protected static function get_groups() {
        $tax_queries_groups = [];

        $posttypes = Core::get_post_types();
        foreach($posttypes as $cpt_slug => $cpt_name){
            $taxonomies = get_object_taxonomies( $cpt_slug, 'objects' );
            if( is_array($taxonomies) && count($taxonomies) > 0 ){
                foreach($taxonomies as $tax_slug => $tax_object){
                    if( !$tax_object->public || !$tax_object->show_ui ) continue;
                    
                    $tax_name = $tax_object->labels->name;
                    
                    $tax_group = new Repeater_Group( 'taxgroup__' . $cpt_slug . '__' . $tax_slug );
                    $tax_group->set_title(__($tax_name,'mv23theme'));
                    $tax_group->add_fields(array(
                        Field::create( 'multiselect', 'terms' )
                            ->hide_label()
                            ->add_terms( $tax_slug ),
                        Field::create( 'checkbox', 'include_children' )
                            ->hide_label()
                            ->set_text(__('Include Children','mv23theme'))
                            ->set_default_value( true ),
                        Field::create( 'select', 'operator', __('Operator','mv23theme') )
                            ->hide_label()
                            ->set_prefix(__('Operator: ','mv23theme'))
                            ->add_options(array(
                                'IN' => __('IN','mv23theme'),
                                'NOT IN' => __('NOT IN','mv23theme'),
                                'AND' => __('AND','mv23theme'),
                                'EXISTS' => __('EXISTS','mv23theme'),
                                'NOT EXISTS' => __('NOT EXISTS','mv23theme'),
                            ))
                    ));

                    $tax_queries_groups[] = $tax_group;
                }
            }
        }

        return $tax_queries_groups;
    }

    public static function from_repeater( $tax_queries ) {
        $result = array();
        if( empty($tax_queries) ) return $result;

        foreach ($tax_queries as $entry) {
            $type = $entry['__type'] ?? ( isset($entry['relation']) ? 'relation' : ( isset($entry['grouped_queries']) ? 'grouped_queries' : 'tax_query' ) );

            if( $type === 'relation' ){
                if( !empty($entry['relation']) ) $result['relation'] = strtoupper($entry['relation']);
                continue;
            }

            if( $type === 'grouped_queries' ){
                $nested = self::from_repeater( $entry['grouped_queries'] ?? array() );
                if( !empty($nested) ) $result[] = $nested;
                continue;
            }

            $clause = self::build_tax_query_clause( $entry );
            if( $clause !== null ) $result[] = $clause;
        }

        return $result;
    }

    private static function build_tax_query_clause( $query_data ) {
        if( !is_array($query_data) || empty($query_data['__type']) ) return null;

        $group_type = $query_data['__type'];
        
        // Determine the taxonomy based on the group type and available post types and taxonomies.
        $taxonomy = null;
        foreach( Core::get_post_types() as $cpt_slug => $cpt_name ){
            $taxonomies = get_object_taxonomies( $cpt_slug, 'objects' );
            if( !is_array($taxonomies) ) continue;

            foreach( $taxonomies as $tax_slug => $tax_object ){
                if( !$tax_object->public || !$tax_object->show_ui ) continue;

                if( $group_type === 'taxgroup__' . $cpt_slug . '__' . $tax_slug ){
                    $taxonomy = $tax_slug;
                    break 2;
                }
            }
        }

        if( $taxonomy === null ) return null;

        $raw_operator = $query_data['operator'] ?? '';
        if( !is_string($raw_operator) ) return null;
        $operator = strtoupper( str_replace('_', ' ', trim($raw_operator)) );
        if( $operator === '' ) $operator = 'IN';
        $allowed_operators = array('IN', 'NOT IN', 'AND', 'EXISTS', 'NOT EXISTS');
        if( !in_array($operator, $allowed_operators, true) ) return null;

        $requires_terms = !in_array($operator, array('EXISTS', 'NOT EXISTS'), true);
        $terms = $query_data['terms'] ?? array();
        if( !is_array($terms) ) $terms = array($terms);
        $terms = array_values( array_filter($terms, static function($term) {
            return $term !== '' && $term !== null;
        }) );

        if( $requires_terms && empty($terms) ) return null;

        $clause = array(
            'taxonomy' => $taxonomy,
            'field' => 'term_id',
            'operator' => $operator,
        );

        if( $requires_terms ) $clause['terms'] = $terms;
        if( $operator !== 'EXISTS' && $operator !== 'NOT EXISTS' ){
            $clause['include_children'] = !empty($query_data['include_children']);
        }

        return $clause;
    }
}