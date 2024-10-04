<?php
namespace Core\Builder;

use Ultimate_Fields\Field;

class Blocks_Layout_Settings{
    function __construct(){}
    
	public static function the_field( $slug = '', $title = '', $args = array()){
        /* slug and title can be setted in $args as first parameter */
        if( is_array($slug) ){
            $args = $slug;
        } else {
            if( !empty($slug) ){
                $args['slug'] = $slug;
                $args['title'] = $title;
            }
        }

        $defaults = array(
            'slug' => 'blocks_layout_settings',
            'title' => 'Layout Settings',
            'hide_label' => true
        );

        $args = wp_parse_args( $args, $defaults );

        $the_field = Field::create( 'common_settings_control', $args['slug'] )
            ->set_add_text( __($args['title'], 'default') )
            ->set_container( 'blocks_layout_settings_container' );

        if($args['hide_label']) $the_field->hide_label();
    
		return $the_field;
	}
}