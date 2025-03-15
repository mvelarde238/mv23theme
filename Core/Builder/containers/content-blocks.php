<?php
use Ultimate_Fields\Container;
use Core\Builder\Blocks_Layout;
use Ultimate_Fields\Field;

if( is_array(CONTENT_BUILDER_POSTTYPES) && count(CONTENT_BUILDER_POSTTYPES) > 0 ){
    foreach (CONTENT_BUILDER_POSTTYPES as $posttype) {

        // set Blocks_Layout arguments
        $blocks_layout_args = array( 
            'add_layout_control' => 0,
            'exclude' => array( 'inner_row' ) 
        );
        if( is_array(CONTENT_BUILDER_SETTINGS) && isset(CONTENT_BUILDER_SETTINGS[$posttype]) ){
            $blocks_layout_args = wp_parse_args( CONTENT_BUILDER_SETTINGS[$posttype], $blocks_layout_args );
        }

        // set container fields
        $fields = array(
            Blocks_Layout::the_field( $blocks_layout_args )
        );
        if($blocks_layout_args['add_layout_control']){
            $fields[] = Field::create( 'layout_control', 'blocks_control', 'Layout Control' )
                ->set_field( 'blocks_layout' )
                ->set_attr( 'style', 'border-top:1px solid silver; background: #eeeeee;' );
        }

        Container::create($posttype.'_content_blocks')
            ->add_location('post_type', $posttype )
            ->set_layout('grid')
            ->set_style('seamless')
            ->add_fields($fields);
    }
}