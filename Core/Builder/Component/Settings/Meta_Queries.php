<?php
namespace Core\Builder\Component\Settings;

use Core\Builder\Component\Settings\Repeater_Nested;
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

class Meta_Queries extends Repeater_Nested {

    protected static function get_groups() {
        
        $meta_query_group = new Repeater_Group( 'meta_query' );
        $meta_query_group->set_title(__('Meta Query','mv23theme'));
        $meta_query_group->add_fields(array(
            Field::create( 'text', 'key' )->hide_label()->set_prefix(__('Meta Key','mv23theme')),
            Field::create( 'select', 'compare' )->hide_label()->set_prefix(__('Compare','mv23theme'))
                ->add_options(array(
                    '' => '', 
                    '=' => __('Equal','mv23theme'),
                    '!=' => __('Not Equal','mv23theme'),
                    '>' => __('Greater Than','mv23theme'),
                    '>=' => __('Greater Than or Equal','mv23theme'),
                    '<' => __('Less Than','mv23theme'),
                    '<=' => __('Less Than or Equal','mv23theme'),
                    'IN' => __('In','mv23theme'),
                    'NOT IN' => __('Not In','mv23theme'),
                    'BETWEEN' => __('Between','mv23theme'),
                    'NOT BETWEEN' => __('Not Between','mv23theme'),
                    'LIKE' => __('Like','mv23theme'),
                    'NOT LIKE' => __('Not Like','mv23theme'),
                    'REGEXP' => __('Regexp','mv23theme'),
                    'NOT REGEXP' => __('Not Regexp','mv23theme'),
                    'RLIKE' => __('Rlike','mv23theme'),
                    'EXISTS' => __('Exists','mv23theme'),
                    'NOT EXISTS' => __('Not Exists','mv23theme'),
                )),
            Field::create( 'text', 'value' )->hide_label()->set_prefix(__('Meta Value','mv23theme'))
                ->add_suggestions( array( '@today', '@+1 day', '@-1 day', '@+1 week', '@-1 week', '@first day of this month', '@last day of this month' ) ),
            Field::create( 'select', 'type' )->hide_label()->set_prefix(__('Type','mv23theme'))
                ->add_options(array(
                    '' => '',
                    'NUMERIC' => __('Numeric','mv23theme'),
                    'BINARY' => __('Binary','mv23theme'),
                    'CHAR' => __('Char','mv23theme'),
                    'DATE' => __('Date','mv23theme'),
                    'DATETIME' => __('Datetime','mv23theme'),
                    'DECIMAL' => __('Decimal','mv23theme'),
                    'SIGNED' => __('Signed','mv23theme'),
                    'TIME' => __('Time','mv23theme'),
                    'UNSIGNED' => __('Unsigned','mv23theme')
                ))
        ));

        return [ $meta_query_group ];
    }

    public static function from_repeater( $meta_queries ) {
        $result = array();
        if( empty($meta_queries) ) return $result;

        foreach ($meta_queries as $entry) {
            $type = $entry['__type'] ?? ( isset($entry['relation']) ? 'relation' : ( isset($entry['grouped_queries']) ? 'grouped_queries' : 'meta_query' ) );

            if( $type === 'relation' ){
                if( !empty($entry['relation']) ) $result['relation'] = strtoupper($entry['relation']);
                continue;
            }

            if( $type === 'grouped_queries' ){
                $nested = self::from_repeater( $entry['grouped_queries'] ?? array() );
                if( !empty($nested) ) $result[] = $nested;
                continue;
            }

            $clause = self::build_meta_query_clause( $entry );
            if( $clause !== null ) $result[] = $clause;
        }

        return $result;
    }

    private static function build_meta_query_clause( $query_data ) {
        if( !isset($query_data['key']) || empty($query_data['key']) ) return null;

        $compare_needs_no_value = array('EXISTS', 'NOT EXISTS');

        // normalize compare (accepts "NOT_EXISTS" as well as "NOT EXISTS")
        $compare = ( !empty($query_data['compare']) ) ? strtoupper( str_replace('_', ' ', trim($query_data['compare'])) ) : '=';
        $requires_value = !in_array($compare, $compare_needs_no_value, true);

        // EXISTS / NOT EXISTS don't take a value; skip entries missing a value otherwise
        if( $requires_value && !isset($query_data['value']) ) return null;

        $meta_query = array(
            'key' => $query_data['key'],
            'compare' => $compare,
        );

        if( !empty($query_data['type']) ) $meta_query['type'] = $query_data['type'];

        if( $requires_value ){
            $value = $query_data['value'];

            // if value is a comma separated string, convert it to an array
            if( is_string($value) && strpos($value, ',') !== false ){
                $value = array_map('trim', explode(',', $value));

                // '=' with an array value breaks wpdb::prepare, so use 'IN' instead
                if( !in_array($compare, array('IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'), true) ){
                    $compare = ( $compare === '!=' ) ? 'NOT IN' : 'IN';
                    $meta_query['compare'] = $compare;
                }
            }

            if( is_string($value) ){
                $value = self::resolve_dynamic_date_value($value);
            }

            $meta_query['value'] = $value;
        }

        return $meta_query;
    }

    // resolves dynamic date tokens like "@today", "@+3 days", "@first day of this month"
    private static function resolve_dynamic_date_value( $value ) {
        if( strlen($value) < 2 || $value[0] !== '@' ) return $value;

        $timestamp = strtotime( substr($value, 1) );
        return ( $timestamp !== false ) ? date('Y-m-d', $timestamp) : $value;
    }
}